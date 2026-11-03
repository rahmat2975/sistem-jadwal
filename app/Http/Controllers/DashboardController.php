<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Set locale to Indonesian and timezone to Asia/Jakarta (WIB)
        Carbon::setLocale('id');
        $now = Carbon::now('Asia/Jakarta');

        // Current active academic year
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();

        // Active lecturer for the dashboard
        $currentLecturer = (auth()->check() && auth()->user()->lecturer)
            ? auth()->user()->lecturer
            : null;

        // Current date display matching the design: "Senin, 21 Oktober 2024" or dynamic today
        $formattedDate = $now->translatedFormat('l, d F Y');

        // Get day of week (1 = Senin, ... 7 = Minggu)
        $todayNumber = $now->dayOfWeekIso; // 1 for Monday to 7 for Sunday

        // Schedules for the actual current day; do not fill empty states with sample data.
        $dayToQuery = $todayNumber;

        $schedulesQuery = Schedule::with(['course', 'room', 'lecturers'])
            ->where('academic_year_id', $activeYear?->id)
            ->where('hari', $dayToQuery)
            ->orderBy('jam_mulai', 'asc');

        $filter = $request->query('filter', 'semua');
        if ($filter === 'pagi') {
            $schedulesQuery->where('jam_mulai', '<', '11:00:00');
        } elseif ($filter === 'siang') {
            $schedulesQuery->whereBetween('jam_mulai', ['11:00:00', '14:30:00']);
        } elseif ($filter === 'sore') {
            $schedulesQuery->where('jam_mulai', '>=', '14:30:00');
        }

        $allowedSemesters = ($activeYear?->semester ?? 'ganjil') === 'genap'
            ? [2, 4, 6, 8]
            : [1, 3, 5, 7];
        $selectedSemester = $request->query('semester', 'semua');
        if ($selectedSemester !== 'semua' && in_array((int) $selectedSemester, $allowedSemesters, true)) {
            if ((int) $selectedSemester === 7) {
                $schedulesQuery->where(function ($scheduleQuery) {
                    $scheduleQuery->where('semester', 7)
                        ->orWhereHas('course', function ($courseQuery) {
                            $courseQuery->where('semester_ideal', 7)
                                ->orWhere('tipe', 'pilihan');
                        });
                });
            } else {
                $schedulesQuery->where('semester', (int) $selectedSemester);
            }
        } else {
            $selectedSemester = 'semua';
        }

        $todaySchedules = $schedulesQuery->get();
        $this->applyCombinedCourseDisplay($todaySchedules);

        // Calculate quick summary metrics
        $totalClassesToday = $todaySchedules->count();
        $totalSksToday = $todaySchedules->sum(fn($s) => $s->course?->sks ?? 0);
        $missingRoomCount = $todaySchedules->filter(fn($s) => !$s->room)->count();
        $missingLecturerCount = $todaySchedules->filter(fn($s) => $s->lecturers->isEmpty())->count();
        
        $completedCount = 0;
        $activeCount = 0;
        $upcomingCount = 0;

        foreach ($todaySchedules as $s) {
            $detail = $s->status_detail;
            if ($detail['badge'] === 'done') {
                $completedCount++;
            } elseif ($detail['badge'] === 'active') {
                $activeCount++;
            } else {
                $upcomingCount++;
            }
        }

        // Daftar Mata Kuliah Diampu
        $taughtCourses = Course::where('is_active', true)
            ->whereHas('schedules', function ($q) use ($activeYear, $currentLecturer) {
                $q->where('academic_year_id', $activeYear?->id)
                    ->whereHas('lecturers', function ($q) use ($currentLecturer) {
                if ($currentLecturer) {
                    $q->where('lecturers.id', $currentLecturer->id);
                }
                    });
            })
            ->with(['schedules' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id);
                $q->with(['room', 'lecturers']);
            }])
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'activeYear',
            'currentLecturer',
            'formattedDate',
            'todaySchedules',
            'taughtCourses',
            'totalClassesToday',
            'totalSksToday',
            'missingRoomCount',
            'missingLecturerCount',
            'completedCount',
            'activeCount',
            'upcomingCount',
            'filter'
            , 'selectedSemester'
            , 'allowedSemesters'
            , 'academicYears'
        ));
    }

    private function applyCombinedCourseDisplay($schedules): void
    {
        $courses = Course::withTrashed()->get(['id', 'nama', 'sks', 'semester_ideal', 'is_active']);
        $groups = $courses->groupBy(function ($course) {
            $baseName = preg_replace('/^praktikum\s*/i', '', trim((string) $course->nama));
            return ($course->semester_ideal ?: 0) . '|' . strtolower(preg_replace('/\s+/', ' ', $baseName));
        });
        $combined = [];

        foreach ($groups as $group) {
            $theory = $group
                ->filter(fn ($course) => !preg_match('/^praktikum\s*/i', trim((string) $course->nama)))
                ->sortByDesc(fn ($course) => (int) $course->is_active)
                ->first();
            $practical = $group->filter(fn ($course) => preg_match('/^praktikum\s*/i', trim((string) $course->nama)));

            if ($theory && $practical->isNotEmpty()) {
                $display = ['name' => trim((string) $theory->nama), 'sks' => (int) $theory->sks + (int) $practical->sum('sks')];
                foreach ($group as $course) $combined[$course->id] = $display;
            }
        }

        $schedules->each(function ($schedule) use ($combined) {
            $display = $combined[$schedule->course_id] ?? null;
            $schedule->display_course_name = $display['name'] ?? $schedule->course?->nama;
            $schedule->display_sks = $display['sks'] ?? ($schedule->course?->sks ?? 0);
        });
    }
}
