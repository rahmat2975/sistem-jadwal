<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();
        // Gunakan sumber data yang sama dengan menu admin agar katalog publik
        // tidak berbeda dari Kelola Mata Kuliah.
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
            $sksDetail = "{$c->sks} SKS";

            $c->meta = (object)[
                'silabus' => $c->deskripsi ?? 'Belum ada deskripsi mata kuliah.',
                'sks_detail' => $sksDetail,
                'sks_teori' => $c->sks_teori ?? ($c->sks >= 3 ? $c->sks - 1 : $c->sks),
                'sks_praktikum' => $c->sks_praktikum ?? ($isPraktikum ? $c->sks : ($c->sks >= 3 ? 1 : 0)),
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
        $semesterSks = collect(range(1, 8))->mapWithKeys(fn (int $semester) => [
            $semester => $courses->where('semester_ideal', $semester)->sum('sks'),
        ])->all();
        $kbkOptions = $courses->pluck('kategori')->filter()->unique()->sort()->values();
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

        return view('courses.index', compact('activeYear', 'academicYears', 'enrichedCourses', 'semesterDistribution', 'semesterSks', 'kbkOptions', 'stats', 'kbkList'));
    }

    public function export(Request $request)
    {
        $courses = Course::query()
            ->orderBy('semester_ideal')
            ->orderBy('kode')
            ->get();

        $cells = [];
        $merges = ['A1:I1', 'A2:I2', 'A3:I3', 'A4:I4'];
        $setCell = function (int $column, int $row, $value, int $style = 4) use (&$cells) {
            $cells[$row][$column] = ['value' => $value, 'style' => $style];
        };

        $setCell(1, 1, 'KURIKULUM KKNI MERDEKA BELAJAR TAHUN 2022', 1);
        $setCell(1, 2, 'PROGRAM STUDI S1 TEKNIK INFORMATIKA', 2);
        $setCell(1, 3, 'FAKULTAS TEKNIK ELEKTRO DAN INFORMATIKA', 2);
        $setCell(1, 4, 'UNIVERSITAS SURAKARTA', 2);

        // Pada workbook referensi, blok Semester 1-8 hanya berisi mata kuliah
        // wajib. Mata kuliah pilihan ditampilkan terpisah pada blok peminatan
        // di bawah Semester 8.
        $semesterCourses = $courses
            ->where('tipe', '!=', 'pilihan')
            ->groupBy('semester_ideal');
        $renderSemester = function (int $startColumn, int &$row, int $semester) use ($semesterCourses, $setCell) {
            $setCell($startColumn, $row, 'No.', 3);
            $setCell($startColumn + 1, $row, 'Kode', 3);
            $setCell($startColumn + 2, $row, "Mata Kuliah Semester {$semester}", 3);
            $setCell($startColumn + 3, $row, 'SKS', 3);
            $row++;

            $number = 1;
            foreach ($semesterCourses->get($semester, collect()) as $course) {
                $setCell($startColumn, $row, $number++, 4);
                $setCell($startColumn + 1, $row, $course->kode, 4);
                $setCell($startColumn + 2, $row, $course->nama, 4);
                $setCell($startColumn + 3, $row, (int) $course->sks, 6);
                $row++;
            }

            $setCell($startColumn, $row, "Jumlah SKS Semester {$semester}", 5);
            $setCell($startColumn + 3, $row, (int) $semesterCourses->get($semester, collect())->sum('sks'), 5);
            $row += 2;
        };

        $leftRow = 6;
        foreach ([1, 2, 3, 4, 5] as $semester) {
            $renderSemester(1, $leftRow, $semester);
        }

        $rightRow = 6;
        foreach ([6, 7, 8] as $semester) {
            $renderSemester(6, $rightRow, $semester);
        }

        $electiveGroups = $courses->where('tipe', 'pilihan')->groupBy(function ($course) {
            return $course->kelompok_peminatan ?: ($course->kategori ?: 'Mata Kuliah Pilihan');
        });
        foreach ($electiveGroups as $groupName => $groupCourses) {
            $setCell(6, $rightRow, 'No', 3);
            $setCell(7, $rightRow, 'Kode', 3);
            $setCell(8, $rightRow, 'Mata Kuliah', 3);
            $setCell(9, $rightRow, 'SKS', 3);
            $rightRow++;
            $setCell(6, $rightRow, "Mata Kuliah Peminatan {$groupName}", 7);
            $rightRow++;
            $number = 1;
            foreach ($groupCourses as $course) {
                $setCell(6, $rightRow, $number++, 4);
                $setCell(7, $rightRow, $course->kode, 4);
                $setCell(8, $rightRow, $course->nama, 4);
                $setCell(9, $rightRow, (int) $course->sks, 6);
                $rightRow++;
            }
            $setCell(6, $rightRow, 'Jumlah SKS', 5);
            $setCell(9, $rightRow, (int) $groupCourses->sum('sks'), 5);
            $rightRow += 2;
        }

        $setCell(6, $rightRow, 'Jumlah Total SKS', 5);
        $setCell(9, $rightRow, (int) $courses->sum('sks'), 5);
        $maxRow = max($leftRow, $rightRow);
        $sheetXml = $this->buildWorkbookSheetXml($cells, $merges, $maxRow);
        $path = tempnam(storage_path('app'), 'kurikulum_') . '.xlsx';
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $this->xlsxContentTypes());
        $zip->addFromString('_rels/.rels', $this->xlsxRootRelationships());
        $zip->addFromString('xl/workbook.xml', $this->xlsxWorkbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->xlsxWorkbookRelationships());
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->addFromString('xl/styles.xml', $this->xlsxStyles());
        $zip->close();

        return response()->download($path, 'Kurikulum_Teknik_Informatika_2022_MBKM.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $courses = Course::where('is_active', true)
            ->orderBy('semester_ideal')
            ->orderBy('kode')
            ->get();

        $semesterCourses = $courses
            ->where('tipe', '!=', 'pilihan')
            ->groupBy('semester_ideal');
        $electiveGroups = $courses
            ->where('tipe', 'pilihan')
            ->groupBy(fn ($course) => $course->kelompok_peminatan ?: ($course->kategori ?: 'Peminatan'));

        return view('courses.export-pdf', compact('semesterCourses', 'electiveGroups'));
    }

    private function buildWorkbookSheetXml(array $cells, array $merges, int $maxRow): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><dimension ref="A1:I' . $maxRow . '"/><sheetViews><sheetView workbookViewId="0"><pane ySplit="5" topLeftCell="A6" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews><sheetFormatPr defaultRowHeight="18"/><cols><col min="1" max="1" width="7"/><col min="2" max="2" width="14"/><col min="3" max="3" width="38"/><col min="4" max="4" width="8"/><col min="5" max="5" width="3"/><col min="6" max="6" width="7"/><col min="7" max="7" width="14"/><col min="8" max="8" width="38"/><col min="9" max="9" width="8"/></cols><sheetData>';
        foreach ($cells as $rowNumber => $rowCells) {
            $xml .= '<row r="' . $rowNumber . '">';
            foreach ($rowCells as $column => $cell) {
                $coordinate = $this->xlsxColumn($column) . $rowNumber;
                $value = $cell['value'];
                $style = $cell['style'];
                if (is_int($value) || is_float($value)) {
                    $xml .= '<c r="' . $coordinate . '" s="' . $style . '"><v>' . $value . '</v></c>';
                } else {
                    $xml .= '<c r="' . $coordinate . '" s="' . $style . '" t="inlineStr"><is><t>' . htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</t></is></c>';
                }
            }
            $xml .= '</row>';
        }
        $xml .= '</sheetData><mergeCells count="' . count($merges) . '">';
        foreach ($merges as $merge) $xml .= '<mergeCell ref="' . $merge . '"/>';
        $xml .= '</mergeCells><pageMargins left="0.3" right="0.3" top="0.5" bottom="0.5" header="0.2" footer="0.2"/></worksheet>';
        return $xml;
    }

    private function xlsxColumn(int $column): string
    {
        $result = '';
        while ($column > 0) {
            $remainder = ($column - 1) % 26;
            $result = chr(65 + $remainder) . $result;
            $column = intdiv($column - 1, 26);
        }
        return $result;
    }

    private function xlsxContentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>';
    }

    private function xlsxRootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
    }

    private function xlsxWorkbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Kurikulum 2022 MBKM" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private function xlsxWorkbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>';
    }

    private function xlsxStyles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="4"><font><sz val="10"/><name val="Arial"/></font><font><b/><sz val="14"/><name val="Arial"/></font><font><b/><sz val="11"/><name val="Arial"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="10"/><name val="Arial"/></font></fonts><fills count="4"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD9EAF7"/><bgColor indexed="64"/></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FF1F4E78"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="2"><border/><border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="8"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyAlignment="1"><alignment vertical="center"/></xf><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyAlignment="1"><alignment vertical="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyAlignment="1"><alignment horizontal="left" vertical="center"/></xf></cellXfs></styleSheet>';
    }
}
