<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Lecturer;
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

        $todaySchedules = $schedulesQuery->get();

        // Calculate quick summary metrics
        $totalClassesToday = $todaySchedules->count();
        $totalSksToday = $todaySchedules->sum(fn($s) => $s->course?->sks ?? 0);
        
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

        // Rekan Dosen Pengampu (exclude current lecturer)
        $colleagues = Lecturer::where('is_active', true)
            ->when($currentLecturer, fn ($q) => $q->where('id', '!=', $currentLecturer->id))
            ->take(4)
            ->get();

        return view('dashboard', compact(
            'activeYear',
            'currentLecturer',
            'formattedDate',
            'todaySchedules',
            'taughtCourses',
            'colleagues',
            'totalClassesToday',
            'totalSksToday',
            'completedCount',
            'activeCount',
            'filter'
            , 'academicYears'
        ));
    }
}
