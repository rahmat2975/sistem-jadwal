@extends('layouts.app')

@section('title', 'Jadwal Perkuliahan Hari Ini - SI Jadwal TI Universitas Surakarta')

@section('content')
<div class="flex flex-col w-full pb-12 gap-y-6">

    <!-- 1. BREADCRUMB & HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-1">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2 text-[#8A94A6] text-xs font-semibold">
                <a href="{{ route('dashboard') }}" class="hover:text-[#5B50E5]">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span>Jadwal</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#5B50E5] font-bold">{{ $days[$selectedDay] ?? 'Hari Ini' }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#161A30] tracking-tight">
                    Jadwal Perkuliahan Hari {{ $days[$selectedDay] ?? 'Ini' }}
                </h1>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-xs font-bold shadow-xs border border-[#4F46E5]/15">
                    <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                    <span>{{ $formattedDate }}</span>
                    <span class="text-[#CBD5E1]">·</span>
                    <span class="font-mono text-[#5B50E5]" id="liveDigitalClock">{{ $serverTime }}</span>
                </div>
            </div>
        </div>

        <!-- Global Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('jadwal.semua') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#5B50E5] hover:bg-[#4233cc] text-white shadow-md shadow-[#5B50E5]/25 transition-all text-xs font-bold">
                <span class="material-symbols-outlined text-[18px]">calendar_view_week</span>
                <span>Semua Jadwal</span>
            </a>
            <a href="{{ route('ruang.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-[#F9FAFC] text-[#161A30] shadow-xs transition-all text-xs font-bold border border-[#E5E7EB]">
                <span class="material-symbols-outlined text-[18px] text-[#5B50E5]">meeting_room</span>
                <span>Status Ruang</span>
            </a>
        </div>
    </div>

    <!-- 2. CONTEXTUAL WELCOME BANNER (DOSEN / MAHASISWA / ADMIN) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#4233cc] via-[#5B50E5] to-[#6063ee] text-white shadow-xl shadow-[#5B50E5]/20 p-6 sm:p-7">
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -bottom-20 w-60 h-60 rounded-full bg-black/10 blur-xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-inner flex-shrink-0">
                    <span class="material-symbols-outlined text-[28px] sm:text-[32px]">
                        @if(auth()->check() && auth()->user()->isMahasiswa())
                            school
                        @elseif(auth()->check() && auth()->user()->isDosen())
                            badge
                        @else
                            hub
                        @endif
                    </span>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        @auth
                            @if(auth()->user()->isMahasiswa())
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/30 text-emerald-200 border border-emerald-400/30 text-xs font-bold">
                                    Mahasiswa TI (NIM: {{ auth()->user()->username }})
                                </span>
                            @elseif(auth()->user()->isDosen())
                                <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white font-mono text-xs font-bold">
                                    NIDN: {{ $currentLecturer?->nidn ?? auth()->user()->username }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-xs font-semibold">
                                    Dosen Pengampu
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-500/30 text-rose-200 border border-rose-400/30 text-xs font-bold">
                                    Administrator Sistem
                                </span>
                            @endif
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-xs font-semibold">
                                Akses Tamu / Publik
                            </span>
                        @endauth
                        <span class="px-2.5 py-0.5 rounded-full bg-white/15 text-white/90 text-xs font-medium">
                            {{ ucfirst($activeYear->semester ?? 'ganjil') }} · {{ $activeYear->tahun ?? '2026/2027' }} · WIB
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                        @auth
                            Halo, {{ auth()->user()->lecturer?->nama ?? auth()->user()->name }}
                        @else
                            Jadwal Perkuliahan Teknik Informatika
                        @endauth
                    </h2>
                    
                    <p class="text-xs sm:text-sm text-[#e8e4ff] mt-1 max-w-2xl leading-relaxed">
                        @if($isViewingToday)
                            Hari ini ada <strong class="text-white font-bold">{{ $totalSessions }} kelas</strong> · {{ $totalSks }} SKS.
                        @else
                            <strong class="text-white font-bold">{{ $days[$selectedDay] ?? 'Hari terpilih' }}</strong> · {{ $totalSessions }} kelas · {{ $totalSks }} SKS.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Quick Summary Widget in Banner -->
            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/20 flex-shrink-0 self-start lg:self-auto">
                <div class="flex flex-col text-right">
                    <span class="text-xs text-[#e8e4ff] uppercase tracking-wider font-semibold">Total Beban Hari Ini</span>
                    <span class="text-base text-white font-extrabold">{{ $totalSks }} SKS · {{ $totalSessions }} Kelas</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[22px]">timer</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. DAY SELECTOR TABS (SENIN - JUMAT) -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 bg-white p-2 rounded-2xl border border-[#E5E7EB] shadow-xs">
        <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider px-3 hidden sm:inline-block">Pilih Hari:</span>
        @foreach($days as $num => $dayName)
            @php
                $isActiveTab = ($selectedDay === $num);
                $isRealToday = ($realDay === $num);
            @endphp
            <a href="{{ route('jadwal.hari-ini', ['hari' => $num, 'shift' => $selectedShift]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap {{ $isActiveTab ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/25' : 'bg-[#F4F5F9] text-[#464555] hover:bg-[#EEF2FF] hover:text-[#5B50E5]' }}">
                <span>{{ $dayName }}</span>
                @if($isRealToday)
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-extrabold {{ $isActiveTab ? 'bg-white text-[#5B50E5]' : 'bg-[#059669] text-white animate-pulse' }}">
                        HARI INI
                    </span>
                @endif
            </a>
        @endforeach
        
        <div class="ml-auto text-xs text-[#8A94A6] hidden md:flex items-center gap-1.5 pr-3">
            <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
            <span>Waktu kampus: <strong>WIB</strong></span>
        </div>
    </div>

    <!-- 4. QUICK KPI STATS CARDS (4 METRICS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat 1: Total Kelas -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB]/80 flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Total Sesi {{ $days[$selectedDay] ?? 'Hari Ini' }}</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#161A30]">{{ $totalSessions }}</span>
                    <span class="text-xs text-[#8A94A6]">Kelas Terjadwal</span>
                </div>
                <div class="text-xs text-[#059669] font-semibold mt-1">Teknik Informatika</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] text-[#4F46E5] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">calendar_view_day</span>
            </div>
        </div>

        <!-- Stat 2: Total SKS -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB]/80 flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Beban SKS</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#161A30]">{{ $totalSks }}</span>
                    <span class="text-xs text-[#8A94A6]">SKS Bobot</span>
                </div>
                <div class="text-xs text-[#4F46E5] font-semibold mt-1">Kurikulum 2022</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#ececff] text-[#5B50E5] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">menu_book</span>
            </div>
        </div>

        <!-- Stat 3: Sedang Berlangsung / Aktif -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB]/80 flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Sedang Berlangsung</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#059669]">{{ $activeCount }}</span>
                    <span class="text-xs text-[#059669] font-bold">Sesi Aktif</span>
                </div>
                <div class="text-xs text-[#8A94A6] mt-1">{{ $upcomingCount }} Sesi Mendatang</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] text-[#059669] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">timelapse</span>
            </div>
        </div>

        <!-- Stat 4: Selesai -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB]/80 flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Telah Selesai</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#64748B]">{{ $completedCount }}</span>
                    <span class="text-xs text-[#8A94A6]">Sesi Usai</span>
                </div>
                <div class="text-xs text-[#059669] font-semibold mt-1">100% Tercatat</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#F1F3F7] text-[#64748B] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">check_circle</span>
            </div>
        </div>
    </div>

    <!-- 5. REAL-TIME SPOTLIGHT CLASS CARD -->
    @if($spotlightSession)
        @php
            $detail = $spotlightSession->status_detail;
            $isActive = ($detail['badge'] === 'active');
            $isUpcoming = ($detail['badge'] === 'upcoming');
        @endphp
        <div class="relative bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-[#E5E7EB] overflow-hidden">
            <!-- Accent Color Bar -->
            <div class="absolute left-0 top-0 bottom-0 w-2 {{ $isActive ? 'bg-[#059669]' : 'bg-[#5B50E5]' }}"></div>

            <div class="pl-2 flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                <!-- Left info -->
                <div class="flex-1 flex flex-col gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        @if($isActive)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#ECFDF5] text-[#059669] text-xs font-bold border border-[#059669]/20">
                                <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
                                SEDANG BERLANGSUNG
                            </span>
                        @elseif($isUpcoming)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-xs font-bold border border-[#4F46E5]/20">
                                <span class="material-symbols-outlined text-[15px]">schedule</span>
                                SESI BERIKUTNYA HARI INI
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#F1F3F7] text-[#64748B] text-xs font-bold">
                                JADWAL TERPILIH
                            </span>
                        @endif

                        <span class="px-2.5 py-0.5 rounded-full bg-[#F4F5F9] text-[#161A30] text-xs font-semibold">
                            Shift {{ ucfirst($spotlightSession->shift) }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#F4F5F9] text-[#161A30] text-xs font-semibold">
                            Semester {{ $spotlightSession->semester }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-[#F4F5F9] text-[#161A30] text-xs font-semibold">
                            {{ $spotlightSession->kelas }}
                        </span>

                        <span class="ml-auto text-xs font-bold {{ $isActive ? 'text-[#059669]' : 'text-[#5B50E5]' }}">
                            {{ $detail['message'] }}
                        </span>
                    </div>

                    <div>
                        <div class="text-xs text-[#8A94A6] font-mono mb-0.5">
                            KODE MK: <strong class="text-[#161A30]">{{ $spotlightSession->course?->kode }}</strong> · {{ $spotlightSession->course?->sks }} SKS
                        </div>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-[#161A30] tracking-tight">
                            {{ $spotlightSession->course?->nama }}
                        </h3>
                        @if($spotlightSession->catatan)
                            <p class="text-xs text-[#464555] mt-1 bg-[#F9FAFC] p-2 rounded-xl border border-[#E5E7EB]">
                                <strong class="text-[#161A30]">Materi / Catatan:</strong> {{ $spotlightSession->catatan }}
                            </p>
                        @endif
                    </div>

                    <!-- Progress Bar for Active Class -->
                    @if($isActive)
                    <div class="flex flex-col gap-1.5 bg-[#F9FAFC] p-3 rounded-xl border border-[#E5E7EB]">
                        <div class="flex justify-between text-xs text-[#8A94A6]">
                            <span class="font-bold text-[#161A30]">Mulai: {{ substr($spotlightSession->jam_mulai, 0, 5) }} WIB</span>
                            <span class="text-[#059669] font-bold">Progres Berjalan: {{ $detail['progress'] }}%</span>
                            <span class="font-bold text-[#161A30]">Selesai: {{ substr($spotlightSession->jam_selesai, 0, 5) }} WIB</span>
                        </div>
                        <div class="w-full bg-[#E5E7EB] h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-[#059669] to-[#10B981] h-full rounded-full transition-all duration-500" style="width: {{ $detail['progress'] }}%;"></div>
                        </div>
                    </div>
                    @endif

                    <!-- Room & Lecturer Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-[#F9FAFC] border border-[#E5E7EB]">
                            <div class="w-9 h-9 rounded-xl bg-[#EEF2FF] text-[#4F46E5] flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] uppercase font-bold text-[#8A94A6]">Ruang Kuliah</div>
                                <div class="text-xs font-bold text-[#161A30] truncate">{{ $spotlightSession->room?->nama }} ({{ $spotlightSession->room?->kode }})</div>
                                <div class="text-[11px] text-[#8A94A6] truncate">{{ $spotlightSession->room?->gedung }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-[#F9FAFC] border border-[#E5E7EB]">
                            <div class="w-9 h-9 rounded-xl bg-[#ECFDF5] text-[#059669] flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">person</span>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] uppercase font-bold text-[#8A94A6]">Dosen Pengampu</div>
                                <div class="text-xs font-bold text-[#161A30] truncate">
                                    {{ $spotlightSession->lecturers->pluck('nama')->join(', ') ?: '-' }}
                                </div>
                                <div class="text-[11px] text-[#8A94A6]">Teknik Informatika Universitas Surakarta</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Time Card -->
                <div class="lg:w-64 bg-[#F9FAFC] rounded-2xl p-4 border border-[#E5E7EB] flex flex-col justify-between gap-4 flex-shrink-0">
                    <div>
                        <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider block mb-1">Waktu Pelaksanaan</span>
                        <div class="text-xl font-extrabold text-[#161A30] font-mono">
                            {{ substr($spotlightSession->jam_mulai, 0, 5) }} - {{ substr($spotlightSession->jam_selesai, 0, 5) }} <span class="text-xs font-bold text-[#5B50E5]">WIB</span>
                        </div>
                        <div class="text-xs text-[#8A94A6] mt-1">
                            Shift: <strong class="text-[#161A30] font-semibold">{{ ucfirst($spotlightSession->shift) }}</strong>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#E5E7EB] space-y-2">
                        <a href="{{ route('jadwal.semua') }}" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-[#5B50E5] hover:bg-[#4F46E5] text-white text-xs font-bold transition-all shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                            <span>Lihat Jadwal Lengkap</span>
                        </a>
                        <a href="{{ route('ruang.index') }}" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white hover:bg-[#F4F5F9] text-[#161A30] text-xs font-semibold border border-[#E5E7EB] transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-[#5B50E5]">door_open</span>
                            <span>Cek Ruangan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 6. FILTER & SEARCH TOOLBAR -->
    <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <form method="GET" action="{{ route('jadwal.hari-ini') }}" class="flex-1 relative flex items-center">
            <select name="academic_year_id" onchange="this.form.submit()" class="mr-2 bg-[#F4F5F9] text-[#161A30] px-3 py-2 rounded-xl text-xs outline-none">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $activeYear?->id === $year->id ? 'selected' : '' }}>{{ $year->tahun }} · {{ ucfirst($year->semester) }}</option>
                @endforeach
            </select>
            <input type="hidden" name="hari" value="{{ $selectedDay }}">
            <input type="hidden" name="shift" value="{{ $selectedShift }}">
            <span class="material-symbols-outlined absolute left-3 text-[#8A94A6] text-[18px]">search</span>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari mata kuliah, dosen, atau ruang..." 
                   class="w-full bg-[#F4F5F9] text-[#161A30] placeholder:text-[#8A94A6] pl-9 pr-4 py-2 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20 border border-transparent focus:border-[#5B50E5]/30 transition-all">
        </form>

        <!-- Shift Filters -->
        <div class="flex items-center gap-1.5 p-1 bg-[#F4F5F9] rounded-xl self-start sm:self-auto overflow-x-auto">
            <a href="{{ route('jadwal.hari-ini', ['hari' => $selectedDay, 'shift' => 'all', 'q' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedShift === 'all' ? 'bg-white text-[#5B50E5] shadow-xs' : 'text-[#464555] hover:text-[#161A30]' }}">
                Semua Shift
            </a>
            <a href="{{ route('jadwal.hari-ini', ['hari' => $selectedDay, 'shift' => 'pagi', 'q' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedShift === 'pagi' ? 'bg-white text-[#5B50E5] shadow-xs' : 'text-[#464555] hover:text-[#161A30]' }}">
                Shift Pagi
            </a>
            <a href="{{ route('jadwal.hari-ini', ['hari' => $selectedDay, 'shift' => 'sore', 'q' => $search]) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedShift === 'sore' ? 'bg-white text-[#5B50E5] shadow-xs' : 'text-[#464555] hover:text-[#161A30]' }}">
                Shift Sore
            </a>
        </div>
    </div>

    <!-- 7. SCHEDULE CARDS LIST -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-sm font-extrabold text-[#161A30]">
                Daftar Perkuliahan Hari {{ $days[$selectedDay] ?? 'Ini' }} ({{ $schedules->count() }} Kelas)
            </h3>
            <span class="text-xs text-[#8A94A6]">Zona Waktu Indonesia Barat (WIB)</span>
        </div>

        @forelse($schedules as $item)
            @php
                $itemDetail = $item->status_detail;
                $isItemActive = ($itemDetail['badge'] === 'active');
                $isItemUpcoming = ($itemDetail['badge'] === 'upcoming');
                $isItemDone = ($itemDetail['badge'] === 'done');
            @endphp
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-[#E5E7EB] hover:border-[#5B50E5]/40 hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $isItemActive ? 'ring-2 ring-[#059669]/30 bg-[#ECFDF5]/20' : '' }}">
                
                <!-- Left Details -->
                <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                    <!-- Time Pill -->
                    <div class="flex flex-col items-start sm:items-center justify-center p-2.5 rounded-xl {{ $isItemActive ? 'bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0]' : 'bg-[#F4F5F9] text-[#161A30]' }} min-w-[130px] flex-shrink-0">
                        <div class="text-xs font-mono font-extrabold">
                            {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                        </div>
                        <div class="text-[10px] font-bold uppercase tracking-wider {{ $isItemActive ? 'text-[#059669]' : 'text-[#8A94A6]' }}">
                            WIB · Shift {{ ucfirst($item->shift) }}
                        </div>
                    </div>

                    <!-- Course & Lecturer Details -->
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-1.5 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EEF2FF] text-[#4F46E5]">
                                {{ $item->course?->kode }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#F4F5F9] text-[#464555]">
                                {{ $item->course?->sks }} SKS
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#F4F5F9] text-[#464555]">
                                {{ $item->kelas }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#F4F5F9] text-[#464555]">
                                Smt {{ $item->semester }}
                            </span>

                            @if($isItemActive)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#ECFDF5] text-[#059669] border border-[#A7F3D0] flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#059669] animate-pulse"></span>
                                    Sedang Berlangsung
                                </span>
                            @elseif($isItemUpcoming)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EEF2FF] text-[#4F46E5]">
                                    {{ $itemDetail['message'] }}
                                </span>
                            @elseif($isItemDone)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F3F7] text-[#64748B]">
                                    Selesai
                                </span>
                            @endif
                        </div>

                        <h4 class="text-sm sm:text-base font-extrabold text-[#161A30] truncate">
                            {{ $item->course?->nama }}
                        </h4>

                        <div class="flex flex-wrap items-center gap-3 text-xs text-[#8A94A6] mt-1">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px] text-[#4F46E5]">person</span>
                                <span class="text-[#464555] font-medium truncate max-w-[220px]">
                                    {{ $item->lecturers->pluck('nama')->join(', ') ?: 'Dosen Pengampu TI' }}
                                </span>
                            </span>
                            <span class="text-[#CBD5E1]">·</span>
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px] text-[#059669]">meeting_room</span>
                                <span class="font-bold text-[#161A30]">{{ $item->room?->nama }} ({{ $item->room?->kode }})</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 self-end md:self-center flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-[#F4F5F9]">
                    <a href="{{ route('mata-kuliah.index') }}" class="px-3 py-1.5 rounded-xl bg-[#F4F5F9] hover:bg-[#EEF2FF] text-[#161A30] text-xs font-semibold transition-colors">
                        Silabus MK
                    </a>
                    <a href="{{ route('jadwal.semua') }}" class="px-3 py-1.5 rounded-xl bg-[#EEF2FF] hover:bg-[#5B50E5] text-[#5B50E5] hover:text-white text-xs font-bold transition-all">
                        Plotting
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-10 text-center border border-[#E5E7EB] shadow-xs flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-2xl bg-[#F4F5F9] text-[#8A94A6] flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-[32px]">event_busy</span>
                </div>
                <h4 class="text-base font-bold text-[#161A30]">Tidak Ada Jadwal Perkuliahan</h4>
                <p class="text-xs text-[#8A94A6] max-w-sm mt-1">
                    Tidak ditemukan agenda perkuliahan untuk filter hari dan shift yang Anda pilih. Silakan ganti hari atau reset filter.
                </p>
                <a href="{{ route('jadwal.hari-ini', ['hari' => $selectedDay, 'shift' => 'all']) }}" class="mt-4 px-4 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold">
                    Reset Filter
                </a>
            </div>
        @endforelse
    </div>
</div>

<script>
    // Live update digital clock in header
    function updateClock() {
        const now = new Date();
        const options = { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
        const clockEl = document.getElementById('liveDigitalClock');
        if (clockEl) {
            clockEl.textContent = timeString + ' WIB';
        }
    }
    setInterval(updateClock, 1000);
</script>
@endsection
