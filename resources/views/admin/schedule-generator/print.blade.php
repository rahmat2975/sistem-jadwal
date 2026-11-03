<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $generation->nama }}</title>
    <style>
        body{font-family:Arial,sans-serif;color:#172033;margin:28px;font-size:11px}h1{font-size:19px;text-align:center;margin:0 0 5px;text-transform:uppercase}h2{font-size:14px;text-align:center;margin:0 0 3px;text-transform:uppercase}p{text-align:center;margin:3px;color:#5f6b7a}.actions{margin:18px 0}button{padding:9px 14px;border:0;border-radius:7px;background:#5b50e5;color:#fff;font-weight:bold;cursor:pointer}.day-title{margin:22px 0 0;background:#dbeafe;border:1px solid #94a3b8;padding:7px;font-weight:bold;text-transform:uppercase}table{width:100%;border-collapse:collapse}th,td{border:1px solid #94a3b8;padding:7px;text-align:left;vertical-align:top}th{background:#e8eef7;font-size:9px;text-transform:uppercase}td.center,th.center{text-align:center}.empty{color:#b45309;font-style:italic}@media print{body{margin:10mm}.actions{display:none}.day-title{break-after:avoid}table{font-size:10px}th,td{padding:5px}}
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Cetak / Simpan PDF</button></div>
    <h1>Jadwal Perkuliahan Program Studi Teknik Informatika</h1>
    <h2>Semester {{ strtoupper($generation->academicYear->semester) }} {{ $generation->academicYear->tahun }} (Kelas {{ strtoupper($generation->shift) }})</h2>
    <p>{{ $generation->nama }} · Draft hasil generator otomatis</p>
    @foreach($generation->schedules->sortBy([['hari','asc'],['jam_mulai','asc']])->groupBy('hari') as $day => $schedules)
        <div class="day-title">{{ \App\Models\Schedule::getNamaHari($day) }}</div>
        <table><thead><tr><th class="center" style="width:35px">No</th><th style="width:90px">Waktu</th><th style="width:80px">Kelas</th><th style="width:95px">Kode Mata Kuliah</th><th>Mata Kuliah</th><th class="center" style="width:45px">SKS</th><th style="width:100px">Ruang</th><th>Dosen</th></tr></thead><tbody>
        @foreach($schedules as $index => $schedule)
            <tr><td class="center">{{ $index + 1 }}</td><td>{{ substr($schedule->jam_mulai,0,5) }} - {{ substr($schedule->jam_selesai,0,5) }}</td><td>{{ $schedule->kelas }}</td><td>{{ $schedule->course->kode }}</td><td>{{ $schedule->nama_tampilan ?: $schedule->course->nama }}</td><td class="center">{{ $schedule->sks_tampilan ?: $schedule->course->sks }}</td><td class="{{ $schedule->room ? '' : 'empty' }}">{{ $schedule->room?->kode ?: 'Belum ditentukan' }}</td><td class="{{ $schedule->lecturer ? '' : 'empty' }}">{{ $schedule->lecturer?->nama ?: 'Belum ditentukan' }}</td></tr>
        @endforeach
        </tbody></table>
    @endforeach
</body>
</html>
