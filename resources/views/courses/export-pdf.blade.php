<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kurikulum Teknik Informatika 2022 MBKM</title>
    <style>
        @page { size: A4 landscape; margin: 9mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #172033; font-family: Arial, Helvetica, sans-serif; font-size: 8px; background: #fff; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 8px 10px; border: 1px solid #d8dee8; border-radius: 6px; background: #f5f7fb; }
        .toolbar strong { font-size: 10px; }
        .toolbar button { border: 0; border-radius: 5px; padding: 7px 12px; background: #1f4e78; color: #fff; font-weight: 700; cursor: pointer; }
        .title { text-align: center; margin: 0 0 9px; line-height: 1.35; }
        .title h1 { margin: 0; font-size: 15px; }
        .title p { margin: 1px 0; font-size: 10px; font-weight: 700; }
        .columns { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 7mm; align-items: start; }
        .block { margin-bottom: 8px; break-inside: avoid; page-break-inside: avoid; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #718096; padding: 3px 4px; vertical-align: middle; line-height: 1.2; }
        th { background: #1f4e78; color: #fff; text-align: center; font-weight: 700; }
        td.num, td.sks { width: 8%; text-align: center; }
        td.code { width: 18%; text-align: center; }
        td.name { width: 66%; }
        .total td { background: #d9eaf7; font-weight: 700; }
        .section td { background: #d9eaf7; font-weight: 700; }
        .section td:first-child { text-align: left; }
        .muted { color: #4a5568; font-size: 7px; }
        @media print {
            .toolbar { display: none; }
            body { font-size: 7.5px; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <strong>Pratinjau PDF Kurikulum 2022 MBKM</strong>
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <header class="title">
        <h1>KURIKULUM KKNI MERDEKA BELAJAR TAHUN 2022</h1>
        <p>PROGRAM STUDI S1 TEKNIK INFORMATIKA</p>
        <p>FAKULTAS TEKNIK ELEKTRO DAN INFORMATIKA</p>
        <p>UNIVERSITAS SURAKARTA</p>
    </header>

    <main class="columns">
        <section>
            @foreach ([1, 2, 3, 4, 5] as $semester)
                @php($items = $semesterCourses->get($semester, collect()))
                <div class="block">
                    <table>
                        <thead><tr><th style="width:8%">No.</th><th style="width:18%">Kode</th><th>Mata Kuliah Semester {{ $semester }}</th><th style="width:8%">SKS</th></tr></thead>
                        <tbody>
                            @foreach ($items as $index => $course)
                                <tr><td class="num">{{ $index + 1 }}</td><td class="code">{{ $course->kode }}</td><td class="name">{{ $course->nama }}</td><td class="sks">{{ $course->sks }}</td></tr>
                            @endforeach
                            <tr class="total"><td colspan="3">Jumlah SKS Semester {{ $semester }}</td><td class="sks">{{ $items->sum('sks') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </section>

        <section>
            @foreach ([6, 7, 8] as $semester)
                @php($items = $semesterCourses->get($semester, collect()))
                <div class="block">
                    <table>
                        <thead><tr><th style="width:8%">No.</th><th style="width:18%">Kode</th><th>Mata Kuliah Semester {{ $semester }}</th><th style="width:8%">SKS</th></tr></thead>
                        <tbody>
                            @foreach ($items as $index => $course)
                                <tr><td class="num">{{ $index + 1 }}</td><td class="code">{{ $course->kode }}</td><td class="name">{{ $course->nama }}</td><td class="sks">{{ $course->sks }}</td></tr>
                            @endforeach
                            <tr class="total"><td colspan="3">Jumlah SKS Semester {{ $semester }}</td><td class="sks">{{ $items->sum('sks') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            @endforeach

            @foreach ($electiveGroups as $groupName => $items)
                <div class="block">
                    <table>
                        <thead><tr><th style="width:8%">No.</th><th style="width:18%">Kode</th><th>Mata Kuliah</th><th style="width:8%">SKS</th></tr></thead>
                        <tbody>
                            <tr class="section"><td colspan="4">Mata Kuliah Peminatan {{ $groupName }}</td></tr>
                            @foreach ($items as $index => $course)
                                <tr><td class="num">{{ $index + 1 }}</td><td class="code">{{ $course->kode }}</td><td class="name">{{ $course->nama }}</td><td class="sks">{{ $course->sks }}</td></tr>
                            @endforeach
                            <tr class="total"><td colspan="3">Jumlah SKS Peminatan</td><td class="sks">{{ $items->sum('sks') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </section>
    </main>
</body>
</html>
