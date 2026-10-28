<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();
        
        $currentLecturer = auth()->user()?->lecturer;

        $schedules = Schedule::with(['course', 'room', 'lecturers'])
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        $totalSchedules = $schedules->count();
        $totalRooms = Room::where('is_active', true)->count();
        $totalLecturers = Lecturer::where('is_active', true)->count();
        $totalPagi = $schedules->where('shift', 'pagi')->count();
        $totalSore = $schedules->where('shift', 'sore')->count();

        $rooms = Room::where('is_active', true)->orderBy('nama')->get();

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
        ];

        return view('schedules.index', compact(
            'activeYear',
            'currentLecturer',
            'schedules',
            'totalSchedules',
            'totalRooms',
            'totalLecturers',
            'totalPagi',
            'totalSore',
            'rooms',
            'days'
            , 'academicYears'
        ));
    }

    public function today(Request $request)
    {
        Carbon::setLocale('id');
        $now = Carbon::now('Asia/Jakarta');

        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();

        $formattedDate = $now->translatedFormat('l, d F Y');
        $currentTime = $now->format('H:i');
        $serverTime = $now->format('H:i:s') . ' WIB';

        // 1 = Senin ... 7 = Minggu
        $realDay = $now->dayOfWeekIso;
        
        // Selected day mengikuti hari berjalan; pada akhir pekan tampilkan Senin sebagai pilihan awal.
        $defaultDay = ($realDay > 5) ? 1 : $realDay;
        $selectedDay = (int) $request->query('hari', $defaultDay);
        if ($selectedDay < 1 || $selectedDay > 5) {
            $selectedDay = $defaultDay;
        }

        $selectedShift = $request->query('shift', 'all');
        $search = $request->query('q', '');

        // Current user info
        $user = auth()->user();
        $currentLecturer = $user?->lecturer;

        // Build query for schedules
        $query = Schedule::with(['course', 'room', 'lecturers'])
            ->where('academic_year_id', $activeYear?->id)
            ->where('hari', $selectedDay)
            ->orderBy('jam_mulai', 'asc');

        if ($selectedShift && in_array($selectedShift, ['pagi', 'sore'])) {
            $query->where('shift', $selectedShift);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', function ($cq) use ($search) {
                    $cq->where('nama', 'like', "%{$search}%")
                       ->orWhere('kode', 'like', "%{$search}%");
                })->orWhereHas('room', function ($rq) use ($search) {
                    $rq->where('nama', 'like', "%{$search}%")
                       ->orWhere('kode', 'like', "%{$search}%");
                })->orWhereHas('lecturers', function ($lq) use ($search) {
                    $lq->where('nama', 'like', "%{$search}%");
                })->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $schedules = $query->get();

        // Metrics for the selected day
        $totalSessions = $schedules->count();
        $totalSks = $schedules->sum(fn($s) => $s->course?->sks ?? 0);
        $totalStudents = $schedules->sum('jumlah_mahasiswa');
        if ($totalStudents == 0) $totalStudents = $totalSessions * 35;

        // Accurate counts based on real time if viewing today
        $activeCount = 0;
        $upcomingCount = 0;
        $completedCount = 0;
        $activeSession = null;
        $nextUpcomingSession = null;

        $currentTimeStr = $now->format('H:i:s');
        $isViewingToday = ($selectedDay === $realDay);

        foreach ($schedules as $s) {
            if ($isViewingToday) {
                if ($currentTimeStr >= $s->jam_mulai && $currentTimeStr <= $s->jam_selesai) {
                    $activeCount++;
                    if (!$activeSession) $activeSession = $s;
                } elseif ($currentTimeStr < $s->jam_mulai) {
                    $upcomingCount++;
                    if (!$nextUpcomingSession) $nextUpcomingSession = $s;
                } else {
                    $completedCount++;
                }
            } else {
                $upcomingCount++;
            }
        }

        // Spotlight session: active session, or next upcoming session, or first session
        $spotlightSession = $activeSession ?? $nextUpcomingSession ?? $schedules->first();

        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
        ];

        return view('schedules.today', compact(
            'activeYear',
            'user',
            'currentLecturer',
            'formattedDate',
            'currentTime',
            'serverTime',
            'schedules',
            'totalSessions',
            'totalSks',
            'totalStudents',
            'activeCount',
            'upcomingCount',
            'completedCount',
            'spotlightSession',
            'activeSession',
            'selectedDay',
            'selectedShift',
            'realDay',
            'isViewingToday',
            'days',
            'search'
            , 'academicYears'
        ));
    }
}
