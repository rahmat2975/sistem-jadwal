<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();

        $lecturers = Lecturer::where('is_active', true)
            ->with(['schedules' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id);
                $q->with(['course', 'room'])->orderBy('hari')->orderBy('jam_mulai');
            }])
            ->withCount(['schedules' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id);
            }])
            ->orderBy('nama', 'asc')
            ->get();

        $totalLecturers = $lecturers->count();
        $totalSchedules = $lecturers->sum('schedules_count');

        // Group by laboratory
        $labs = $lecturers->pluck('laboratorium')->unique()->filter()->values();

        return view('lecturers.index', compact('activeYear', 'academicYears', 'lecturers', 'totalLecturers', 'totalSchedules', 'labs'));
    }
}
