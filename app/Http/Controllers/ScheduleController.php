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
        $this->applyCombinedCourseDisplay($schedules);

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

    public function print(Request $request)
    {
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $shift = $request->query('shift');
        $semester = $request->query('semester');
        $day = $request->query('hari');
        $room = trim((string) $request->query('room', ''));
        $search = trim((string) $request->query('q', ''));

        $query = Schedule::with(['course', 'room', 'lecturers'])
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->orderBy('kelas');

        if (in_array($shift, ['pagi', 'sore'], true)) {
            $query->where('shift', $shift);
        } else {
            $shift = 'all';
        }

        if (is_numeric($semester) && (int) $semester >= 1 && (int) $semester <= 8) {
            if ((int) $semester === 7) {
                $query->where(function ($scheduleQuery) {
                    $scheduleQuery->where('semester', 7)
                        ->orWhereHas('course', fn ($courseQuery) => $courseQuery->where('tipe', 'pilihan'));
                });
            } else {
                $query->where('semester', (int) $semester);
            }
        } else {
            $semester = 'all';
        }

        if (is_numeric($day) && (int) $day >= 1 && (int) $day <= 5) {
            $query->where('hari', (int) $day);
        } else {
            $day = 'all';
        }

        if ($room !== '') {
            $query->whereHas('room', function ($roomQuery) use ($room) {
                $roomQuery->where('nama', 'like', "%{$room}%")
                    ->orWhere('kode', 'like', "%{$room}%");
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', function ($courseQuery) use ($search) {
                    $courseQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%");
                })->orWhereHas('room', function ($roomQuery) use ($search) {
                    $roomQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%");
                })->orWhereHas('lecturers', function ($lecturerQuery) use ($search) {
                    $lecturerQuery->where('nama', 'like', "%{$search}%");
                })->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $schedules = $query->get();
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat'];
        $groupedSchedules = $schedules->groupBy('hari');
        $shiftLabel = $shift === 'all' ? 'SEMUA SHIFT' : strtoupper($shift);

        return view('schedules.print', compact(
            'activeYear',
            'schedules',
            'groupedSchedules',
            'dayNames',
            'shift',
            'shiftLabel',
            'semester',
            'day',
            'room',
            'search'
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
        $selectedSemester = $request->query('semester', 'all');
        if ($selectedSemester !== 'all' && (!is_numeric($selectedSemester) || (int) $selectedSemester < 1 || (int) $selectedSemester > 8)) {
            $selectedSemester = 'all';
        }
        $search = $request->query('q', '');

        // Current user info
        $user = auth()->user();
        $currentLecturer = $user?->lecturer;
        if (!$currentLecturer && $user?->isDosen()) {
            $currentLecturer = Lecturer::where('nidn', $user->username)
                ->where('is_active', true)
                ->first();
        }
        $isLecturerView = (bool) ($user?->isDosen() && $currentLecturer);

        // Build query for schedules
        $query = Schedule::with(['course', 'room', 'lecturers'])
            ->where('academic_year_id', $activeYear?->id)
            ->where('hari', $selectedDay)
            ->orderBy('jam_mulai', 'asc');

        if ($isLecturerView) {
            $query->whereHas('lecturers', function ($lecturerQuery) use ($currentLecturer) {
                $lecturerQuery->whereKey($currentLecturer->id);
            });
        }

        if ($selectedShift && in_array($selectedShift, ['pagi', 'sore'])) {
            $query->where('shift', $selectedShift);
        }

        if ($selectedSemester !== 'all') {
            if ((int) $selectedSemester === 7) {
                $query->where(function ($scheduleQuery) {
                    $scheduleQuery->where('semester', 7)
                        ->orWhereHas('course', function ($courseQuery) {
                            $courseQuery->where('semester_ideal', 7)
                                ->orWhere('tipe', 'pilihan');
                        });
                });
            } else {
                $query->where('semester', (int) $selectedSemester);
            }
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

        $this->applyCombinedCourseDisplay($schedules);
        $shiftCounts = Schedule::where('academic_year_id', $activeYear?->id)
            ->where('hari', $selectedDay)
            ->selectRaw('shift, COUNT(*) as total')
            ->groupBy('shift')
            ->pluck('total', 'shift');

        $lecturerSemesters = $schedules->pluck('display_semester')->filter()->unique()->sort()->values();

        // Metrics for the selected day
        $totalSessions = $schedules->count();
        $totalSks = $schedules->sum(fn($s) => $s->course?->sks ?? 0);
        $totalStudents = $schedules->sum('jumlah_mahasiswa');
        if ($totalStudents == 0) $totalStudents = $totalSessions * 35;

        // Accurate counts based on real time if viewing today
        $activeCount = 0;
        $upcomingCount = 0;
        $completedCount = 0;

        $currentTimeStr = $now->format('H:i:s');
        $isViewingToday = ($selectedDay === $realDay);

        foreach ($schedules as $s) {
            if ($isViewingToday) {
                if ($currentTimeStr >= $s->jam_mulai && $currentTimeStr <= $s->jam_selesai) {
                    $activeCount++;
                } elseif ($currentTimeStr < $s->jam_mulai) {
                    $upcomingCount++;
                } else {
                    $completedCount++;
                }
            } else {
                $upcomingCount++;
            }
        }

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
            'selectedDay',
            'selectedShift',
            'selectedSemester',
            'realDay',
            'isViewingToday',
            'days',
            'search'
            , 'isLecturerView'
            , 'lecturerSemesters'
            , 'shiftCounts'
            , 'academicYears'
        ));
    }

    private function applyCombinedCourseDisplay($schedules): void
    {
        $curriculumCourses = Course::withTrashed()->get(['id', 'nama', 'sks', 'semester_ideal', 'is_active']);
        $courseGroups = $curriculumCourses->groupBy(function ($course) {
            $baseName = preg_replace('/^praktikum\s*/i', '', trim((string) $course->nama));
            $baseName = strtolower(preg_replace('/\s+/', ' ', $baseName));
            return ($course->semester_ideal ?: 0) . '|' . $baseName;
        });
        $combinedCourses = [];

        foreach ($courseGroups as $group) {
            $theory = $group
                ->filter(fn ($course) => !preg_match('/^praktikum\s*/i', trim((string) $course->nama)))
                ->sortByDesc(fn ($course) => (int) $course->is_active)
                ->first();
            $practical = $group->filter(fn ($course) => preg_match('/^praktikum\s*/i', trim((string) $course->nama)));

            if ($theory && $practical->isNotEmpty()) {
                $display = [
                    'name' => trim((string) $theory->nama),
                    'sks' => (int) $theory->sks + (int) $practical->sum('sks'),
                ];
                foreach ($group as $course) {
                    $combinedCourses[$course->id] = $display;
                }
            }
        }

        $schedules->each(function ($schedule) use ($combinedCourses) {
            $display = $combinedCourses[$schedule->course_id] ?? null;
            $schedule->display_course_name = $display['name'] ?? $schedule->course?->nama;
            $schedule->display_sks = $display['sks'] ?? ($schedule->course?->sks ?? 0);
            // Gunakan semester dari kurikulum sebagai sumber utama. Data jadwal
            // lama bisa menyimpan semester yang berbeda dari mata kuliahnya.
            $schedule->display_semester = $schedule->course?->semester_ideal ?? $schedule->semester;
        });
    }
}
