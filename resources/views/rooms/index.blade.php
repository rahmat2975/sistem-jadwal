@extends('layouts.app')

@section('title', 'Ruang Perkuliahan & Laboratorium - SI Jadwal TI Universitas Surakarta')

@section('content')
<div class="flex flex-col w-full pb-12 gap-y-6">

    <!-- 1. HEADER & BREADCRUMB -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pt-1">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2 text-[#8A94A6] text-xs font-semibold">
                <a href="{{ route('dashboard') }}" class="hover:text-[#5B50E5]">Dashboard</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span>Akademik</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-[#5B50E5] font-bold">Ruang Perkuliahan</span>
            </div>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#161A30] tracking-tight">
                    Ruang Kuliah
                </h1>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-xs font-bold border border-[#4F46E5]/15">
                    <span class="material-symbols-outlined text-[15px]">apartment</span>
                <span>Fakultas Teknik</span>
                </div>
            </div>
            <p class="text-xs sm:text-sm text-[#8A94A6] mt-0.5">
                Ketersediaan ruang dan jadwal hari ini.
            </p>
        </div>

        <!-- Global Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="window.print()" class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] font-bold text-xs border border-[#E5E7EB] shadow-xs hover:bg-[#F9FAFC] transition-all cursor-pointer" type="button">
                <span class="material-symbols-outlined text-[18px] text-[#8A94A6]">print</span>
                <span>Cetak Daftar Ruang</span>
            </button>

            <a href="{{ route('jadwal.semua') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#5B50E5] hover:bg-[#4233cc] text-white font-bold text-xs shadow-md shadow-[#5B50E5]/25 transition-all">
                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                <span>Lihat Alokasi Jadwal</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI METRIC SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1: Total Ruangan -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB] flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Total Ruangan Terdata</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#161A30]">{{ $totalRooms }}</span>
                    <span class="text-xs text-[#059669] font-bold">100% Aktif</span>
                </div>
                <div class="text-xs text-[#8A94A6] mt-1">{{ $totalLab }} Lab · {{ $totalKelas }} Ruang Teori</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] text-[#4F46E5] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">meeting_room</span>
            </div>
        </div>

        <!-- Card 2: Okupansi Real-Time -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB] flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Status Okupansi Saat Ini</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold {{ $occupiedCount > 0 ? 'text-[#059669]' : 'text-[#161A30]' }}">
                        {{ $occupiedCount }}
                    </span>
                    <span class="text-xs font-bold text-[#059669]">Sedang Digunakan</span>
                </div>
                <div class="text-xs text-[#8A94A6] mt-1">{{ $availableCount }} Ruang Kosong / Tersedia</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] text-[#059669] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">nest_clock_farsight_analog</span>
            </div>
        </div>

        <!-- Card 3: Total Kapasitas Kursi -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB] flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Total Kapasitas Kursi</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#161A30]">{{ $totalKapasitas }}</span>
                    <span class="text-xs text-[#8A94A6] font-semibold">Kursi / Workstation</span>
                </div>
                <div class="text-xs text-[#8A94A6] mt-1">Rata-rata {{ $totalRooms > 0 ? round($totalKapasitas / $totalRooms) : 0 }} kursi / ruang</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#ececff] text-[#5B50E5] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">airline_seat_recline_normal</span>
            </div>
        </div>

        <!-- Card 4: Integritas Alokasi -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-xs border border-[#E5E7EB] flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider">Zona Waktu Sistem</span>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#059669]">WIB</span>
                    <span class="text-xs text-[#059669] font-bold">UTC+7</span>
                </div>
                <div class="text-xs text-[#8A94A6] mt-1">Waktu kampus · WIB</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] text-[#059669] flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">schedule</span>
            </div>
        </div>
    </div>

    <!-- 3. FILTER & SEARCH TOOLBAR -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-[#E5E7EB] flex flex-col gap-3">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8A94A6] text-[18px]">search</span>
                <input id="roomSearchInput" onkeyup="filterRooms()" type="text" placeholder="Cari nama ruang, kode ruang (contoh: LAB-PBD), atau gedung..." 
                       class="w-full bg-[#F4F5F9] text-[#161A30] placeholder:text-[#8A94A6] pl-10 pr-4 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20 border border-transparent focus:border-[#5B50E5]/30 transition-all">
            </div>

            <!-- Filters & View Switch -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Status Filter -->
                <div class="relative">
                    <select id="statusFilterSelect" onchange="filterRooms()" class="appearance-none bg-[#F4F5F9] text-[#161A30] text-xs font-semibold pl-3 pr-8 py-2.5 rounded-xl outline-none cursor-pointer hover:bg-[#EEF2FF] transition-colors border border-transparent">
                        <option value="all">Semua Status (Saat Ini)</option>
                        <option value="occupied">Sedang Digunakan</option>
                        <option value="available">Tersedia / Kosong</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-[#8A94A6] text-[16px] pointer-events-none">expand_more</span>
                </div>

                <!-- Type Filter -->
                <div class="relative">
                    <select id="typeFilterSelect" onchange="filterRooms()" class="appearance-none bg-[#F4F5F9] text-[#161A30] text-xs font-semibold pl-3 pr-8 py-2.5 rounded-xl outline-none cursor-pointer hover:bg-[#EEF2FF] transition-colors border border-transparent">
                        <option value="all">Semua Jenis Ruang</option>
                        <option value="laboratorium">Laboratorium Komputer</option>
                        <option value="kelas">Ruang Kelas Teori</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-[#8A94A6] text-[16px] pointer-events-none">expand_more</span>
                </div>

                <!-- Grid vs Table Toggle Buttons -->
                <div class="flex items-center p-1 bg-[#F4F5F9] rounded-xl border border-[#E5E7EB]/50">
                    <button id="btnGridView" onclick="setViewMode('grid')" title="Tampilan Grid" class="p-1.5 rounded-lg bg-white text-[#5B50E5] shadow-xs cursor-pointer transition-all">
                        <span class="material-symbols-outlined text-[18px]">grid_view</span>
                    </button>
                    <button id="btnTableView" onclick="setViewMode('table')" title="Tampilan Tabel" class="p-1.5 rounded-lg text-[#8A94A6] hover:text-[#161A30] cursor-pointer transition-all">
                        <span class="material-symbols-outlined text-[18px]">view_list</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. GRID VIEW OF ROOM CARDS -->
    <div id="roomGridView" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($rooms as $room)
            @php
                $currentSchedule = $room->current_schedule;
                $nextSchedule = $room->next_schedule_today;
                $isOccupied = ($currentSchedule !== null);
                $weeklyCount = $room->schedules->count();
                $isLab = ($room->jenis === 'laboratorium');
            @endphp
            <div class="room-card bg-white rounded-3xl p-5 shadow-xs border border-[#E5E7EB] hover:border-[#5B50E5]/40 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden group"
                 data-name="{{ strtolower($room->nama) }}"
                 data-code="{{ strtolower($room->kode) }}"
                 data-gedung="{{ strtolower($room->gedung) }}"
                 data-type="{{ $room->jenis }}"
                 data-status="{{ $isOccupied ? 'occupied' : 'available' }}">
                
                <!-- Left Accent Border -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isOccupied ? 'bg-[#EF4444]' : 'bg-[#059669]' }}"></div>

                <div>
                    <!-- Top Pill Row: Code & Live Status -->
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <span class="px-2.5 py-1 rounded-xl bg-[#F4F5F9] text-[#161A30] font-mono font-extrabold text-xs">
                            {{ $room->kode }}
                        </span>

                        @if($isOccupied)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#FEF2F2] text-[#EF4444] text-[11px] font-bold border border-[#FECACA]">
                                <span class="w-2 h-2 rounded-full bg-[#EF4444] animate-pulse"></span>
                                <span>Sedang Digunakan</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#ECFDF5] text-[#059669] text-[11px] font-bold border border-[#A7F3D0]">
                                <span class="w-2 h-2 rounded-full bg-[#059669]"></span>
                                <span>Tersedia / Kosong</span>
                            </span>
                        @endif
                    </div>

                    <!-- Room Title & Building Location -->
                    <h3 class="text-base font-extrabold text-[#161A30] group-hover:text-[#5B50E5] transition-colors leading-snug">
                        {{ $room->nama }}
                    </h3>
                    
                    <div class="flex items-center gap-1.5 text-xs text-[#8A94A6] mt-1">
                        <span class="material-symbols-outlined text-[16px] text-[#5B50E5]">apartment</span>
                        <span class="truncate">{{ $room->gedung }}</span>
                    </div>

                    <!-- Room Meta Badges -->
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $isLab ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'bg-[#F4F5F9] text-[#464555]' }}">
                            {{ $isLab ? 'Laboratorium' : 'Ruang Teori' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-[#F4F5F9] text-[#464555] flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">group</span>
                            <span>{{ $room->kapasitas }} Kursi</span>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-[#F4F5F9] text-[#464555]">
                            {{ $weeklyCount }} Sesi / Pekan
                        </span>
                    </div>

                    <!-- Current or Next Class Box -->
                    <div class="mt-4 p-3 rounded-2xl {{ $isOccupied ? 'bg-[#FEF2F2]/60 border border-[#FECACA]' : 'bg-[#F9FAFC] border border-[#E5E7EB]' }} flex flex-col gap-1 text-xs">
                        @if($isOccupied && $currentSchedule)
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold text-[#EF4444] uppercase tracking-wider">Kuliah Berlangsung</span>
                                <span class="font-mono text-[#EF4444] font-bold">
                                    {{ substr($currentSchedule->jam_mulai, 0, 5) }} - {{ substr($currentSchedule->jam_selesai, 0, 5) }} WIB
                                </span>
                            </div>
                            <div class="font-extrabold text-[#161A30] line-clamp-1 mt-0.5">
                                {{ $currentSchedule->course?->nama }}
                            </div>
                            <div class="text-[#8A94A6] line-clamp-1 text-[11px]">
                                {{ $currentSchedule->lecturers->pluck('nama')->join(', ') ?: 'Dosen Pengampu' }} ({{ $currentSchedule->kelas }})
                            </div>
                        @elseif($nextSchedule)
                            <div class="flex items-center justify-between text-[#8A94A6]">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#5B50E5]">Sesi Berikutnya Hari Ini</span>
                                <span class="font-mono text-[#5B50E5] font-bold">
                                    {{ substr($nextSchedule->jam_mulai, 0, 5) }} WIB
                                </span>
                            </div>
                            <div class="font-bold text-[#161A30] line-clamp-1 mt-0.5">
                                {{ $nextSchedule->course?->nama }}
                            </div>
                            <div class="text-[#8A94A6] line-clamp-1 text-[11px]">
                                Shift {{ ucfirst($nextSchedule->shift) }} · {{ $nextSchedule->kelas }}
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 text-[#059669] font-medium py-1">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                <span>Tidak ada jadwal lagi hari ini.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Card Actions -->
                <div class="mt-5 pt-3 border-t border-[#F1F3F7] flex items-center justify-between gap-2">
                    <button type="button" 
                            onclick="openRoomModal({{ $room->id }}, '{{ addslashes($room->nama) }}', '{{ $room->kode }}', '{{ addslashes($room->gedung) }}', {{ $room->kapasitas }}, '{{ $room->jenis }}')"
                            class="text-xs font-bold text-[#5B50E5] hover:text-[#4233cc] flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">calendar_view_week</span>
                        <span>Jadwal Ruangan</span>
                    </button>

                    <a href="{{ route('jadwal.semua') }}" class="px-3 py-1.5 rounded-xl bg-[#EEF2FF] text-[#5B50E5] hover:bg-[#5B50E5] hover:text-white text-xs font-bold transition-all">
                        Semua Jadwal →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 5. TABLE VIEW OF ROOMS (ALTERNATIVE TOGGLE) -->
    <div id="roomTableView" class="hidden bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFC] border-b border-[#E5E7EB] text-[11px] font-extrabold text-[#8A94A6] uppercase tracking-wider">
                        <th class="py-3.5 px-4">Kode Ruang</th>
                        <th class="py-3.5 px-4">Nama Ruangan</th>
                        <th class="py-3.5 px-4">Jenis</th>
                        <th class="py-3.5 px-4">Gedung / Lokasi</th>
                        <th class="py-3.5 px-4">Kapasitas</th>
                        <th class="py-3.5 px-4">Status Saat Ini</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB] text-xs">
                    @foreach($rooms as $room)
                        @php
                            $currentSchedule = $room->current_schedule;
                            $isOccupied = ($currentSchedule !== null);
                        @endphp
                        <tr class="hover:bg-[#F9FAFC] transition-colors room-table-row"
                            data-name="{{ strtolower($room->nama) }}"
                            data-code="{{ strtolower($room->kode) }}"
                            data-gedung="{{ strtolower($room->gedung) }}"
                            data-type="{{ $room->jenis }}"
                            data-status="{{ $isOccupied ? 'occupied' : 'available' }}">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#161A30]">
                                {{ $room->kode }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#161A30]">
                                {{ $room->nama }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $room->jenis === 'laboratorium' ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'bg-[#F4F5F9] text-[#464555]' }}">
                                    {{ ucfirst($room->jenis) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#464555]">
                                {{ $room->gedung }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#161A30]">
                                {{ $room->kapasitas }} Kursi
                            </td>
                            <td class="py-3.5 px-4">
                                @if($isOccupied)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#FEF2F2] text-[#EF4444] text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                                        <span>Digunakan</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#ECFDF5] text-[#059669] text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#059669]"></span>
                                        <span>Tersedia</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" 
                                        onclick="openRoomModal({{ $room->id }}, '{{ addslashes($room->nama) }}', '{{ $room->kode }}', '{{ addslashes($room->gedung) }}', {{ $room->kapasitas }}, '{{ $room->jenis }}')"
                                        class="px-2.5 py-1 rounded-lg bg-[#EEF2FF] text-[#5B50E5] hover:bg-[#5B50E5] hover:text-white font-bold text-xs transition-all">
                                    Jadwal
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="roomPaginationControls" class="flex items-center justify-center gap-1.5 mt-2"></div>
</div>

<!-- 6. ROOM SCHEDULE MODAL -->
<div id="roomDetailModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl border border-[#E5E7EB] overflow-hidden flex flex-col max-h-[85vh]">
        <!-- Modal Header -->
        <div class="flex items-start justify-between pb-4 border-b border-[#E5E7EB]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span id="modalRoomCode" class="px-2 py-0.5 rounded-lg bg-[#EEF2FF] text-[#5B50E5] font-mono text-xs font-bold">LAB-PBD</span>
                    <span id="modalRoomType" class="text-xs text-[#8A94A6]">Laboratorium</span>
                </div>
                <h3 id="modalRoomName" class="text-lg sm:text-xl font-extrabold text-[#161A30]">Nama Ruangan</h3>
                <p id="modalRoomGedung" class="text-xs text-[#8A94A6] mt-0.5">Gedung TI Lt. 2 · Kapasitas 45 Kursi</p>
            </div>
            <button onclick="closeRoomModal()" class="w-8 h-8 rounded-full bg-[#F4F5F9] text-[#8A94A6] hover:text-[#161A30] hover:bg-[#E5E7EB] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Modal Body: Weekly Schedule for this Room -->
        <div class="py-4 overflow-y-auto flex-1 space-y-3" id="modalScheduleContainer">
            <!-- Injected dynamically via JS from rooms data -->
        </div>

        <!-- Modal Footer -->
        <div class="pt-4 border-t border-[#E5E7EB] flex items-center justify-between">
            <span class="text-xs text-[#8A94A6]">Waktu Sesi: Mengacu Zona WIB (UTC+7)</span>
            <button onclick="closeRoomModal()" class="px-4 py-2 rounded-xl bg-[#F4F5F9] text-[#161A30] hover:bg-[#E5E7EB] text-xs font-bold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // Room schedules mapping for modal
    const roomsData = @json($rooms);
    let currentRoomPage = 1;
    const ROOM_PAGE_SIZE = 10;

    function filterRooms() {
        const search = document.getElementById('roomSearchInput').value.toLowerCase();
        const status = document.getElementById('statusFilterSelect').value;
        const type = document.getElementById('typeFilterSelect').value;

        // Filter Grid Cards
        const cards = document.querySelectorAll('.room-card');
        cards.forEach(card => {
            const cardName = card.getAttribute('data-name') || '';
            const cardCode = card.getAttribute('data-code') || '';
            const cardGedung = card.getAttribute('data-gedung') || '';
            const cardType = card.getAttribute('data-type') || '';
            const cardStatus = card.getAttribute('data-status') || '';

            const matchSearch = cardName.includes(search) || cardCode.includes(search) || cardGedung.includes(search);
            const matchStatus = (status === 'all') || (cardStatus === status);
            const matchType = (type === 'all') || (cardType === type);

            if (matchSearch && matchStatus && matchType) {
                card.dataset.roomMatch = '1';
            } else {
                card.dataset.roomMatch = '0';
            }
        });

        // Filter Table Rows
        const rows = document.querySelectorAll('.room-table-row');
        rows.forEach(row => {
            const rowName = row.getAttribute('data-name') || '';
            const rowCode = row.getAttribute('data-code') || '';
            const rowGedung = row.getAttribute('data-gedung') || '';
            const rowType = row.getAttribute('data-type') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchSearch = rowName.includes(search) || rowCode.includes(search) || rowGedung.includes(search);
            const matchStatus = (status === 'all') || (rowStatus === status);
            const matchType = (type === 'all') || (rowType === type);

            if (matchSearch && matchStatus && matchType) {
                row.dataset.roomMatch = '1';
            } else {
                row.dataset.roomMatch = '0';
            }
        });

        currentRoomPage = 1;
        renderRoomPage();
    }

    function renderRoomPage() {
        const cards = [...document.querySelectorAll('.room-card')];
        const rows = [...document.querySelectorAll('.room-table-row')];
        const matchingCards = cards.filter(item => item.dataset.roomMatch === '1');
        const matchingRows = rows.filter(item => item.dataset.roomMatch === '1');
        const totalItems = matchingCards.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / ROOM_PAGE_SIZE));
        currentRoomPage = Math.min(currentRoomPage, totalPages);
        const start = (currentRoomPage - 1) * ROOM_PAGE_SIZE;
        const end = start + ROOM_PAGE_SIZE;

        cards.forEach(item => item.style.display = 'none');
        rows.forEach(item => item.style.display = 'none');
        matchingCards.slice(start, end).forEach(item => item.style.display = 'flex');
        matchingRows.slice(start, end).forEach(item => item.style.display = 'table-row');

        const controls = document.getElementById('roomPaginationControls');
        controls.innerHTML = '';
        if (totalItems <= ROOM_PAGE_SIZE) return;
        const button = (label, page, disabled = false) => {
            const el = document.createElement('button');
            el.type = 'button';
            el.textContent = label;
            el.disabled = disabled;
            el.className = 'px-3 py-1.5 rounded-lg border border-[#E5E7EB] text-xs font-bold ' + (disabled ? 'opacity-40 cursor-not-allowed' : 'hover:bg-[#EEF2FF] text-[#5B50E5]');
            el.onclick = () => { currentRoomPage = page; renderRoomPage(); };
            controls.appendChild(el);
        };
        button('Previous', currentRoomPage - 1, currentRoomPage === 1);
        const label = document.createElement('span');
        label.className = 'px-3 text-xs font-semibold text-[#8A94A6]';
        label.textContent = `Halaman ${currentRoomPage} dari ${totalPages}`;
        controls.appendChild(label);
        button('Next', currentRoomPage + 1, currentRoomPage === totalPages);
    }

    function setViewMode(mode) {
        const gridView = document.getElementById('roomGridView');
        const tableView = document.getElementById('roomTableView');
        const btnGrid = document.getElementById('btnGridView');
        const btnTable = document.getElementById('btnTableView');

        if (mode === 'grid') {
            gridView.classList.remove('hidden');
            tableView.classList.add('hidden');
            btnGrid.className = 'p-1.5 rounded-lg bg-white text-[#5B50E5] shadow-xs cursor-pointer transition-all';
            btnTable.className = 'p-1.5 rounded-lg text-[#8A94A6] hover:text-[#161A30] cursor-pointer transition-all';
        } else {
            gridView.classList.add('hidden');
            tableView.classList.remove('hidden');
            btnTable.className = 'p-1.5 rounded-lg bg-white text-[#5B50E5] shadow-xs cursor-pointer transition-all';
            btnGrid.className = 'p-1.5 rounded-lg text-[#8A94A6] hover:text-[#161A30] cursor-pointer transition-all';
        }
    }

    const daysNameMap = {
        1: 'Senin',
        2: 'Selasa',
        3: 'Rabu',
        4: 'Kamis',
        5: 'Jumat',
        6: 'Sabtu',
        7: 'Minggu'
    };

    function openRoomModal(roomId, name, code, gedung, kapasitas, jenis) {
        document.getElementById('modalRoomCode').textContent = code;
        document.getElementById('modalRoomType').textContent = (jenis === 'laboratorium') ? 'Laboratorium Komputer' : 'Ruang Kelas Teori';
        document.getElementById('modalRoomName').textContent = name;
        document.getElementById('modalRoomGedung').textContent = gedung + ' · Kapasitas: ' + kapasitas + ' Kursi';

        const room = roomsData.find(r => r.id === roomId);
        const container = document.getElementById('modalScheduleContainer');
        container.innerHTML = '';

        if (!room || !room.schedules || room.schedules.length === 0) {
            container.innerHTML = `
                <div class="p-6 text-center text-[#8A94A6]">
                    <span class="material-symbols-outlined text-[36px] text-[#8A94A6] mb-1">event_available</span>
                    <p class="text-xs font-semibold">Belum ada alokasi jadwal perkuliahan mingguan untuk ruangan ini.</p>
                </div>
            `;
        } else {
            // Group schedules by day
            const grouped = {};
            room.schedules.forEach(s => {
                const dayName = daysNameMap[s.hari] || 'Lainnya';
                if (!grouped[dayName]) grouped[dayName] = [];
                grouped[dayName].push(s);
            });

            for (const [day, schList] of Object.entries(grouped)) {
                const dayBlock = document.createElement('div');
                dayBlock.className = 'space-y-1.5';
                
                let schedulesHtml = '';
                schList.forEach(s => {
                    const courseName = s.course ? s.course.nama : 'Mata Kuliah';
                    const courseCode = s.course ? s.course.kode : '-';
                    const timeRange = s.jam_mulai.substring(0, 5) + ' - ' + s.jam_selesai.substring(0, 5) + ' WIB';
                    const lecturers = (s.lecturers && s.lecturers.length > 0) ? s.lecturers.map(l => l.nama).join(', ') : 'Dosen Pengampu';

                    schedulesHtml += `
                        <div class="p-3 rounded-xl bg-[#F9FAFC] border border-[#E5E7EB] flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <div class="font-extrabold text-[#161A30] truncate">${courseName} (${courseCode})</div>
                                <div class="text-[11px] text-[#8A94A6] truncate">Dosen: ${lecturers} · ${s.kelas}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-mono font-bold text-[#5B50E5]">${timeRange}</div>
                                <div class="text-[10px] text-[#8A94A6] uppercase">Shift ${s.shift}</div>
                            </div>
                        </div>
                    `;
                });

                dayBlock.innerHTML = `
                    <div class="text-[11px] font-extrabold text-[#5B50E5] uppercase tracking-wider px-1">Hari ${day}</div>
                    <div class="space-y-1.5">${schedulesHtml}</div>
                `;
                container.appendChild(dayBlock);
            }
        }

        document.getElementById('roomDetailModal').classList.remove('hidden');
    }

    function closeRoomModal() {
        document.getElementById('roomDetailModal').classList.add('hidden');
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeRoomModal();
    });

    filterRooms();
</script>
@endsection
