<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\GeneratedSchedule;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\ScheduleGeneration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScheduleGeneratorController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();
        $generations = ScheduleGeneration::with('academicYear')->withCount('schedules')->latest()->paginate(10)->withQueryString();
        $selected = $request->filled('generation_id')
            ? ScheduleGeneration::with(['academicYear', 'schedules.course', 'schedules.room', 'schedules.lecturer'])->find($request->integer('generation_id'))
            : null;

        return view('admin.schedule-generator.index', compact('academicYears', 'generations', 'selected'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'shift' => ['required', Rule::in(['pagi', 'sore'])],
            'semester' => ['nullable', 'integer', 'between:1,8'],
            'nama' => ['required', 'string', 'max:150'],
            'jumlah_mahasiswa' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $academicYear = AcademicYear::findOrFail($validated['academic_year_id']);
        $allowedSemesters = $academicYear->semester === 'ganjil' ? [1, 3, 5, 7] : [2, 4, 6, 8];
        if (!empty($validated['semester']) && !in_array((int) $validated['semester'], $allowedSemesters, true)) {
            return back()->withInput()->withErrors([
                'semester' => 'Semester mata kuliah tidak sesuai dengan semester akademik yang dipilih.',
            ]);
        }

        $courses = Course::where('is_active', true)
            ->when($validated['semester'] ?? null,
                fn ($q, $semester) => $q->where('semester_ideal', $semester),
                fn ($q) => $q->whereIn('semester_ideal', $allowedSemesters))
            ->orderBy('semester_ideal')->orderBy('kode')->get();
        $courseUnits = $courses->groupBy(fn ($course) => ($course->semester_ideal ?: 1) . '|' . ($course->kelompok_peminatan ?: 'Wajib') . '|' . $this->courseGroupName($course->nama))
            ->map(function ($group) {
                $primary = $group->first(fn ($course) => !$this->isPracticalCourse($course->nama)) ?: $group->first();
                return [
                    'course' => $primary,
                    'name' => $primary->nama,
                    'sks' => (int) $group->sum('sks'),
                    'semester' => $primary->semester_ideal ?: 1,
                    'kelompok' => $primary->kelompok_peminatan ?: ($primary->tipe === 'pilihan' ? 'Peminatan' : 'Wajib'),
                    'needsLab' => $group->contains(fn ($course) => $this->isPracticalCourse($course->nama) || $course->kategori === 'Praktikum'),
                    'combined' => $group->count() > 1,
                ];
            })->values()
            // Jadwalkan mata kuliah dengan ruang paling terbatas terlebih dahulu.
            ->sortByDesc(fn ($unit) => $this->unitPriority($unit))
            ->values();
        $rooms = Room::where('is_active', true)->orderByDesc('jenis')->orderBy('kapasitas')->get();
        $lecturers = Lecturer::where('is_active', true)->orderBy('nama')->get();

        if ($courses->isEmpty()) {
            return back()->withInput()->withErrors(['semester' => 'Tidak ada mata kuliah aktif pada semester yang dipilih.']);
        }
        if ($rooms->isEmpty() || $lecturers->isEmpty()) {
            return back()->withInput()->withErrors(['nama' => 'Data ruangan dan dosen aktif harus tersedia sebelum membuat jadwal.']);
        }

        $slots = $validated['shift'] === 'pagi'
            ? ['08:00', '09:40', '10:30', '11:20', '12:10', '13:00']
            : ['16:00', '17:40', '18:30', '20:10'];
        $days = range(1, 4);
        $roomBusy = $lecturerBusy = $classBusy = $roomUsage = $semesterDayLoad = [];
        $assignments = [];
        $lecturerByCourse = Schedule::with('lecturers')
            ->where('academic_year_id', $validated['academic_year_id'])
            ->get()->groupBy('course_id')->map(fn ($items) => $items->first()->lecturers->first()?->id);
        $lecturerIndex = 0;

        foreach ($courseUnits as $unit) {
            $course = $unit['course'];
            $semester = $unit['semester'];
            $class = 'Smt ' . $semester . ($unit['kelompok'] !== 'Wajib' ? ' - ' . $unit['kelompok'] : '');
            $duration = $unit['sks'] * 50;
            $lecturerId = $lecturerByCourse->get($course->id) ?: $lecturers[$lecturerIndex++ % $lecturers->count()]->id;
            $allowedRoomCodes = $this->allowedRoomCodes($unit);
            $roomCandidates = $rooms->filter(function ($room) use ($course, $validated, $unit, $allowedRoomCodes) {
                if ($room->kapasitas < $validated['jumlah_mahasiswa']) return false;
                if ($room->kode === 'FISIP' && $allowedRoomCodes !== ['FISIP']) return false;
                if ($allowedRoomCodes !== null) return in_array($room->kode, $allowedRoomCodes, true);
                $needsLab = $unit['needsLab'] || str_contains(strtolower((string) $course->kategori), 'lab');
                return $room->jenis === ($needsLab ? 'laboratorium' : 'kelas');
            });
            if ($roomCandidates->isEmpty() && $allowedRoomCodes === null) {
                $roomCandidates = $rooms->filter(fn ($room) => $room->kapasitas >= $validated['jumlah_mahasiswa']);
            }

            $placed = false;
            $balancedDays = collect($days)
                ->sortBy(fn ($day) => [$semesterDayLoad[$class][$day] ?? 0, $day])
                ->values();
            foreach ($balancedDays as $day) {
                foreach ($slots as $start) {
                    $end = date('H:i', strtotime($start . ' +' . $duration . ' minutes'));
                    if (substr($end, 0, 5) > ($validated['shift'] === 'pagi' ? '14:40' : '22:00')) continue;
                    $timeKey = $day . '|' . $start . '|' . $end;
                    if (($classBusy[$class . '|' . $day] ?? false) && $this->overlaps($classBusy[$class . '|' . $day], $start, $end)) continue;
                    if (($lecturerBusy[$lecturerId . '|' . $day] ?? false) && $this->overlaps($lecturerBusy[$lecturerId . '|' . $day], $start, $end)) continue;
                    $room = $roomCandidates
                        ->sortBy(fn ($r) => [$roomUsage[$r->id] ?? 0, $r->kapasitas])
                        ->first(fn ($r) => !isset($roomBusy[$r->id . '|' . $day]) || !$this->overlaps($roomBusy[$r->id . '|' . $day], $start, $end));
                    if (!$room) continue;
                    $assignment = compact('course', 'room', 'lecturerId', 'semester', 'class', 'day', 'start', 'end') + ['name' => $unit['name'], 'sks' => $unit['sks'], 'combined' => $unit['combined']];
                    $assignments[] = $assignment;
                    $classBusy[$class . '|' . $day][] = [$start, $end];
                    $lecturerBusy[$lecturerId . '|' . $day][] = [$start, $end];
                    $roomBusy[$room->id . '|' . $day][] = [$start, $end];
                    $roomUsage[$room->id] = ($roomUsage[$room->id] ?? 0) + 1;
                    $semesterDayLoad[$class][$day] = ($semesterDayLoad[$class][$day] ?? 0) + 1;
                    $placed = true;
                    break 2;
                }
            }
            if (!$placed) {
                $assignments[] = [
                    'course' => $course, 'room' => null, 'lecturerId' => $lecturerId,
                    'semester' => $semester, 'class' => $class, 'day' => 1,
                    'name' => $unit['name'], 'sks' => $unit['sks'], 'combined' => $unit['combined'],
                    'start' => '08:00', 'end' => '08:00',
                ];
            }
        }

        $generation = DB::transaction(function () use ($validated, $assignments) {
            $generation = ScheduleGeneration::create([
                'academic_year_id' => $validated['academic_year_id'], 'created_by' => auth()->id(),
                'shift' => $validated['shift'], 'semester' => $validated['semester'] ?? null,
                'nama' => $validated['nama'], 'status' => 'draft',
            ]);
            foreach ($assignments as $item) {
                $generation->schedules()->create([
                    'course_id' => $item['course']->id, 'nama_tampilan' => $item['name'], 'sks_tampilan' => $item['sks'], 'room_id' => $item['room']?->id,
                    'lecturer_id' => $item['lecturerId'], 'kelas' => $item['class'], 'semester' => $item['semester'],
                    'hari' => $item['day'], 'jam_mulai' => $item['start'], 'jam_selesai' => $item['end'],
                    'jumlah_mahasiswa' => $validated['jumlah_mahasiswa'],
                    'catatan' => $item['room'] ? ($item['combined'] ? 'Teori dan praktikum digabung dalam satu blok.' : null) : 'Belum mendapat slot ruang; perlu penyesuaian manual.',
                ]);
            }
            return $generation;
        });

        return redirect()->route('admin.schedule-generator.index', ['generation_id' => $generation->id])->with('success', 'Draft jadwal otomatis berhasil dibuat. Jadwal lama tidak diubah.');
    }

    public function destroy(ScheduleGeneration $generation)
    {
        $generation->delete();
        return redirect()->route('admin.schedule-generator.index')->with('success', 'Draft generator berhasil dihapus.');
    }

    public function print(ScheduleGeneration $generation)
    {
        $generation->load(['academicYear', 'schedules.course', 'schedules.room', 'schedules.lecturer']);

        return view('admin.schedule-generator.print', compact('generation'));
    }

    private function overlaps(array $ranges, string $start, string $end): bool
    {
        foreach ($ranges as [$oldStart, $oldEnd]) {
            if ($oldStart < $end && $oldEnd > $start) return true;
        }
        return false;
    }

    private function courseGroupName(string $name): string
    {
        return trim(preg_replace('/\s+/', ' ', preg_replace('/^praktikum\s*/i', '', strtolower($name))));
    }

    private function isPracticalCourse(string $name): bool
    {
        return str_starts_with(strtolower(trim($name)), 'praktikum');
    }

    private function unitPriority(array $unit): int
    {
        $name = strtolower($unit['name']);
        $priority = 0;
        if (str_contains($name, 'agama kristen') || str_contains($name, 'agama katolik') || str_contains($name, 'agama katholik')) $priority += 100;
        if (str_contains($name, 'agama islam')) $priority += 90;
        if (str_contains($name, 'jaringan') || str_contains($name, 'multimedia') || str_contains($name, 'animasi')) $priority += 80;
        // Teori memakai tiga ruang kelas umum yang kapasitasnya lebih terbatas.
        // Jadwalkan lebih dahulu agar tidak kalah oleh mata kuliah laboratorium.
        if (!$unit['needsLab'] && $priority === 0) $priority += 70;
        if ($unit['needsLab']) $priority += 60;
        if ($unit['sks'] >= 3) $priority += 50;
        return $priority + $unit['sks'];
    }

    private function allowedRoomCodes(array $unit): ?array
    {
        $name = strtolower($unit['name']);

        if (str_contains($name, 'agama kristen') || str_contains($name, 'agama katolik') || str_contains($name, 'agama katholik')) {
            return ['FISIP'];
        }
        if (str_contains($name, 'agama islam')) {
            return ['B.43', 'B.44', 'B.45'];
        }
        if (str_contains($name, 'jaringan')) {
            return ['Lab J'];
        }
        if (str_contains($name, 'multimedia') || str_contains($name, 'animasi') || str_contains($name, 'cinematografi')) {
            return ['Lab M'];
        }
        if ($unit['sks'] >= 3 || $unit['needsLab']) {
            return ['Lab 1', 'Lab 2', 'Lab J', 'Lab M'];
        }

        return null;
    }
}
