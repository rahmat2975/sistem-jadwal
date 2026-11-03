@extends('layouts.app')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12 xl:items-start">
        <main class="space-y-6 sm:space-y-8 xl:col-span-12">
            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#4338CA] via-[#5B50E5] to-[#7C3AED] p-5 text-white shadow-xl shadow-[#5B50E5]/15 sm:p-8">
                <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-24 left-1/3 h-56 w-56 rounded-full bg-[#A78BFA]/20 blur-3xl"></div>
                <div class="relative z-10 max-w-2xl">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/20 bg-white/15 px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide text-white/90"><span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>Periode Aktif · {{ ucfirst($activeYear?->semester ?? 'Ganjil') }} {{ $activeYear?->tahun ?? '' }}</span>
                        <form method="GET" action="{{ route('dashboard') }}" class="w-full sm:w-auto">
                            <label for="dashboardAcademicYear" class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-white/70">Pilih Periode Akademik</label>
                            <div class="relative">
                                <select id="dashboardAcademicYear" name="academic_year_id" onchange="this.form.submit()" class="w-full appearance-none rounded-xl border border-white/25 bg-white/15 px-3.5 py-2.5 pr-10 text-xs font-semibold text-white outline-none backdrop-blur-sm transition focus:border-white/60 focus:bg-white/20 sm:min-w-[210px] sm:w-auto">
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" class="text-[#161A30]" @selected($activeYear?->id === $year->id)>{{ $year->tahun }} · {{ ucfirst($year->semester) }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-white/70"></i>
                            </div>
                        </form>
                    </div>
                    <h1 class="mt-7 text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl">Jadwal Perkuliahan Hari Ini</h1>
                    <p class="mt-2 max-w-lg text-sm leading-relaxed text-white/75">Pantau jadwal kuliah dan ketersediaan ruang dalam satu tampilan yang ringkas.</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center"><a href="{{ route('jadwal.semua', ['academic_year_id' => $activeYear?->id]) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-bold text-[#4F46E5] shadow-sm transition hover:bg-[#F4F2FF]">Lihat Semua Jadwal <i data-lucide="arrow-up-right" class="h-4 w-4"></i></a></div>
                </div>
            </section>

            <section class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center justify-between gap-2"><span class="text-[10px] font-bold uppercase tracking-wider text-[#94A3B8]">Kelas Hari Ini</span><i data-lucide="calendar-days" class="h-4 w-4 text-[#5B50E5]"></i></div>
                    <div class="mt-3 text-2xl font-extrabold text-[#161A30]">{{ $totalClassesToday }}</div>
                    <div class="mt-1 text-[11px] text-[#94A3B8]">Sesuai filter yang dipilih</div>
                </div>
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center justify-between gap-2"><span class="text-[10px] font-bold uppercase tracking-wider text-[#94A3B8]">Total SKS</span><i data-lucide="book-open" class="h-4 w-4 text-[#0EA5E9]"></i></div>
                    <div class="mt-3 text-2xl font-extrabold text-[#161A30]">{{ $totalSksToday }}</div>
                    <div class="mt-1 text-[11px] text-[#94A3B8]">Beban perkuliahan hari ini</div>
                </div>
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center justify-between gap-2"><span class="text-[10px] font-bold uppercase tracking-wider text-[#94A3B8]">Berlangsung</span><i data-lucide="radio" class="h-4 w-4 text-emerald-500"></i></div>
                    <div class="mt-3 text-2xl font-extrabold text-[#161A30]">{{ $activeCount }}</div>
                    <div class="mt-1 text-[11px] text-[#94A3B8]">Kelas sedang berjalan</div>
                </div>
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center justify-between gap-2"><span class="text-[10px] font-bold uppercase tracking-wider text-[#94A3B8]">Berikutnya</span><i data-lucide="clock-3" class="h-4 w-4 text-amber-500"></i></div>
                    <div class="mt-3 text-2xl font-extrabold text-[#161A30]">{{ $upcomingCount }}</div>
                    <div class="mt-1 text-[11px] text-[#94A3B8]">Kelas yang belum dimulai</div>
                </div>
            </section>

            <section>
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"><div><h2 class="text-lg font-extrabold text-[#161A30]">Jadwal Hari Ini</h2><p class="mt-1 text-xs text-[#8A94A6]">{{ $totalClassesToday }} kelas <span class="mx-1 text-[#CBD5E1]">·</span> {{ $totalSksToday }} SKS <span class="mx-1 text-[#CBD5E1]">·</span> WIB</p></div><div class="-mx-1 flex max-w-full gap-1.5 overflow-x-auto px-1 pb-1">@foreach(['semua' => 'Semua', 'pagi' => 'Pagi', 'siang' => 'Siang', 'sore' => 'Sore'] as $key => $label)<a href="{{ route('dashboard', ['filter' => $key, 'semester' => $selectedSemester, 'academic_year_id' => $activeYear?->id]) }}" class="shrink-0 rounded-xl px-3 py-2 text-xs font-bold transition {{ $filter === $key ? 'bg-[#5B50E5] text-white shadow-sm' : 'bg-white text-[#64748B] hover:bg-[#EEF2FF] hover:text-[#5B50E5]' }}">{{ $label }}</a>@endforeach</div></div>
                <div class="mb-5 flex flex-col gap-2 rounded-2xl border border-[#E5E7EB] bg-white p-3 sm:flex-row sm:items-center sm:justify-between sm:px-4"><span class="text-[10px] font-bold uppercase tracking-wider text-[#94A3B8]">Filter Semester</span><div class="-mx-1 flex max-w-full gap-1.5 overflow-x-auto px-1 pb-1 sm:pb-0"><a href="{{ route('dashboard', ['filter' => $filter, 'semester' => 'semua', 'academic_year_id' => $activeYear?->id]) }}" class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-bold transition {{ $selectedSemester === 'semua' ? 'bg-[#5B50E5] text-white shadow-sm' : 'bg-[#F4F5F9] text-[#64748B] hover:bg-[#EEF2FF] hover:text-[#5B50E5]' }}">Semua</a>@foreach($allowedSemesters as $semester)<a href="{{ route('dashboard', ['filter' => $filter, 'semester' => $semester, 'academic_year_id' => $activeYear?->id]) }}" class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-bold transition {{ (string) $selectedSemester === (string) $semester ? 'bg-[#5B50E5] text-white shadow-sm' : 'bg-[#F4F5F9] text-[#64748B] hover:bg-[#EEF2FF] hover:text-[#5B50E5]' }}">Semester {{ $semester }}</a>@endforeach</div></div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($todaySchedules as $schedule)
                        @php $detail = $schedule->status_detail; $course = $schedule->course; $coordinator = $schedule->coordinator(); $statusClass = $detail['badge'] === 'active' ? 'border-[#5B50E5]/30 ring-1 ring-[#5B50E5]/10' : 'border-[#E5E7EB]'; $badgeClass = $detail['badge'] === 'active' ? 'bg-[#EEF2FF] text-[#4F46E5]' : ($detail['badge'] === 'done' ? 'bg-[#F1F3F7] text-[#64748B]' : 'bg-[#ECFDF5] text-[#059669]'); @endphp
                        <article data-dashboard-schedule class="flex min-h-[250px] flex-col justify-between rounded-2xl border bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md {{ $statusClass }}">
                            <div><div class="flex items-center justify-between gap-3"><span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold {{ $badgeClass }}"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $detail['status'] }}</span><span class="truncate text-xs font-bold text-[#94A3B8]">{{ $course?->kode ?? '-' }}</span></div><h3 class="mt-4 line-clamp-2 min-h-[2.75rem] text-sm font-extrabold leading-5 text-[#161A30]">{{ $schedule->display_course_name ?? $course?->nama ?? 'Mata Kuliah' }}</h3><p class="mt-1 text-xs text-[#94A3B8]">{{ $schedule->kelas }} <span class="mx-1 text-[#CBD5E1]">·</span> Semester {{ $schedule->semester }} <span class="mx-1 text-[#CBD5E1]">·</span> {{ $schedule->jumlah_mahasiswa ?? 0 }} mahasiswa</p>@if($detail['badge'] === 'active')<div class="mt-4 h-1.5 overflow-hidden rounded-full bg-[#F1F3F7]"><div class="h-full rounded-full bg-[#5B50E5]" style="width: {{ $detail['progress'] }}%"></div></div>@endif<div class="mt-4 space-y-2.5 text-xs"><div class="flex items-center justify-between gap-3 text-[#161A30]"><span class="inline-flex items-center gap-2 font-semibold"><i data-lucide="clock-3" class="h-3.5 w-3.5 text-[#5B50E5]"></i>{{ substr($schedule->jam_mulai, 0, 5) }} – {{ substr($schedule->jam_selesai, 0, 5) }} WIB</span><span class="shrink-0 font-semibold text-[#8A94A6]">{{ $schedule->display_sks ?? $course?->sks ?? 0 }} SKS</span></div><div class="flex items-center gap-2 text-[#475569]"><i data-lucide="map-pin" class="h-3.5 w-3.5 shrink-0 text-[#94A3B8]"></i><span class="truncate">{{ $schedule->room?->nama ?? 'Ruang belum ditentukan' }}</span></div><div class="flex items-center gap-2 text-[#475569]"><i data-lucide="user-round" class="h-3.5 w-3.5 shrink-0 text-[#94A3B8]"></i><span class="truncate">{{ $coordinator?->nama ?? 'Dosen belum ditentukan' }}</span></div></div></div><div class="mt-5 flex items-center justify-between border-t border-[#F1F3F7] pt-4"><span class="truncate pr-3 text-[11px] font-medium text-[#94A3B8]">{{ $detail['message'] }}</span><a href="{{ route('jadwal.semua', ['academic_year_id' => $activeYear?->id]) }}" title="Lihat jadwal" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-[#E5E7EB] text-[#94A3B8] transition hover:border-[#5B50E5] hover:bg-[#EEF2FF] hover:text-[#5B50E5]"><i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i></a></div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-[#CBD5E1] bg-white p-10 text-center"><i data-lucide="calendar-off" class="mx-auto h-9 w-9 text-[#94A3B8]"></i><p class="mt-3 text-sm font-bold text-[#475569]">Belum ada jadwal untuk filter ini.</p><p class="mt-1 text-xs text-[#94A3B8]">Pilih periode lain atau buka semua jadwal.</p></div>
                    @endforelse
                </div>
                <div id="dashboardSchedulePagination" class="mt-4 hidden items-center justify-between rounded-2xl border border-[#E5E7EB] bg-white px-4 py-3">
                    <span id="dashboardPaginationInfo" class="text-xs text-[#8A94A6]"></span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="dashboardPrev" class="rounded-lg border border-[#E5E7EB] px-3 py-2 text-xs font-bold text-[#64748B] disabled:cursor-not-allowed disabled:opacity-40">Sebelumnya</button>
                        <button type="button" id="dashboardNext" class="rounded-lg border border-[#E5E7EB] px-3 py-2 text-xs font-bold text-[#64748B] disabled:cursor-not-allowed disabled:opacity-40">Berikutnya</button>
                    </div>
                </div>
                @if($missingRoomCount > 0 || $missingLecturerCount > 0)
                    <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                        <div class="flex items-start gap-2"><i data-lucide="triangle-alert" class="mt-0.5 h-4 w-4 shrink-0"></i><div><div class="font-bold">Perlu dilengkapi</div><div class="mt-1">@if($missingRoomCount > 0){{ $missingRoomCount }} jadwal belum memiliki ruangan.@endif @if($missingRoomCount > 0 && $missingLecturerCount > 0) @endif @if($missingLecturerCount > 0){{ $missingLecturerCount }} jadwal belum memiliki dosen pengampu.@endif</div></div></div>
                    </div>
                @endif
            </section>

        </main>

        @if(false)
            <aside class="space-y-6 sm:space-y-8 xl:sticky xl:top-6 xl:col-span-4"><section class="rounded-3xl border border-[#E5E7EB] bg-white p-5 shadow-sm sm:p-6"><div class="mb-5 flex items-center justify-between"><div><h2 class="text-base font-extrabold text-[#161A30]">Dosen Pengampu</h2><p class="mt-1 text-xs text-[#94A3B8]">Dosen dan laboratorium</p></div><a href="{{ route('pengajar.index') }}" title="Lihat dosen" class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#5B50E5] transition hover:bg-[#5B50E5] hover:text-white"><i data-lucide="arrow-up-right" class="h-4 w-4"></i></a></div><div class="space-y-4">@forelse($colleagues as $colleague)<div class="flex items-center gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EEF2FF] text-xs font-extrabold text-[#5B50E5]">{{ strtoupper(substr($colleague->nama, 0, 2)) }}</div><div class="min-w-0"><div class="truncate text-xs font-bold text-[#161A30]">{{ $colleague->nama }}</div><div class="mt-0.5 truncate text-[11px] text-[#94A3B8]">{{ $colleague->laboratorium ?? 'Laboratorium belum ditentukan' }}</div></div></div>@empty<p class="text-xs text-[#94A3B8]">Belum ada data dosen.</p>@endforelse</div><a href="{{ route('pengajar.index') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#F8FAFC] px-4 py-3 text-xs font-bold text-[#5B50E5] transition hover:bg-[#EEF2FF]">Lihat Semua Dosen <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i></a></section></aside>
        @endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = Array.from(document.querySelectorAll('[data-dashboard-schedule]'));
    const pagination = document.getElementById('dashboardSchedulePagination');
    const info = document.getElementById('dashboardPaginationInfo');
    const previous = document.getElementById('dashboardPrev');
    const next = document.getElementById('dashboardNext');
    const pageSize = 10;
    let page = 1;
    const totalPages = Math.max(1, Math.ceil(cards.length / pageSize));

    if (!pagination || cards.length <= pageSize) return;
    pagination.classList.remove('hidden');
    pagination.classList.add('flex');

    const renderPage = () => {
        const start = (page - 1) * pageSize;
        cards.forEach((card, index) => card.classList.toggle('hidden', index < start || index >= start + pageSize));
        info.textContent = `Menampilkan ${start + 1}-${Math.min(start + pageSize, cards.length)} dari ${cards.length} jadwal`;
        previous.disabled = page === 1;
        next.disabled = page === totalPages;
    };

    previous.addEventListener('click', () => { if (page > 1) { page--; renderPage(); } });
    next.addEventListener('click', () => { if (page < totalPages) { page++; renderPage(); } });
    renderPage();
});
</script>
@endsection
