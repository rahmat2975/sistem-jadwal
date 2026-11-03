<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Jadwal Perkuliahan {{ $activeYear?->tahun ?? '' }}</title>
    <style>
        @page { size: A4 portrait; margin: 9mm 8mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #111; font-family: Arial, Helvetica, sans-serif; font-size: 9px; }
        .toolbar { display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 12px; }
        .toolbar button { border: 1px solid #999; background: #fff; border-radius: 4px; padding: 7px 12px; cursor: pointer; }
        .heading { text-align: center; margin: 0 0 7px; }
        .heading h1 { margin: 0; font-size: 14px; font-weight: 700; }
        .heading h2 { margin: 4px 0 0; font-size: 12px; font-weight: 700; }
        .heading p { margin: 4px 0 0; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #222; padding: 4px 4px; vertical-align: middle; }
        th { background: #9dbbdc; text-align: center; font-weight: 700; }
        td.center { text-align: center; }
        td.day { font-weight: 700; text-align: center; }
        td.course { text-align: left; }
        .subtle { color: #333; font-size: 8px; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        @media print { .toolbar { display: none; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        <button type="button" onclick="window.close()">Tutup</button>
    </div>

    <div class="heading">
        <h1>JADWAL PERKULIAHAN PROGRAM STUDI TEKNIK INFORMATIKA</h1>
        <h2>SEMESTER {{ strtoupper($activeYear?->semester ?? 'GANJIL') }} {{ $activeYear?->tahun ?? '2026/2027' }} (KELAS {{ $shiftLabel }})</h2>
        @if($semester !== 'all' || $day !== 'all' || $room !== '' || $search !== '')
            <p>
                @if($semester !== 'all') Semester {{ $semester }} @endif
                @if($day !== 'all') · {{ $dayNames[(int) $day] ?? '' }} @endif
                @if($room !== '') · Ruang {{ $room }} @endif
                @if($search !== '') · Filter: {{ $search }} @endif
            </p>
        @endif
    </div>

    <table>
        <colgroup>
            <col style="width: 3%">
            <col style="width: 3%">
            <col style="width: 10%">
            <col style="width: 5%">
            <col style="width: 9%">
            <col style="width: 24%">
            <col style="width: 4%">
            <col style="width: 5%">
            <col style="width: 8%">
            <col style="width: 29%">
        </colgroup>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Hari</th>
                <th rowspan="2">Waktu</th>
                <th rowspan="2">Kelas</th>
                <th rowspan="2">Kode Mata<br>Kuliah</th>
                <th rowspan="2">Mata Kuliah</th>
                <th colspan="2">SKS</th>
                <th rowspan="2">Ruang</th>
                <th rowspan="2">Dosen</th>
            </tr>
            <tr>
                <th>Teori</th>
                <th>Praktik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groupedSchedules as $dayNumber => $daySchedules)
                @foreach($daySchedules as $rowIndex => $schedule)
                    @php
                        $course = $schedule->course;
                        $courseName = strtolower($course?->nama ?? '');
                        $courseCategory = strtolower($course?->kategori ?? '');
                        $hasExplicitBreakdown = (($course?->sks_teori ?? 0) + ($course?->sks_praktikum ?? 0)) > 0;
                        $isPracticalOnly = str_contains($courseName, 'praktikum') || $courseCategory === 'praktikum';
                        $start = \Carbon\Carbon::parse($schedule->jam_mulai);
                        $end = \Carbon\Carbon::parse($schedule->jam_selesai);
                        $durationMinutes = $start->diffInMinutes($end);
                        // Data baru memakai SKS teori/praktikum secara eksplisit.
                        // Durasi 150 menit tetap menjadi sumber kebenaran untuk data
                        // jadwal lama yang masih menyimpan praktikum sebagai 0.
                        $teori = (int) ($course?->sks_teori ?? 0);
                        $praktikum = (int) ($course?->sks_praktikum ?? 0);
                        $hasPracticalPart = !$isPracticalOnly && (
                            $praktikum > 0 ||
                            ($course?->sks ?? 0) >= 3 ||
                            $durationMinutes >= 150
                        );

                        if (!$hasExplicitBreakdown || ($praktikum === 0 && $durationMinutes >= 150 && !$isPracticalOnly)) {
                            $teori = $isPracticalOnly ? 0 : ($hasPracticalPart ? 2 : ($course?->sks ?? 0));
                            $praktikum = $isPracticalOnly ? ($course?->sks ?? 0) : ($hasPracticalPart ? 1 : 0);
                        }
                        $lecturers = $schedule->lecturers->pluck('nama')->filter()->implode(', ');
                    @endphp
                    <tr>
                        @if($rowIndex === 0)
                            <td class="center" rowspan="{{ $daySchedules->count() }}">{{ $rowIndex + 1 }}</td>
                            <td class="day" rowspan="{{ $daySchedules->count() }}">{{ $dayNames[$dayNumber] ?? $schedule->hari_nama }}</td>
                        @endif
                        <td class="center">{{ substr($schedule->jam_mulai, 0, 5) }} - {{ substr($schedule->jam_selesai, 0, 5) }}</td>
                        <td class="center">{{ $schedule->kelas ?: '-' }}</td>
                        <td class="center">{{ $course?->kode ?: '-' }}</td>
                        <td class="course">{{ $course?->nama ?: '-' }}</td>
                        <td class="center">{{ $teori > 0 ? $teori : '-' }}</td>
                        <td class="center">{{ $praktikum > 0 ? $praktikum : '-' }}</td>
                        <td class="center">{{ $schedule->room?->nama ?: $schedule->room?->kode ?: '-' }}</td>
                        <td>{{ $lecturers ?: '-' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="10" class="center">Tidak ada data jadwal untuk filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
