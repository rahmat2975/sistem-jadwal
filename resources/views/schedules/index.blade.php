@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full">
    
    <!-- 1. PAGE TOP BAR & ACTIONS -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex flex-wrap items-center gap-2.5 mb-1.5">
                <h1 class="text-xl sm:text-2xl font-bold text-[#161A30] tracking-tight">Semua Jadwal</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#ECFDF5] text-[#059669] text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#059669] animate-pulse"></span>
                    {{ ucfirst($activeYear->semester ?? 'ganjil') }} · {{ $activeYear->tahun ?? '2026/2027' }}
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-xs font-semibold">
                    <span class="material-symbols-outlined text-[15px]">schedule</span>
                    WIB (UTC+7)
                </span>
            </div>
            <p class="text-xs sm:text-sm text-[#8A94A6]">
                Departemen Teknik Informatika · Fakultas Teknik
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#464555] hover:text-[#161A30] hover:bg-[#F9FAFC] text-xs font-bold border border-[#E5E7EB] shadow-sm transition-all" type="button">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span>Cetak Jadwal Mingguan</span>
            </button>

            <div class="relative inline-block text-left" id="exportDropdownWrapper">
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold shadow-md shadow-[#5B50E5]/25 hover:bg-[#4F46E5] transition-all" id="btnExportToggle" type="button">
                    <span class="material-symbols-outlined text-[18px]">file_download</span>
                    <span>Ekspor Jadwal</span>
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <div class="hidden absolute right-0 mt-2 w-52 rounded-2xl bg-white shadow-xl border border-[#E5E7EB] p-2 z-30 flex flex-col gap-1" id="exportMenu">
                    <button onclick="window.print()" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-[#161A30] hover:bg-[#F4F2FF] text-xs font-medium transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-[#EF4444]">picture_as_pdf</span>
                        <span>Dokumen PDF (.pdf)</span>
                    </button>
                    <button onclick="exportSchedulesCSV()" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-[#161A30] hover:bg-[#F4F2FF] text-xs font-medium transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-[#059669]">table_view</span>
                        <span>Lembar Kerja Excel (.csv)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. METRIC / OVERVIEW KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Jadwal -->
        <div class="p-5 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Total Jadwal Aktif</span>
                <span class="text-2xl font-extrabold text-[#161A30] mt-1 leading-none">{{ $totalSchedules }}</span>
                <span class="text-xs font-semibold text-[#059669] mt-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">check_circle</span>
                    100% Terverifikasi Jurusan
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#EEF2FF] text-[#4F46E5] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">calendar_month</span>
            </div>
        </div>

        <!-- Card 2: Ruangan -->
        <div class="p-5 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Ruang Terpakai</span>
                <span class="text-2xl font-extrabold text-[#161A30] mt-1 leading-none">{{ $totalRooms }} <span class="text-xs font-medium text-[#8A94A6]">Ruangan</span></span>
                <span class="text-xs text-[#8A94A6] mt-1.5">Lab & ruang teori</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F4F2FF] text-[#6366F1] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">meeting_room</span>
            </div>
        </div>

        <!-- Card 3: Beban Dosen -->
        <div class="p-5 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Beban Dosen Pengampu</span>
                <span class="text-2xl font-extrabold text-[#161A30] mt-1 leading-none">{{ $totalLecturers }} <span class="text-xs font-medium text-[#8A94A6]">Dosen</span></span>
                <span class="text-xs text-[#8A94A6] mt-1.5">Dosen Terplot {{ ucfirst($activeYear->semester ?? 'ganjil') }} {{ $activeYear->tahun ?? '2026/2027' }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">supervisor_account</span>
            </div>
        </div>

        <!-- Card 4: Shift Pagi vs Sore -->
        <div class="p-5 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Distribusi Shift Kelas</span>
                <span class="text-2xl font-extrabold text-[#161A30] mt-1 leading-none">
                    {{ $totalPagi }} <span class="text-xs font-medium text-[#8A94A6]">Pagi</span> / {{ $totalSore }} <span class="text-xs font-medium text-[#8A94A6]">Sore</span>
                </span>
                <span class="text-xs font-semibold text-[#059669] mt-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">verified</span>
                    Algoritma Bebas Tabrakan
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#ECFDF5] text-[#059669] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">event_available</span>
            </div>
        </div>
    </div>

    <!-- 3. INTERACTIVE CONTROL & FILTER PANEL -->
    <div class="p-5 rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 mb-6 flex flex-col gap-4">
        <!-- Row 1: Semester Tabs & View Toggle -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <form method="GET" action="{{ route('jadwal.semua') }}" class="flex items-center gap-2">
                <label for="academic_year_id" class="text-xs font-bold text-[#8A94A6]">Periode:</label>
                <select id="academic_year_id" name="academic_year_id" onchange="this.form.submit()" class="bg-white border border-[#E5E7EB] text-[#161A30] px-3 py-2 rounded-xl text-xs outline-none">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $activeYear?->id === $year->id ? 'selected' : '' }}>{{ $year->tahun }} · {{ ucfirst($year->semester) }}</option>
                    @endforeach
                </select>
            </form>
            <div class="flex flex-wrap items-center gap-1.5 p-1 rounded-2xl bg-[#F4F5F9]">
                <button class="semester-filter-btn active-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#5B50E5] text-white shadow-sm transition-all" data-semester="all" type="button">Semua Semester</button>
                <button class="semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all" data-semester="1" type="button">Semester 1</button>
                <button class="semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all" data-semester="3" type="button">Semester 3</button>
                <button class="semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all" data-semester="5" type="button">Semester 5</button>
                <button class="semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all" data-semester="7" type="button">Semester 7</button>
                <button class="semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all" data-semester="pilihan" type="button">MK Pilihan</button>
            </div>

            <!-- View Switcher -->
            <div class="flex items-center gap-1 p-1 rounded-2xl bg-[#F4F5F9] self-start lg:self-auto">
                <button class="px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 bg-white text-[#5B50E5] shadow-sm transition-all" id="viewTableBtn" type="button">
                    <span class="material-symbols-outlined text-[18px]">table_rows</span>
                    <span>Tabel Jadwal</span>
                </button>
                <button class="px-3.5 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 text-[#464555] hover:text-[#161A30] transition-all" id="viewGridBtn" type="button">
                    <span class="material-symbols-outlined text-[18px]">grid_view</span>
                    <span>Kartu Jadwal</span>
                </button>
            </div>
        </div>

        <!-- Row 2: Search, Room Dropdown, Reset -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 pt-1">
            <!-- Search Input -->
            <div class="md:col-span-5 relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-[#8A94A6] text-[18px] pointer-events-none">search</span>
                <input class="w-full bg-[#F4F5F9] text-[#161A30] placeholder:text-[#8A94A6] pl-10 pr-4 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20 transition-all" id="searchInput" placeholder="Cari nama mata kuliah, kode MK, dosen, atau kelas..." type="text"/>
            </div>

            <!-- Room Dropdown -->
            <div class="md:col-span-4 relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-[#8A94A6] text-[18px] pointer-events-none">meeting_room</span>
                <select class="w-full bg-[#F4F5F9] text-[#161A30] pl-10 pr-8 py-2.5 rounded-xl text-xs outline-none appearance-none cursor-pointer focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20 transition-all" id="roomFilterSelect">
                    <option value="all">Semua Ruangan Kuliah & Lab</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->nama }}">{{ $r->nama }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 text-[#8A94A6] text-[18px] pointer-events-none">expand_more</span>
            </div>

            <!-- Filter Status & Reset -->
            <div class="md:col-span-3 flex items-center justify-between md:justify-end gap-2">
                <span class="text-xs font-semibold text-[#8A94A6]" id="resultCount">Menampilkan {{ $totalSchedules }} Jadwal</span>
                <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#F4F5F9] hover:bg-[#EEF2FF] text-[#464555] hover:text-[#5B50E5] text-xs font-bold transition-colors" id="resetFilterBtn" type="button">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    <span>Reset</span>
                </button>
            </div>
        </div>

        <!-- Row 3: SHIFT FILTER & DAY PILLS -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-[#F1F3F7]">
            <!-- Shift Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider mr-1">Shift Kelas:</span>
                <button class="shift-filter-btn active-shift px-3 py-1 rounded-full text-xs font-bold bg-[#5B50E5] text-white shadow-sm transition-all" data-shift="all" type="button">
                    Semua ({{ $totalSchedules }})
                </button>
                <button class="shift-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] flex items-center gap-1 transition-all" data-shift="pagi" type="button">
                    <span class="material-symbols-outlined text-[14px] text-[#F59E0B]">light_mode</span>
                    <span>Pagi ({{ $totalPagi }})</span>
                </button>
                <button class="shift-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] flex items-center gap-1 transition-all" data-shift="sore" type="button">
                    <span class="material-symbols-outlined text-[14px] text-[#818CF8]">dark_mode</span>
                    <span>Sore ({{ $totalSore }})</span>
                </button>
            </div>

            <!-- Day Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider mr-1">Hari:</span>
                <button class="day-filter-btn active-day px-3 py-1 rounded-full text-xs font-bold bg-[#5B50E5] text-white transition-colors" data-day="all" type="button">Semua</button>
                <button class="day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors" data-day="Senin" type="button">Senin</button>
                <button class="day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors" data-day="Selasa" type="button">Selasa</button>
                <button class="day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors" data-day="Rabu" type="button">Rabu</button>
                <button class="day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors" data-day="Kamis" type="button">Kamis</button>
                <button class="day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors" data-day="Jumat" type="button">Jumat</button>
            </div>
        </div>
    </div>

    <!-- 4. MAIN VIEW: TABLE VIEW (PAGINATED 10 ROWS PER PAGE) -->
    <div id="tableViewContainer" class="flex flex-col gap-4">
        <div class="rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="mainScheduleTable">
                    <thead>
                        <tr class="bg-[#F9FAFC] text-[#8A94A6] text-xs font-bold uppercase tracking-wider border-b border-[#E5E7EB]">
                            <th class="py-3.5 px-4">HARI & WAKTU</th>
                            <th class="py-3.5 px-4">MATA KULIAH & KODE</th>
                            <th class="py-3.5 px-4">KELAS & SMT</th>
                            <th class="py-3.5 px-4">RUANGAN</th>
                            <th class="py-3.5 px-4">DOSEN PENGAMPU</th>
                            <th class="py-3.5 px-4">STATUS</th>
                            <th class="py-3.5 px-4 text-center">RINCIAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB] text-xs" id="tableScheduleTbody">
                        @forelse($schedules as $sched)
                            @php
                                $course = $sched->course;
                                $room = $sched->room;
                                $coordinator = $sched->coordinator();
                                $otherLecturers = $sched->lecturers->where('id', '!=', $coordinator?->id);
                                $detail = $sched->status_detail;
                                $isPilihan = $course?->tipe === 'pilihan';
                                $semesterTag = $isPilihan ? 'pilihan' : $sched->semester;
                                $dayName = $days[$sched->hari] ?? 'Senin';
                                $searchKeywords = strtolower("{$course?->nama} {$course?->kode} {$sched->kelas} {$coordinator?->nama} {$room?->nama} {$sched->shift} {$dayName}");
                                $lecturersList = $sched->lecturers->pluck('nama')->implode(', ');
                            @endphp
                            <tr class="schedule-row hover:bg-[#FBFBFC] transition-colors {{ $detail['badge'] === 'active' ? 'bg-[#EEF2FF]/30' : '' }}" 
                                data-day="{{ $dayName }}" 
                                data-shift="{{ $sched->shift }}"
                                data-semester="{{ $semesterTag }}" 
                                data-room="{{ $room?->nama }}" 
                                data-keyword="{{ $searchKeywords }}"
                                data-course="{{ $course?->nama }}"
                                data-code="{{ $course?->kode }}"
                                data-sks="{{ $course?->sks }}"
                                data-kelas="{{ $sched->kelas }}"
                                data-semester-num="{{ $sched->semester }}"
                                data-time="{{ substr($sched->jam_mulai, 0, 5) }} - {{ substr($sched->jam_selesai, 0, 5) }}"
                                data-lecturer="{{ $coordinator?->nama }}"
                                data-lecturers="{{ $lecturersList }}"
                                data-lecturer-foto="{{ $coordinator?->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}"
                                data-room-name="{{ $room?->nama ?? 'Ruang Belum Ditentukan' }}"
                                data-room-building="{{ $room?->gedung ?? 'Gedung TI' }}">
                                
                                <!-- Hari, Waktu & Shift -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="px-2 py-0.5 rounded-lg bg-[#5B50E5] text-white text-xs font-bold">{{ $dayName }}</span>
                                            <span class="text-xs font-extrabold {{ $detail['badge'] === 'active' ? 'text-[#4F46E5]' : 'text-[#161A30]' }}">
                                                {{ substr($sched->jam_mulai, 0, 5) }} - {{ substr($sched->jam_selesai, 0, 5) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs text-[#8A94A6]">WIB</span>
                                            <!-- Shift Badge -->
                                            @if($sched->shift === 'sore')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#FEF3C7] text-[#D97706]">
                                                    <span class="material-symbols-outlined text-[13px]">dark_mode</span>
                                                    <span>Shift Sore</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-[#E0F2FE] text-[#0284C7]">
                                                    <span class="material-symbols-outlined text-[13px]">light_mode</span>
                                                    <span>Shift Pagi</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Mata Kuliah & Kode -->
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="text-xs font-bold text-[#161A30]">{{ $course?->nama }}</span>
                                            <span class="px-2 py-0.5 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-xs font-bold">{{ $course?->sks }} SKS</span>
                                            <span class="px-2 py-0.5 rounded-full {{ $isPilihan ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#F1F3F7] text-[#64748B]' }} text-xs font-bold">
                                                {{ ucfirst($course?->tipe) }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-[#8A94A6] mt-1">
                                            Kode: <span class="font-semibold text-[#161A30]">{{ $course?->kode }}</span> · {{ $course?->kategori ?? 'Teknik Informatika' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Kelas & Smt -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-[#161A30]">{{ $sched->kelas }}</span>
                                        <span class="text-xs text-[#8A94A6]">
                                            Semester {{ $sched->semester }} · {{ $sched->jumlah_mahasiswa ?? 40 }} Mahasiswa
                                        </span>
                                    </div>
                                </td>

                                <!-- Ruangan -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-[#161A30] flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px] text-[#5B50E5]">
                                                {{ $room?->jenis === 'laboratorium' ? 'desktop_windows' : 'co_present' }}
                                            </span>
                                            {{ $room?->nama ?? 'Ruang Belum Ditentukan' }}
                                        </span>
                                        <span class="text-xs text-[#8A94A6]">{{ $room?->gedung ?? 'Gedung TI' }}</span>
                                    </div>
                                </td>

                                <!-- Dosen Pengampu -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $coordinator?->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}" 
                                             alt="Avatar" 
                                             class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-[#5B50E5]/20">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-[#161A30] leading-tight">
                                                {{ $coordinator?->nama }} <span class="text-[#5B50E5] font-semibold">(Koor)</span>
                                            </span>
                                            @if($otherLecturers->count() > 0)
                                                <span class="text-xs text-[#8A94A6] truncate max-w-[200px]" title="{{ $otherLecturers->pluck('nama')->implode(', ') }}">
                                                    +{{ $otherLecturers->count() }} dosen: {{ $otherLecturers->pluck('nama')->first() }}
                                                </span>
                                            @else
                                                <span class="text-xs text-[#8A94A6]">Dosen Tunggal Pengampu</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($detail['badge'] === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-xs font-bold">
                                            <span class="w-2 h-2 rounded-full bg-[#4F46E5] animate-pulse"></span>
                                            Sedang Berlangsung
                                        </span>
                                    @elseif($detail['badge'] === 'done')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#F1F3F7] text-[#64748B] text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">done_all</span>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#ECFDF5] text-[#059669] text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                                            Belum Mulai
                                        </span>
                                    @endif
                                </td>

                                <!-- Rincian Modal Button -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <button onclick="showScheduleModalFromRow(this.closest('tr'))" 
                                            class="inline-flex items-center justify-center p-2 rounded-xl bg-[#F4F2FF] text-[#5B50E5] hover:bg-[#5B50E5] hover:text-white transition-all shadow-xs" 
                                            title="Lihat Rincian Jadwal" type="button">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-[#8A94A6]">
                                    Tidak ada jadwal perkuliahan terdata.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Empty Search State -->
            <div id="tableScheduleEmptyState" class="hidden py-12 text-center text-xs text-[#8A94A6] flex flex-col items-center justify-center gap-2">
                <span class="material-symbols-outlined text-4xl text-[#C4C0FF]">search_off</span>
                <p class="font-bold text-[#161A30]">Tidak ada jadwal yang cocok dengan filter atau kata kunci.</p>
                <p>Silakan coba kata kunci lain atau klik tombol Reset Filter.</p>
            </div>

            <!-- 10 ITEM PAGINATION CONTROLS -->
            <div class="px-5 py-4 bg-[#F9FAFC] border-t border-[#E5E7EB] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs" id="tablePaginationFooter">
                <span class="text-[#8A94A6] font-medium" id="tablePaginationInfo">
                    Menampilkan 1-10 dari {{ $totalSchedules }} jadwal
                </span>
                <div class="flex items-center gap-1.5" id="tablePaginationControls">
                    <!-- Populated dynamically by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- 5. SECONDARY VIEW: GRID CARDS VIEW (PAGINATED 10 CARDS PER PAGE) -->
    <div id="gridViewContainer" class="hidden flex flex-col gap-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="gridCardsContainer">
            @foreach($schedules as $sched)
                @php
                    $course = $sched->course;
                    $room = $sched->room;
                    $coordinator = $sched->coordinator();
                    $detail = $sched->status_detail;
                    $isPagi = $sched->shift === 'pagi';
                    $dayName = $days[$sched->hari] ?? 'Senin';
                    $searchKeywords = strtolower("{$course?->nama} {$course?->kode} {$sched->kelas} {$coordinator?->nama} {$room?->nama} {$sched->shift} {$dayName}");
                    $lecturersList = $sched->lecturers->pluck('nama')->implode(', ');
                @endphp
                <div class="schedule-card-item p-4 rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 {{ $detail['badge'] === 'active' ? 'border-l-4 border-l-[#5B50E5] shadow-md' : '' }} flex flex-col justify-between gap-3 hover:shadow-md transition-all"
                     data-day="{{ $dayName }}" 
                     data-shift="{{ $sched->shift }}"
                     data-semester="{{ $course?->tipe === 'pilihan' ? 'pilihan' : $sched->semester }}" 
                     data-room="{{ $room?->nama }}" 
                     data-keyword="{{ $searchKeywords }}"
                     data-course="{{ $course?->nama }}"
                     data-code="{{ $course?->kode }}"
                     data-sks="{{ $course?->sks }}"
                     data-kelas="{{ $sched->kelas }}"
                     data-semester-num="{{ $sched->semester }}"
                     data-time="{{ substr($sched->jam_mulai, 0, 5) }} - {{ substr($sched->jam_selesai, 0, 5) }}"
                     data-lecturer="{{ $coordinator?->nama }}"
                     data-lecturers="{{ $lecturersList }}"
                     data-lecturer-foto="{{ $coordinator?->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}"
                     data-room-name="{{ $room?->nama ?? 'Ruang Belum Ditentukan' }}"
                     data-room-building="{{ $room?->gedung ?? 'Gedung TI' }}">
                    
                    <div class="flex flex-col gap-2">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#5B50E5] text-white text-xs font-bold">{{ $dayName }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $isPagi ? 'bg-[#E0F2FE] text-[#0284C7]' : 'bg-[#FEF3C7] text-[#D97706]' }} flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">{{ $isPagi ? 'light_mode' : 'dark_mode' }}</span>
                                {{ substr($sched->jam_mulai, 0, 5) }} - {{ substr($sched->jam_selesai, 0, 5) }}
                            </span>
                        </div>

                        <!-- Course Title -->
                        <div>
                            <h3 class="text-xs font-bold text-[#161A30] leading-snug line-clamp-1">{{ $course?->nama }}</h3>
                            <p class="text-xs text-[#8A94A6] mt-0.5">{{ $sched->kelas }} · Smt {{ $sched->semester }} · {{ $course?->sks }} SKS</p>
                        </div>

                        <!-- Room & Lecturer -->
                        <div class="pt-2 flex flex-col gap-1 border-t border-[#F1F3F7]">
                            <span class="text-xs font-semibold text-[#161A30] flex items-center gap-1 truncate">
                                <span class="material-symbols-outlined text-[15px] text-[#5B50E5]">meeting_room</span>
                                {{ $room?->nama }}
                            </span>
                            <span class="text-xs text-[#8A94A6] truncate flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px] text-[#8A94A6]">person</span>
                                {{ $coordinator?->nama }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Action -->
                    <button onclick="showScheduleModalFromCard(this.closest('.schedule-card-item'))" 
                            class="w-full py-2 rounded-xl bg-[#F4F2FF] hover:bg-[#5B50E5] text-[#5B50E5] hover:text-white font-bold text-xs flex items-center justify-center gap-1.5 transition-all" type="button">
                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                        <span>Lihat Rincian</span>
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Grid Empty State -->
        <div id="gridScheduleEmptyState" class="hidden py-12 text-center text-xs text-[#8A94A6] flex flex-col items-center justify-center gap-2 bg-white rounded-3xl border border-[#E5E7EB]">
            <span class="material-symbols-outlined text-4xl text-[#C4C0FF]">search_off</span>
            <p class="font-bold text-[#161A30]">Tidak ada kartu jadwal yang cocok dengan filter atau kata kunci.</p>
        </div>

        <!-- Grid Pagination Footer -->
        <div class="p-5 rounded-3xl bg-white border border-[#E5E7EB]/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs" id="gridPaginationFooter">
            <span class="text-[#8A94A6] font-medium" id="gridPaginationInfo">
                Menampilkan 1-10 dari {{ $totalSchedules }} jadwal
            </span>
            <div class="flex items-center gap-1.5" id="gridPaginationControls">
                <!-- Populated dynamically by JS -->
            </div>
        </div>
    </div>

    <!-- 6. BOTTOM ACADEMIC NOTICE -->
    <div class="mt-8 p-4 rounded-2xl bg-white border border-[#E5E7EB] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-[#8A94A6] text-xs">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-[#5B50E5]">verified_user</span>
            <span>Jadwal perkuliahan disahkan oleh Ketua Jurusan Teknik Informatika FT Universitas Surakarta Periode {{ $activeYear->tahun ?? '2026/2027' }}.</span>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="text-[#5B50E5] hover:underline font-bold text-xs cursor-pointer" type="button">Unduh SK Jadwal Perkuliahan (.pdf)</button>
            <span class="w-1 h-1 rounded-full bg-[#8A94A6]"></span>
            <span class="font-semibold">Versi Cetak: 2.4.1</span>
        </div>
    </div>

</div>

<!-- 7. INTERACTIVE SCHEDULE DETAIL MODAL -->
<div id="scheduleDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-[#E5E7EB] overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="px-6 py-5 bg-[#F9FAFC] border-b border-[#E5E7EB] flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#EEF2FF] text-[#5B50E5] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#161A30]">Rincian Jadwal Perkuliahan</h3>
                    <p class="text-xs text-[#8A94A6]">FT Universitas Surakarta</p>
                </div>
            </div>
            <button onclick="closeScheduleModal()" class="w-8 h-8 rounded-full bg-white border border-[#E5E7EB] flex items-center justify-center text-[#8A94A6] hover:text-[#161A30] hover:bg-[#F4F5F9] transition-all" type="button">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <!-- Course Header -->
            <div class="p-4 rounded-2xl bg-[#F4F2FF]/60 border border-[#EEF2FF] flex flex-col gap-1.5">
                <div class="flex items-center justify-between">
                    <span id="modalCourseCode" class="text-xs font-bold text-[#5B50E5]">-</span>
                    <span id="modalCourseSks" class="px-2.5 py-0.5 rounded-full bg-[#5B50E5] text-white text-xs font-bold">- SKS</span>
                </div>
                <h4 id="modalCourseName" class="text-base font-extrabold text-[#161A30]">Mata Kuliah</h4>
                <div class="flex items-center gap-2 text-xs text-[#8A94A6]">
                    <span id="modalCourseClass">-</span>
                    <span class="text-[#CBD5E1]">·</span>
                    <span id="modalCourseSemester">-</span>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-2xl bg-[#F9FAFC] border border-[#E5E7EB]/80 flex flex-col gap-1">
                    <span class="text-[#8A94A6] font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-[#5B50E5]">schedule</span>
                        Hari & Waktu
                    </span>
                    <span id="modalScheduleTime" class="font-bold text-[#161A30]">-</span>
                </div>

                <div class="p-3 rounded-2xl bg-[#F9FAFC] border border-[#E5E7EB]/80 flex flex-col gap-1">
                    <span class="text-[#8A94A6] font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-[#5B50E5]">meeting_room</span>
                        Ruang Kuliah
                    </span>
                    <span id="modalRoomName" class="font-bold text-[#161A30]">-</span>
                    <span id="modalRoomBuilding" class="text-xs text-[#8A94A6]">-</span>
                </div>
            </div>

            <!-- Lecturer Card -->
            <div class="p-3.5 rounded-2xl bg-[#F9FAFC] border border-[#E5E7EB]/80 flex items-center gap-3">
                <img id="modalLecturerFoto" src="" alt="Dosen" class="w-11 h-11 rounded-full object-cover shrink-0 ring-2 ring-[#5B50E5]/20">
                <div class="flex flex-col min-w-0">
                    <span class="text-xs text-[#8A94A6] font-medium">Koordinator & Tim Pengampu:</span>
                    <span id="modalLecturerName" class="text-xs font-bold text-[#161A30] truncate">-</span>
                    <span id="modalLecturersList" class="text-xs text-[#8A94A6] truncate">-</span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-[#F9FAFC] border-t border-[#E5E7EB] flex items-center justify-between">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-[#E5E7EB] bg-white text-xs font-semibold text-[#161A30] hover:bg-[#F4F5F9] transition-all" type="button">
                <span class="material-symbols-outlined text-[16px]">print</span>
                <span>Cetak Rincian</span>
            </button>
            <button onclick="closeScheduleModal()" class="px-4 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold hover:bg-[#4F46E5] transition-all" type="button">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// CSV EXPORT FUNCTION FOR SCHEDULES
function exportSchedulesCSV() {
    const table = document.getElementById('mainScheduleTable');
    if (!table) return;

    const rows = document.querySelectorAll('#tableScheduleTbody tr.schedule-row');
    const csvContent = [];
    
    // Header
    csvContent.push(['Hari', 'Jam Mulai', 'Jam Selesai', 'Shift', 'Kode MK', 'Nama Mata Kuliah', 'SKS', 'Kelas', 'Semester', 'Ruangan', 'Dosen Pengampu'].join(','));

    rows.forEach(row => {
        const day = row.getAttribute('data-day') || '';
        const time = row.getAttribute('data-time') || '';
        const times = time.split('-');
        const jamMulai = times[0] ? times[0].trim() : '';
        const jamSelesai = times[1] ? times[1].trim() : '';
        const shift = row.getAttribute('data-shift') || '';
        const code = row.getAttribute('data-code') || '';
        const course = '"' + (row.getAttribute('data-course') || '').replace(/"/g, '""') + '"';
        const sks = row.getAttribute('data-sks') || '';
        const kelas = row.getAttribute('data-kelas') || '';
        const semester = row.getAttribute('data-semester-num') || '';
        const room = '"' + (row.getAttribute('data-room-name') || '').replace(/"/g, '""') + '"';
        const lecturer = '"' + (row.getAttribute('data-lecturers') || row.getAttribute('data-lecturer') || '').replace(/"/g, '""') + '"';

        csvContent.push([day, jamMulai, jamSelesai, shift, code, course, sks, kelas, semester, room, lecturer].join(','));
    });

    const blob = new Blob(["\uFEFF" + csvContent.join("\n")], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "jadwal_kuliah_ti_universitas_surakarta.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// MODAL CONTROLS
function showScheduleModalFromRow(row) {
    if (!row) return;
    document.getElementById('modalCourseName').textContent = row.getAttribute('data-course') || '';
    document.getElementById('modalCourseCode').textContent = row.getAttribute('data-code') || '';
    document.getElementById('modalCourseSks').textContent = (row.getAttribute('data-sks') || '3') + ' SKS';
    document.getElementById('modalCourseClass').textContent = 'Kelas ' + (row.getAttribute('data-kelas') || '');
    document.getElementById('modalCourseSemester').textContent = 'Semester ' + (row.getAttribute('data-semester-num') || '');
    document.getElementById('modalScheduleTime').textContent = (row.getAttribute('data-day') || '') + ', ' + (row.getAttribute('data-time') || '') + ' WIB';
    document.getElementById('modalRoomName').textContent = row.getAttribute('data-room-name') || '';
    document.getElementById('modalRoomBuilding').textContent = row.getAttribute('data-room-building') || '';
    document.getElementById('modalLecturerName').textContent = row.getAttribute('data-lecturer') || '';
    document.getElementById('modalLecturersList').textContent = row.getAttribute('data-lecturers') || '';
    document.getElementById('modalLecturerFoto').src = row.getAttribute('data-lecturer-foto') || '';

    document.getElementById('scheduleDetailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function showScheduleModalFromCard(card) {
    if (!card) return;
    showScheduleModalFromRow(card);
}

function closeScheduleModal() {
    document.getElementById('scheduleDetailModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// CLOSE MODAL ON ESCAPE OR BACKDROP CLICK
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeScheduleModal();
});
document.getElementById('scheduleDetailModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeScheduleModal();
});

document.addEventListener('DOMContentLoaded', function () {
    // Dropdown Export Toggle
    const exportBtn = document.getElementById('btnExportToggle');
    const exportMenu = document.getElementById('exportMenu');
    if (exportBtn && exportMenu) {
        exportBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            exportMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', () => {
            if (!exportMenu.classList.contains('hidden')) {
                exportMenu.classList.add('hidden');
            }
        });
    }

    // View switcher: Table vs Grid
    const viewTableBtn = document.getElementById('viewTableBtn');
    const viewGridBtn = document.getElementById('viewGridBtn');
    const tableViewContainer = document.getElementById('tableViewContainer');
    const gridViewContainer = document.getElementById('gridViewContainer');
    let currentView = 'table';

    function setView(view) {
        currentView = view;
        if (view === 'table') {
            tableViewContainer.classList.remove('hidden');
            gridViewContainer.classList.add('hidden');
            viewTableBtn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 bg-white text-[#5B50E5] shadow-sm transition-all';
            viewGridBtn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 text-[#464555] hover:text-[#161A30] transition-all';
        } else {
            tableViewContainer.classList.add('hidden');
            gridViewContainer.classList.remove('hidden');
            viewGridBtn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 bg-white text-[#5B50E5] shadow-sm transition-all';
            viewTableBtn.className = 'px-3.5 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 text-[#464555] hover:text-[#161A30] transition-all';
        }
        updatePagination();
    }

    if (viewTableBtn && viewGridBtn) {
        viewTableBtn.addEventListener('click', () => setView('table'));
        viewGridBtn.addEventListener('click', () => setView('grid'));
    }

    // Filter Logic & 10 Data per Page Pagination
    let currentSemester = 'all';
    let currentShift = 'all';
    let currentDay = 'all';
    let currentRoom = 'all';
    let searchQuery = '';
    
    let tableCurrentPage = 1;
    let gridCurrentPage = 1;
    const ITEMS_PER_PAGE = 10;

    const semesterBtns = document.querySelectorAll('.semester-filter-btn');
    const shiftBtns = document.querySelectorAll('.shift-filter-btn');
    const dayBtns = document.querySelectorAll('.day-filter-btn');
    const roomSelect = document.getElementById('roomFilterSelect');
    const searchInput = document.getElementById('searchInput');
    const resetBtn = document.getElementById('resetFilterBtn');
    const resultCount = document.getElementById('resultCount');

    const tableRows = Array.from(document.querySelectorAll('#tableScheduleTbody tr.schedule-row'));
    const gridCards = Array.from(document.querySelectorAll('#gridCardsContainer .schedule-card-item'));

    function getMatches(element) {
        const rowSemester = element.getAttribute('data-semester');
        const rowShift = element.getAttribute('data-shift');
        const rowDay = element.getAttribute('data-day');
        const rowRoom = element.getAttribute('data-room');
        const rowKeywords = (element.getAttribute('data-keyword') || '').toLowerCase();

        const matchSemester = (currentSemester === 'all') || (rowSemester === currentSemester);
        const matchShift = (currentShift === 'all') || (rowShift === currentShift);
        const matchDay = (currentDay === 'all') || (rowDay === currentDay);
        const matchRoom = (currentRoom === 'all') || (rowRoom && rowRoom.toLowerCase().includes(currentRoom.toLowerCase()));
        const matchSearch = !searchQuery || rowKeywords.includes(searchQuery.toLowerCase());

        return matchSemester && matchShift && matchDay && matchRoom && matchSearch;
    }

    function renderPaginationControls(container, currentPage, totalPages, onPageChange) {
        if (!container) return;
        container.innerHTML = '';
        if (totalPages <= 1) return;

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = `p-1.5 rounded-lg border border-[#E5E7EB] transition-colors ${currentPage === 1 ? 'opacity-40 cursor-not-allowed text-[#8A94A6]' : 'text-[#161A30] hover:bg-[#F4F5F9]'}`;
        prevBtn.innerHTML = '<span class="material-symbols-outlined text-[16px] block">chevron_left</span>';
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) onPageChange(currentPage - 1);
        });
        container.appendChild(prevBtn);

        // Page Numbers Logic (max 5 pills with ellipsis if needed)
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.type = 'button';
            pageBtn.textContent = i;
            if (i === currentPage) {
                pageBtn.className = 'w-7 h-7 rounded-lg bg-[#5B50E5] text-white font-bold text-xs flex items-center justify-center shadow-xs';
            } else {
                pageBtn.className = 'w-7 h-7 rounded-lg border border-[#E5E7EB] bg-white text-[#464555] hover:text-[#161A30] hover:bg-[#F4F5F9] font-semibold text-xs flex items-center justify-center transition-colors';
                pageBtn.addEventListener('click', () => onPageChange(i));
            }
            container.appendChild(pageBtn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = `p-1.5 rounded-lg border border-[#E5E7EB] transition-colors ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed text-[#8A94A6]' : 'text-[#161A30] hover:bg-[#F4F5F9]'}`;
        nextBtn.innerHTML = '<span class="material-symbols-outlined text-[16px] block">chevron_right</span>';
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) onPageChange(currentPage + 1);
        });
        container.appendChild(nextBtn);
    }

    function updatePagination() {
        // 1. Filter Table Rows
        const visibleTableRows = tableRows.filter(row => getMatches(row));
        const totalTableItems = visibleTableRows.length;
        const totalTablePages = Math.ceil(totalTableItems / ITEMS_PER_PAGE) || 1;
        if (tableCurrentPage > totalTablePages) tableCurrentPage = 1;

        tableRows.forEach(row => row.style.display = 'none');
        const startIdx = (tableCurrentPage - 1) * ITEMS_PER_PAGE;
        const endIdx = startIdx + ITEMS_PER_PAGE;
        visibleTableRows.slice(startIdx, endIdx).forEach(row => row.style.display = '');

        const tableEmptyState = document.getElementById('tableScheduleEmptyState');
        const tablePaginationFooter = document.getElementById('tablePaginationFooter');
        if (totalTableItems === 0) {
            if (tableEmptyState) tableEmptyState.classList.remove('hidden');
            if (tablePaginationFooter) tablePaginationFooter.classList.add('hidden');
        } else {
            if (tableEmptyState) tableEmptyState.classList.add('hidden');
            if (tablePaginationFooter) tablePaginationFooter.classList.remove('hidden');
            
            const startDisplay = startIdx + 1;
            const endDisplay = Math.min(endIdx, totalTableItems);
            const tablePaginationInfo = document.getElementById('tablePaginationInfo');
            if (tablePaginationInfo) {
                tablePaginationInfo.textContent = `Menampilkan ${startDisplay}-${endDisplay} dari ${totalTableItems} jadwal`;
            }
            renderPaginationControls(
                document.getElementById('tablePaginationControls'),
                tableCurrentPage,
                totalTablePages,
                (p) => { tableCurrentPage = p; updatePagination(); }
            );
        }

        // 2. Filter Grid Cards
        const visibleGridCards = gridCards.filter(card => getMatches(card));
        const totalGridItems = visibleGridCards.length;
        const totalGridPages = Math.ceil(totalGridItems / ITEMS_PER_PAGE) || 1;
        if (gridCurrentPage > totalGridPages) gridCurrentPage = 1;

        gridCards.forEach(card => card.style.display = 'none');
        const gStartIdx = (gridCurrentPage - 1) * ITEMS_PER_PAGE;
        const gEndIdx = gStartIdx + ITEMS_PER_PAGE;
        visibleGridCards.slice(gStartIdx, gEndIdx).forEach(card => card.style.display = '');

        const gridEmptyState = document.getElementById('gridScheduleEmptyState');
        const gridPaginationFooter = document.getElementById('gridPaginationFooter');
        if (totalGridItems === 0) {
            if (gridEmptyState) gridEmptyState.classList.remove('hidden');
            if (gridPaginationFooter) gridPaginationFooter.classList.add('hidden');
        } else {
            if (gridEmptyState) gridEmptyState.classList.add('hidden');
            if (gridPaginationFooter) gridPaginationFooter.classList.remove('hidden');

            const gStartDisplay = gStartIdx + 1;
            const gEndDisplay = Math.min(gEndIdx, totalGridItems);
            const gridPaginationInfo = document.getElementById('gridPaginationInfo');
            if (gridPaginationInfo) {
                gridPaginationInfo.textContent = `Menampilkan ${gStartDisplay}-${gEndDisplay} dari ${totalGridItems} jadwal`;
            }
            renderPaginationControls(
                document.getElementById('gridPaginationControls'),
                gridCurrentPage,
                totalGridPages,
                (p) => { gridCurrentPage = p; updatePagination(); }
            );
        }

        if (resultCount) {
            resultCount.textContent = `Menampilkan ${totalTableItems} Jadwal`;
        }
    }

    // Semester click
    semesterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            semesterBtns.forEach(b => {
                b.className = 'semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all';
            });
            btn.className = 'semester-filter-btn active-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#5B50E5] text-white shadow-sm transition-all';
            currentSemester = btn.getAttribute('data-semester');
            tableCurrentPage = 1;
            gridCurrentPage = 1;
            updatePagination();
        });
    });

    // Shift click
    shiftBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            shiftBtns.forEach(b => {
                b.className = 'shift-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] flex items-center gap-1 transition-all';
            });
            btn.className = 'shift-filter-btn active-shift px-3 py-1 rounded-full text-xs font-bold bg-[#5B50E5] text-white shadow-sm flex items-center gap-1 transition-all';
            currentShift = btn.getAttribute('data-shift');
            tableCurrentPage = 1;
            gridCurrentPage = 1;
            updatePagination();
        });
    });

    // Day click
    dayBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            dayBtns.forEach(b => {
                b.className = 'day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors';
            });
            btn.className = 'day-filter-btn active-day px-3 py-1 rounded-full text-xs font-bold bg-[#5B50E5] text-white transition-colors';
            currentDay = btn.getAttribute('data-day');
            tableCurrentPage = 1;
            gridCurrentPage = 1;
            updatePagination();
        });
    });

    // Room select
    if (roomSelect) {
        roomSelect.addEventListener('change', (e) => {
            currentRoom = e.target.value;
            tableCurrentPage = 1;
            gridCurrentPage = 1;
            updatePagination();
        });
    }

    // Search input
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.trim();
            tableCurrentPage = 1;
            gridCurrentPage = 1;
            updatePagination();
        });
    }

    // Reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            currentSemester = 'all';
            currentShift = 'all';
            currentDay = 'all';
            currentRoom = 'all';
            searchQuery = '';
            tableCurrentPage = 1;
            gridCurrentPage = 1;

            if (searchInput) searchInput.value = '';
            if (roomSelect) roomSelect.value = 'all';

            semesterBtns.forEach(b => {
                if (b.getAttribute('data-semester') === 'all') {
                    b.className = 'semester-filter-btn active-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#5B50E5] text-white shadow-sm transition-all';
                } else {
                    b.className = 'semester-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all';
                }
            });

            shiftBtns.forEach(b => {
                if (b.getAttribute('data-shift') === 'all') {
                    b.className = 'shift-filter-btn active-shift px-3 py-1 rounded-full text-xs font-bold bg-[#5B50E5] text-white shadow-sm transition-all';
                } else {
                    b.className = 'shift-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] flex items-center gap-1 transition-all';
                }
            });

            dayBtns.forEach(b => {
                if (b.getAttribute('data-day') === 'all') {
                    b.className = 'day-filter-btn active-day px-3 py-1 rounded-full text-xs font-bold bg-[#5B50E5] text-white transition-colors';
                } else {
                    b.className = 'day-filter-btn px-3 py-1 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:text-[#161A30] transition-colors';
                }
            });

            updatePagination();
        });
    }

    // Initialize Pagination
    updatePagination();
});
</script>
@endpush
@endsection
