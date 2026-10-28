<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();
        $courses = Course::where('is_active', true)
            ->with(['schedules' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id)->with(['room', 'lecturers']);
            }])
            ->orderBy('semester_ideal', 'asc')
            ->orderBy('kode', 'asc')
            ->get();

        // Enrich each course dynamically
        $enrichedCourses = $courses->map(function ($c) {
            $firstSchedule = $c->schedules->first();
            $firstLecturer = $firstSchedule?->lecturers->first();
            $roomName = $firstSchedule?->room?->nama ?? '-';

            $isPraktikum = str_contains(strtolower($c->nama), 'praktikum');
            $sksDetail = $isPraktikum 
                ? "{$c->sks} SKS (Praktikum Lab)" 
                : "{$c->sks} SKS (Teori)";

            $c->meta = (object)[
                'silabus' => $c->deskripsi ?? 'Belum ada deskripsi mata kuliah.',
                'sks_detail' => $sksDetail,
                'sifat' => $c->tipe === 'pilihan' ? 'Pilihan Peminatan' : 'Wajib Program Studi',
                'sifat_type' => $c->tipe ?? 'wajib',
                'kbk' => $c->kategori ?? '-',
                'lab_ruang' => $roomName,
                'icon' => $isPraktikum ? 'desktop_windows' : ($c->tipe === 'pilihan' ? 'stars' : 'menu_book'),
                'icon_color' => $isPraktikum ? 'text-[#059669]' : 'text-[#5B50E5]',
                'koordinator' => $firstLecturer?->nama ?? '-',
                'initials' => $c->singkatan ?? (isset($c->nama) ? strtoupper(substr($c->nama, 0, 2)) : 'TI'),
                'avatar_bg' => 'bg-[#5B50E5]',
                'avatar_text' => 'text-white',
                'kbk_sub' => $c->kategori ?? '-',
                'prasyarat' => $c->semester_ideal > 1 ? 'Mata Kuliah Prasyarat Smt ' . ($c->semester_ideal - 1) : 'Tanpa Prasyarat',
            ];

            return $c;
        });

        // Semester SKS Distribution Breakdown
        $activeSemester = $activeYear?->semester === 'genap' ? [2, 4, 6, 8] : [1, 3, 5, 7];
        $semesterDistribution = collect(range(1, 8))->map(function (int $semester) use ($courses, $activeSemester) {
            $semesterCourses = $courses->where('semester_ideal', $semester);
            $wajib = $semesterCourses->where('tipe', 'wajib')->sum('sks');
            $pilihan = $semesterCourses->where('tipe', 'pilihan')->sum('sks');
            $total = $wajib + $pilihan;

            return [
                'sem' => "Sem. {$semester}",
                'sks' => $total,
                'percent' => $total > 0 ? (int) round(($wajib / $total) * 100) : 0,
                'active' => in_array($semester, $activeSemester, true),
                'wajib' => $wajib,
                'pilihan' => $pilihan,
            ];
        })->all();
        /*
        $semesterDistribution = [
            ['sem' => 'Sem. 1', 'sks' => 20, 'percent' => 83, 'active' => false, 'wajib' => 20, 'pilihan' => 0],
            ['sem' => 'Sem. 2', 'sks' => 20, 'percent' => 83, 'active' => false, 'wajib' => 20, 'pilihan' => 0],
            ['sem' => 'Sem. 3', 'sks' => 24, 'percent' => 100, 'active' => false, 'wajib' => 24, 'pilihan' => 0],
            ['sem' => 'Sem. 4', 'sks' => 23, 'percent' => 96, 'active' => false, 'wajib' => 23, 'pilihan' => 0],
            ['sem' => 'Sem. 5', 'sks' => 20, 'percent' => 83, 'active' => true,  'wajib' => 20, 'pilihan' => 0],
            ['sem' => 'Sem. 6', 'sks' => 19, 'percent' => 79, 'active' => false, 'wajib' => 10, 'pilihan' => 9],
            ['sem' => 'Sem. 7', 'sks' => 13, 'percent' => 54, 'active' => true,  'wajib' => 2,  'pilihan' => 11],
            ['sem' => 'Sem. 8', 'sks' => 6,  'percent' => 25, 'active' => false, 'wajib' => 6,  'pilihan' => 0],
        ]; */

        // Summary Stats
        $stats = [
            'total_mk' => $courses->count(),
            'mk_wajib' => $courses->where('tipe', 'wajib')->count(),
            'mk_pilihan' => $courses->where('tipe', 'pilihan')->count(),
            'total_sks' => $courses->sum('sks'),
            'sks_semester_aktif' => $courses->whereIn('semester_ideal', $activeSemester)->sum('sks'),
            'total_rombel' => $courses->flatMap->schedules->count(),
            'sks_lab' => $courses->filter(fn($c) => str_contains(strtolower($c->nama), 'praktikum'))->sum('sks'),
            'percent_lab' => $courses->sum('sks') > 0 ? (int) round(($courses->filter(fn($c) => str_contains(strtolower($c->nama), 'praktikum'))->sum('sks') / $courses->sum('sks')) * 100) : 0,
            'active_labs' => $courses->flatMap->schedules->pluck('room')->filter(fn($room) => $room?->jenis === 'laboratorium')->unique('id')->count(),
        ];

        // Kelompok KBK diambil dari kategori yang benar-benar tersedia.
        $kbkIcons = [
            'sistem informasi' => ['icon' => 'web', 'bg_icon' => 'bg-[#EEF2FF]', 'text_icon' => 'text-[#4F46E5]'],
            'multimedia' => ['icon' => 'movie_filter', 'bg_icon' => 'bg-[#E3DFFF]', 'text_icon' => 'text-[#4233cc]'],
            'jaringan' => ['icon' => 'router', 'bg_icon' => 'bg-[#ECFDF5]', 'text_icon' => 'text-[#059669]'],
        ];
        $kbkList = $courses->filter(fn ($course) => filled($course->kategori))
            ->groupBy('kategori')
            ->map(function ($group, $category) use ($kbkIcons) {
                $schedule = $group->flatMap->schedules->first();
                $icon = collect($kbkIcons)->first(fn ($style, $key) => str_contains(strtolower($category), $key))
                    ?? ['icon' => 'category', 'bg_icon' => 'bg-[#F4F5F9]', 'text_icon' => 'text-[#5B50E5]'];

                return array_merge([
                    'name' => $category,
                    'mk_count' => $group->count(),
                    'desc' => $group->pluck('nama')->filter()->join(', '),
                    'coordinator' => $schedule?->lecturers->first()?->nama ?? '-',
                ], $icon);
            })->values()->all();

        return view('courses.index', compact('activeYear', 'academicYears', 'enrichedCourses', 'semesterDistribution', 'stats', 'kbkList'));
    }
}
