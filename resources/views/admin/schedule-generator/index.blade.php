@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full gap-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-[#161A30]">Generator Jadwal Otomatis</h1>
        <p class="text-xs sm:text-sm text-[#8A94A6] mt-1">Buat draft jadwal pagi atau sore tanpa mengubah jadwal lama.</p>
    </div>

    @if(session('success'))<div class="rounded-xl bg-[#ECFDF5] px-4 py-3 text-xs font-semibold text-[#047857]">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="rounded-xl bg-[#FEF2F2] px-4 py-3 text-xs text-[#B91C1C]">{{ $errors->first() }}</div>@endif

    <div class="grid grid-cols-1 xl:grid-cols-[360px_1fr] gap-6 items-start">
        <div class="rounded-3xl bg-white border border-[#E5E7EB] shadow-sm p-6">
            <h2 class="font-bold text-[#161A30]">Buat Draft Baru</h2>
            <p class="text-xs text-[#8A94A6] mt-1 mb-5">Sistem mencari slot ruang, dosen, dan waktu yang tidak bentrok.</p>
            <form method="POST" action="{{ route('admin.schedule-generator.generate') }}" class="space-y-4">
                @csrf
                <div><label class="block text-xs font-bold mb-1">Tahun Akademik</label><select id="generatorAcademicYear" name="academic_year_id" required class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3 py-2.5 text-xs">@foreach($academicYears as $year)<option value="{{ $year->id }}" data-academic-semester="{{ $year->semester }}">{{ $year->tahun }} · {{ ucfirst($year->semester) }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-bold mb-1">Shift</label><select name="shift" required class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3 py-2.5 text-xs"><option value="pagi">Kelas Pagi</option><option value="sore">Kelas Sore</option></select></div>
                <div><label class="block text-xs font-bold mb-1">Kelompok Semester</label><select id="generatorSemester" name="semester" class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3 py-2.5 text-xs"><option value="">Semua semester sesuai periode</option>@for($i=1;$i<=8;$i++)<option value="{{ $i }}" data-parity="{{ $i % 2 === 1 ? 'ganjil' : 'genap' }}">Semester {{ $i }}</option>@endfor</select><p id="semesterHint" class="mt-1 text-[10px] text-[#8A94A6]"></p></div>
                <div><label class="block text-xs font-bold mb-1">Nama Draft</label><input name="nama" required value="Draft Jadwal {{ now()->format('d-m-Y H:i') }}" class="w-full rounded-xl border border-[#E5E7EB] px-3 py-2.5 text-xs"></div>
                <div><label class="block text-xs font-bold mb-1">Perkiraan Mahasiswa per Kelas</label><input name="jumlah_mahasiswa" type="number" min="1" max="1000" value="40" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-2.5 text-xs"></div>
                <button class="w-full rounded-xl bg-[#5B50E5] px-4 py-3 text-xs font-bold text-white hover:bg-[#4F46E5]">Buat Draft Otomatis</button>
            </form>
        </div>

        <div class="rounded-3xl bg-white border border-[#E5E7EB] shadow-sm overflow-hidden">
            <div class="p-5 border-b border-[#E5E7EB]"><h2 class="font-bold text-[#161A30]">Riwayat Draft Generator</h2><p class="text-xs text-[#8A94A6] mt-1">Draft tersimpan terpisah dari jadwal utama.</p></div>
            <div class="divide-y divide-[#F1F3F7]">
                @forelse($generations as $generation)
                    <a href="{{ route('admin.schedule-generator.index', ['generation_id' => $generation->id]) }}" class="flex items-center justify-between gap-3 p-4 hover:bg-[#FBFBFC] {{ $selected?->id === $generation->id ? 'bg-[#F5F3FF]' : '' }}">
                        <div><div class="text-sm font-bold text-[#161A30]">{{ $generation->nama }}</div><div class="text-[11px] text-[#8A94A6]">{{ $generation->academicYear->tahun }} · {{ ucfirst($generation->academicYear->semester) }} · Shift {{ ucfirst($generation->shift) }}</div></div>
                        <span class="rounded-full bg-[#EEF2FF] px-2.5 py-1 text-[10px] font-bold text-[#5B50E5]">{{ $generation->schedules_count }} data</span>
                    </a>
                @empty
                    <div class="p-8 text-center text-xs text-[#8A94A6]">Belum ada draft jadwal otomatis.</div>
                @endforelse
            </div>
            <div class="p-4">{{ $generations->onEachSide(1)->links() }}</div>
        </div>
    </div>

    @if($selected)
        <div class="rounded-3xl bg-white border border-[#E5E7EB] shadow-sm overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-5 border-b border-[#E5E7EB]"><div><h2 class="font-bold text-[#161A30]">{{ $selected->nama }}</h2><p class="text-xs text-[#8A94A6] mt-1">{{ $selected->schedules->count() }} mata kuliah · Draft terpisah</p></div><div class="flex gap-2"><a target="_blank" href="{{ route('admin.schedule-generator.print', $selected) }}" class="rounded-xl bg-[#EEF2FF] px-3 py-2 text-xs font-bold text-[#5B50E5]">Cetak / PDF</a><form method="POST" action="{{ route('admin.schedule-generator.destroy', $selected) }}" onsubmit="return confirm('Hapus draft ini?')">@csrf @method('DELETE')<button class="rounded-xl bg-red-50 px-3 py-2 text-xs font-bold text-red-600">Hapus Draft</button></form></div></div>
            <div class="overflow-x-auto"><table class="w-full text-left text-xs"><thead class="bg-[#F9FAFC] text-[10px] uppercase tracking-wider text-[#8A94A6]"><tr><th class="px-5 py-3">Mata Kuliah</th><th class="px-5 py-3">SKS</th><th class="px-5 py-3">Semester</th><th class="px-5 py-3">Hari / Jam</th><th class="px-5 py-3">Ruang</th><th class="px-5 py-3">Dosen</th></tr></thead><tbody class="divide-y divide-[#F1F3F7]">@foreach($selected->schedules->sortBy(['hari','jam_mulai']) as $schedule)<tr><td class="px-5 py-3"><div class="font-bold text-[#161A30]">{{ $schedule->nama_tampilan ?: $schedule->course->nama }}</div><div class="font-mono text-[10px] text-[#8A94A6]">{{ $schedule->course->kode }}</div></td><td class="px-5 py-3">{{ $schedule->sks_tampilan ?: $schedule->course->sks }}</td><td class="px-5 py-3">Smt {{ $schedule->semester }}</td><td class="px-5 py-3">{{ \App\Models\Schedule::getNamaHari($schedule->hari) }}<br>{{ substr($schedule->jam_mulai,0,5) }}–{{ substr($schedule->jam_selesai,0,5) }}</td><td class="px-5 py-3">{{ $schedule->room?->kode ?: 'Belum ditentukan' }}</td><td class="px-5 py-3">{{ $schedule->lecturer?->nama ?: 'Belum ditentukan' }}</td></tr>@endforeach</tbody></table></div>
        </div>
    @endif
</div>
<script>
const academicYearSelect=document.getElementById('generatorAcademicYear');
const semesterSelect=document.getElementById('generatorSemester');
const semesterHint=document.getElementById('semesterHint');
function syncGeneratorSemesters(){const period=academicYearSelect.options[academicYearSelect.selectedIndex]?.dataset.academicSemester;const allowed=period==='ganjil'?[1,3,5,7]:[2,4,6,8];[...semesterSelect.options].forEach((option,index)=>{if(index===0){option.hidden=false;return;}const visible=allowed.includes(Number(option.value));option.hidden=!visible;option.disabled=!visible;if(!visible&&option.selected)semesterSelect.value='';});semesterHint.textContent=period==='ganjil'?'Otomatis: semester 1, 3, 5, dan 7':'Otomatis: semester 2, 4, 6, dan 8';}
academicYearSelect.addEventListener('change',syncGeneratorSemesters);syncGeneratorSemesters();
</script>
@endsection
