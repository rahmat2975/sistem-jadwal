<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();
        
        $query = Schedule::with(['course', 'room', 'lecturers'])
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai');

        if ($request->filled('shift') && $request->shift !== 'all') {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('hari') && $request->hari !== 'all') {
            $query->where('hari', $request->hari);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', function ($cq) use ($search) {
                    $cq->where('nama', 'like', "%{$search}%")
                       ->orWhere('kode', 'like', "%{$search}%");
                })->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $schedules = $query->paginate(10)->withQueryString();
        $courses = Course::where('is_active', true)->orderBy('nama')->get();
        $rooms = Room::where('is_active', true)->orderBy('nama')->get();
        $lecturers = Lecturer::where('is_active', true)->orderBy('nama')->get();

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
        ];

        return view('admin.schedules.index', compact(
            'schedules',
            'courses',
            'rooms',
            'lecturers',
            'activeYear',
            'days'
            , 'academicYears'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')->where('is_active', true)],
            'academic_year_id' => 'required|exists:academic_years,id',
            'room_id' => ['nullable', Rule::exists('rooms', 'id')->where('is_active', true)],
            'kelas' => 'required|string|max:50',
            'shift' => 'required|in:pagi,sore',
            'angkatan' => 'required|integer|min:1',
            'semester' => 'required|integer|between:1,8',
            'jumlah_mahasiswa' => 'nullable|integer|min:0',
            'hari' => 'required|integer|between:1,7',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'catatan' => 'nullable|string|max:255',
            'lecturer_id' => ['required', Rule::exists('lecturers', 'id')->where('is_active', true)],
            'member_id' => ['nullable', Rule::exists('lecturers', 'id')->where('is_active', true)],
        ]);

        $this->validateScheduleWindow($validated);
        $this->validateScheduleConflict($validated);

        $schedule = null;
        DB::transaction(function () use ($validated, &$schedule) {
            $schedule = Schedule::create([
                'academic_year_id' => $validated['academic_year_id'],
                'course_id' => $validated['course_id'],
                'room_id' => $validated['room_id'] ?: null,
                'kelas' => $validated['kelas'],
                'shift' => $validated['shift'],
                'angkatan' => $validated['angkatan'],
                'semester' => $validated['semester'],
                'jumlah_mahasiswa' => $validated['jumlah_mahasiswa'] ?? 0,
                'hari' => $validated['hari'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'catatan' => $validated['catatan'] ?: null,
                'created_by' => auth()->id(),
            ]);

            $schedule->lecturers()->attach($validated['lecturer_id'], ['peran' => 'koordinator', 'urutan' => 1]);

            if (!empty($validated['member_id']) && $validated['member_id'] != $validated['lecturer_id']) {
                $schedule->lecturers()->attach($validated['member_id'], ['peran' => 'anggota', 'urutan' => 2]);
            }
        });

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal perkuliahan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')->where('is_active', true)],
            'room_id' => ['nullable', Rule::exists('rooms', 'id')->where('is_active', true)],
            'kelas' => 'required|string|max:50',
            'shift' => 'required|in:pagi,sore',
            'angkatan' => 'required|integer|min:1',
            'semester' => 'required|integer|between:1,8',
            'jumlah_mahasiswa' => 'nullable|integer|min:0',
            'hari' => 'required|integer|between:1,7',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'catatan' => 'nullable|string|max:255',
            'lecturer_id' => ['required', Rule::exists('lecturers', 'id')->where('is_active', true)],
            'member_id' => ['nullable', Rule::exists('lecturers', 'id')->where('is_active', true)],
        ]);

        $validated['academic_year_id'] = $schedule->academic_year_id;
        $this->validateScheduleWindow($validated);
        $this->validateScheduleConflict($validated, $schedule->id);

        DB::transaction(function () use ($validated, $schedule) {
            $schedule->update([
                'course_id' => $validated['course_id'],
                'room_id' => $validated['room_id'] ?: null,
                'kelas' => $validated['kelas'],
                'shift' => $validated['shift'],
                'angkatan' => $validated['angkatan'],
                'semester' => $validated['semester'],
                'jumlah_mahasiswa' => $validated['jumlah_mahasiswa'] ?? 0,
                'hari' => $validated['hari'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'catatan' => $validated['catatan'],
            ]);

            $syncData = [
                $validated['lecturer_id'] => ['peran' => 'koordinator', 'urutan' => 1]
            ];

            if (!empty($validated['member_id']) && $validated['member_id'] != $validated['lecturer_id']) {
                $syncData[$validated['member_id']] = ['peran' => 'anggota', 'urutan' => 2];
            }

            $schedule->lecturers()->sync($syncData);
        });

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal perkuliahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->lecturers()->detach();
        $schedule->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal perkuliahan berhasil dihapus.');
    }

    private function validateScheduleWindow(array $data): void
    {
        $start = Carbon::createFromFormat('H:i', $data['jam_mulai']);
        $end = Carbon::createFromFormat('H:i', $data['jam_selesai']);

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'jam_selesai' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }
    }

    private function validateScheduleConflict(array $data, ?int $ignoreId = null): void
    {
        $lecturerIds = array_values(array_filter([
            $data['lecturer_id'] ?? null,
            $data['member_id'] ?? null,
        ]));

        $conflict = Schedule::query()
            ->where('academic_year_id', $data['academic_year_id'] ?? request('academic_year_id'))
            ->where('hari', $data['hari'])
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->where(function ($query) use ($data, $lecturerIds) {
                $query->where('kelas', $data['kelas']);
                if (!empty($data['room_id'])) {
                    $query->orWhere('room_id', $data['room_id']);
                }
                $query->orWhereHas('lecturers', fn ($lecturers) => $lecturers->whereIn('lecturers.id', $lecturerIds));
            })
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Jadwal bentrok dengan kelas, ruang, atau dosen lain pada waktu tersebut.',
            ]);
        }
    }
}
