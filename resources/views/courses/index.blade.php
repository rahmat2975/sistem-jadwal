@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah & Struktur Kurikulum - SI Jadwal TI')

@section('content')
<div class="flex flex-col w-full pb-12 gap-y-6">

    <!-- Top Navigation & Header Row -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mt-1">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Akademik</span>
                <span class="material-symbols-outlined text-[14px] text-[#8A94A6]">chevron_right</span>
                <span class="text-[11px] font-bold text-[#5B50E5]">Kurikulum & Mata Kuliah</span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-[#161A30] tracking-tight">Mata Kuliah</h1>
            <p class="text-xs sm:text-sm text-[#464555] flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-[#161A30]">Departemen Teknik Informatika</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#c8c4d7]"></span>
                <span>Fakultas Teknik</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#c8c4d7]"></span>
                <span class="px-2.5 py-0.5 rounded-md bg-[#ececff] text-xs text-[#5B50E5] font-semibold">Kurikulum OBE · {{ $stats['total_sks'] }} SKS</span>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] hover:bg-[#ececff] transition-all shadow-sm border border-[#E5E7EB] text-xs font-bold" type="button">
                <span class="material-symbols-outlined text-[18px] text-[#464555]">picture_as_pdf</span>
                <span>Unduh PDF</span>
            </button>
            <button onclick="exportToCSV()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] hover:bg-[#ececff] transition-all shadow-sm border border-[#E5E7EB] text-xs font-bold" type="button">
                <span class="material-symbols-outlined text-[18px] text-[#059669]">table_view</span>
                <span>Ekspor Excel</span>
            </button>
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#5B50E5] text-white hover:bg-[#4233cc] transition-all shadow-md shadow-[#5B50E5]/25 text-xs font-bold">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Kelola Mata Kuliah</span>
            </a>
        </div>
    </div>

    <!-- Metric Summary Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Total Mata Kuliah</span>
                <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] flex items-center justify-center text-[#4F46E5] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">menu_book</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['total_mk'] }} MK</span>
                <p class="text-xs text-[#464555] mt-1">{{ $stats['mk_wajib'] }} MK Wajib · {{ $stats['mk_pilihan'] }} MK Pilihan</p>
            </div>
            <div class="w-full bg-[#ececff] h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-[#5B50E5] h-full rounded-full" style="width: {{ $stats['total_mk'] > 0 ? round(($stats['mk_wajib'] / $stats['total_mk']) * 100) : 0 }}%"></div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Beban Kurikulum</span>
                <div class="w-10 h-10 rounded-xl bg-[#ececff] flex items-center justify-center text-[#4233cc] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">workspace_premium</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['total_sks'] }} SKS</span>
                <p class="text-xs text-[#464555] mt-1">Syarat lulus S.Kom</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#8A94A6] font-semibold">
                <span class="material-symbols-outlined text-[16px] text-[#059669]">check_circle</span>
                <span>Terstandar</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">SKS Semester Aktif</span>
                <span class="px-2.5 py-1 rounded-full bg-[#ECFDF5] text-[#059669] text-[11px] font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#059669] animate-pulse"></span>
                    {{ $activeYear?->tahun ?? 'Periode aktif' }}
                </span>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['sks_semester_aktif'] }} SKS</span>
                <p class="text-xs text-[#464555] mt-1">Semester {{ ucfirst($activeYear?->semester ?? '-') }}</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#5B50E5] font-semibold">
                <span class="material-symbols-outlined text-[16px]">event_repeat</span>
                    <span>{{ $stats['total_rombel'] }} jadwal terdaftar</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Praktek & Laboratorium</span>
                <div class="w-10 h-10 rounded-xl bg-[#e3dfff] flex items-center justify-center text-[#4233cc] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">laptop_chromebook</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['sks_lab'] }} SKS ({{ $stats['percent_lab'] }}%)</span>
                <p class="text-xs text-[#464555] mt-1">{{ $stats['active_labs'] }} lab aktif</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#464555] font-semibold">
                <span class="material-symbols-outlined text-[16px] text-[#5B50E5]">science</span>
                <span>Data berdasarkan mata kuliah aktif</span>
            </div>
        </div>
    </div>

    <!-- Filter & Interactive Controls Bar -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#E5E7EB]/80 flex flex-col gap-4">
        <!-- Search Bar & Primary Selects -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8A94A6] text-[20px]">search</span>
                <input class="w-full pl-10 pr-4 py-2.5 rounded-full bg-[#F4F5F9] text-xs sm:text-sm text-[#161A30] placeholder:text-[#8A94A6] focus:outline-none focus:ring-2 focus:ring-[#5B50E5]/30 focus:bg-white transition-all" 
                       id="courseSearchInput" 
                       placeholder="Cari kode atau nama mata kuliah..." 
                       type="text"/>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Tipe MK Filter -->
                <div class="relative">
                    <select id="courseTypeFilter" class="appearance-none pl-3.5 pr-8 py-2 rounded-full bg-[#F4F5F9] text-xs font-semibold text-[#161A30] focus:outline-none focus:ring-2 focus:ring-[#5B50E5]/30 cursor-pointer transition-all">
                        <option value="all">Semua Tipe (Wajib & Pilihan)</option>
                        <option value="wajib">Mata Kuliah Wajib (MKW)</option>
                        <option value="pilihan">Mata Kuliah Pilihan (MKP)</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-[#8A94A6] pointer-events-none">expand_more</span>
                </div>

                <!-- KBK / Lab Filter -->
                <div class="relative">
                    <select id="courseKbkFilter" class="appearance-none pl-3.5 pr-8 py-2 rounded-full bg-[#F4F5F9] text-xs font-semibold text-[#161A30] focus:outline-none focus:ring-2 focus:ring-[#5B50E5]/30 cursor-pointer transition-all">
                        <option value="all">Semua Lab / KBK Keahlian</option>
                        <option value="KBK Rekayasa Perangkat Lunak">KBK Rekayasa Perangkat Lunak</option>
                        <option value="KBK Komputasi Cerdas & AI">KBK Komputasi Cerdas & AI</option>
                        <option value="KBK Jaringan, Keamanan & IoT">KBK Jaringan, Keamanan & IoT</option>
                        <option value="KBK Sistem Informasi & Basis Data">KBK Sistem Informasi & Basis Data</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-[#8A94A6] pointer-events-none">expand_more</span>
                </div>

                <!-- Toggle View Mode -->
                <div class="flex items-center bg-[#F4F5F9] p-1 rounded-full">
                    <button class="px-3 py-1.5 rounded-full bg-white text-[#5B50E5] font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all" id="viewTableBtn" type="button">
                        <span class="material-symbols-outlined text-[16px]">table_rows</span>
                        <span>Tabel</span>
                    </button>
                    <button class="px-3 py-1.5 rounded-full text-[#8A94A6] hover:text-[#161A30] font-bold text-xs flex items-center gap-1.5 transition-all" id="viewGridBtn" type="button">
                        <span class="material-symbols-outlined text-[16px]">grid_view</span>
                        <span>Grid Semester</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Semester Filter Pills / Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 no-scrollbar select-none text-nowrap" id="semesterPills">
            <button data-semester="all" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/25 transition-all" type="button">
                Semua Semester <span class="ml-1 opacity-80 font-normal">({{ $enrichedCourses->count() }})</span>
            </button>
            <button data-semester="1" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                Semester 1 <span class="ml-1 text-[#8A94A6] font-normal">(18 SKS)</span>
            </button>
            <button data-semester="2" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                Semester 2 <span class="ml-1 text-[#8A94A6] font-normal">(20 SKS)</span>
            </button>
            <button data-semester="3" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                Semester 3 <span class="ml-1 text-[#8A94A6] font-normal">(20 SKS)</span>
            </button>
            <button data-semester="4" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                Semester 4 <span class="ml-1 text-[#8A94A6] font-normal">(20 SKS)</span>
            </button>
            <button data-semester="5" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all flex items-center gap-1.5" type="button">
                <span>Semester 5</span>
                <span class="px-1.5 py-0.2 rounded-full bg-[#ECFDF5] text-[#059669] text-[10px] font-bold">Aktif</span>
            </button>
            <button data-semester="6" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                Semester 6 <span class="ml-1 text-[#8A94A6] font-normal">(19 SKS)</span>
            </button>
            <button data-semester="7" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all flex items-center gap-1.5" type="button">
                <span>Semester 7</span>
                <span class="px-1.5 py-0.2 rounded-full bg-[#ECFDF5] text-[#059669] text-[10px] font-bold">Aktif</span>
            </button>
            <button data-semester="pilihan" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                MK Pilihan / Konsentrasi <span class="ml-1 text-[#8A94A6] font-normal">(12 SKS)</span>
            </button>
        </div>
    </div>

    <!-- TABLE VIEW CONTAINER -->
    <div id="tableViewContainer" class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <!-- Table Header Bar -->
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-[#F9FAFC] border-b border-[#E5E7EB]/80">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-[#5B50E5]"></div>
                <span class="text-sm font-extrabold text-[#161A30]">Katalog Mata Kuliah Kurikulum TI 2020</span>
                <span class="text-xs text-[#8A94A6]" id="courseCountLabel">Menampilkan {{ $enrichedCourses->count() }} mata kuliah pilihan & wajib</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-[#8A94A6]">Sinkronisasi:</span>
                <span class="text-xs text-[#059669] font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#059669]"></span>
                    PDDIKTI Terhubung (v2024.1)
                </span>
            </div>
        </div>

        <!-- Table Responsive Wrapper -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="courseTable">
                <thead>
                    <tr class="bg-[#F9FAFC] text-[#8A94A6] text-[11px] uppercase tracking-wider border-b border-[#E5E7EB]">
                        <th class="py-3 px-6 font-bold">Kode MK</th>
                        <th class="py-3 px-6 font-bold">Nama Mata Kuliah & Silabus</th>
                        <th class="py-3 px-6 font-bold">SKS & Sifat</th>
                        <th class="py-3 px-6 font-bold">Semester</th>
                        <th class="py-3 px-6 font-bold">Laboratorium / Ruang</th>
                        <th class="py-3 px-6 font-bold">Koordinator & Tim Pengajar</th>
                        <th class="py-3 px-6 font-bold">Prasyarat</th>
                        <th class="py-3 px-6 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]/50 text-xs">
                    @forelse($enrichedCourses as $c)
                    <tr class="hover:bg-[#F9FAFC] transition-colors group course-row"
                        data-kode="{{ strtolower($c->kode) }}"
                        data-nama="{{ strtolower($c->nama) }}"
                        data-deskripsi="{{ strtolower($c->meta->silabus) }}"
                        data-semester="{{ $c->semester_ideal }}"
                        data-tipe="{{ $c->tipe }}"
                        data-kbk="{{ $c->meta->kbk }}">
                        
                        <!-- Kode MK -->
                        <td class="py-4 px-6 align-top">
                            <span class="px-2.5 py-1 rounded-lg bg-[#ececff] text-[#5B50E5] font-mono font-bold text-xs inline-block">
                                {{ $c->kode }}
                            </span>
                        </td>

                        <!-- Nama Mata Kuliah & Silabus -->
                        <td class="py-4 px-6 align-top max-w-xs">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-[#161A30] group-hover:text-[#5B50E5] transition-colors">
                                    {{ $c->nama }}
                                </span>
                                <span class="text-xs text-[#464555] mt-1 line-clamp-2 leading-relaxed">
                                    {{ $c->meta->silabus }}
                                </span>
                            </div>
                        </td>

                        <!-- SKS & Sifat -->
                        <td class="py-4 px-6 align-top whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-[#161A30]">{{ $c->meta->sks_detail }}</span>
                                @if($c->tipe === 'pilihan')
                                    <span class="px-2 py-0.5 rounded-full bg-[#ececff] text-[#4233cc] text-[10px] font-bold w-fit">
                                        {{ $c->meta->sifat }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-[#EEF2FF] text-[#4F46E5] text-[10px] font-bold w-fit">
                                        {{ $c->meta->sifat }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Semester -->
                        <td class="py-4 px-6 align-top whitespace-nowrap">
                            @if(in_array($c->semester_ideal, [5, 7]))
                                <span class="px-2.5 py-1 rounded-full bg-[#ECFDF5] text-[#059669] text-[11px] font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#059669]"></span>
                                    Semester {{ $c->semester_ideal }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-[#F4F2FF] text-[#464555] text-[11px] font-semibold inline-block">
                                    Semester {{ $c->semester_ideal }}
                                </span>
                            @endif
                        </td>

                        <!-- Laboratorium / Ruang -->
                        <td class="py-4 px-6 align-top">
                            <div class="flex items-center gap-1.5 text-[#161A30]">
                                <span class="material-symbols-outlined text-[16px] {{ $c->meta->icon_color }}">{{ $c->meta->icon }}</span>
                                <span class="text-xs font-medium">{{ $c->meta->lab_ruang }}</span>
                            </div>
                        </td>

                        <!-- Koordinator & Tim Pengajar -->
                        <td class="py-4 px-6 align-top">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full {{ $c->meta->avatar_bg }} {{ $c->meta->avatar_text }} flex items-center justify-center font-bold text-[10px] flex-shrink-0 shadow-sm">
                                    {{ $c->meta->initials }}
                                </div>
                                <div class="flex flex-col leading-tight">
                                    <span class="font-bold text-[#161A30] text-xs">{{ $c->meta->koordinator }}</span>
                                    <span class="text-[11px] text-[#8A94A6]">{{ $c->meta->kbk_sub }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Prasyarat -->
                        <td class="py-4 px-6 align-top">
                            <span class="px-2 py-0.5 rounded-md bg-[#ececff] text-[11px] font-medium text-[#464555] inline-block">
                                {{ $c->meta->prasyarat }}
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-6 align-top text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="openSyllabusModal('{{ $c->kode }}', '{{ addslashes($c->nama) }}', '{{ $c->sks }}', '{{ $c->semester_ideal }}', '{{ addslashes($c->meta->silabus) }}', '{{ addslashes($c->meta->koordinator) }}', '{{ addslashes($c->meta->lab_ruang) }}', '{{ addslashes($c->meta->prasyarat) }}')" 
                                        class="p-1.5 rounded-lg bg-[#F4F5F9] hover:bg-[#EEF2FF] text-[#464555] hover:text-[#5B50E5] transition-all" 
                                        title="Lihat RPS / Silabus Lengkap" 
                                        type="button">
                                    <span class="material-symbols-outlined text-[18px]">description</span>
                                </button>
                                <a href="{{ route('admin.courses.index') }}" 
                                   class="p-1.5 rounded-lg bg-[#F4F5F9] hover:bg-[#ececff] text-[#464555] hover:text-[#161A30] transition-all" 
                                   title="Kelola Mata Kuliah di Admin">
                                    <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-[#8A94A6]">
                            Belum ada mata kuliah terdata.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="px-6 py-4 bg-white border-t border-[#E5E7EB]/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-[#464555] text-xs">
                <span id="coursePaginationInfo">Menampilkan 1-10 dari total {{ $stats['total_mk'] }} mata kuliah</span>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap" id="coursePaginationControls">
                <!-- Injected via JavaScript -->
            </div>
        </div>
    </div>

    <!-- GRID VIEW CONTAINER (Hidden by default, toggled via JS) -->
    <div id="gridViewContainer" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4" id="gridCardList">
            @foreach($enrichedCourses as $c)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between hover:shadow-md hover:border-[#5B50E5]/40 transition-all group course-card"
                 data-kode="{{ strtolower($c->kode) }}"
                 data-nama="{{ strtolower($c->nama) }}"
                 data-deskripsi="{{ strtolower($c->meta->silabus) }}"
                 data-semester="{{ $c->semester_ideal }}"
                 data-tipe="{{ $c->tipe }}"
                 data-kbk="{{ $c->meta->kbk }}">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-1 rounded-lg bg-[#ececff] text-[#5B50E5] font-mono font-bold text-xs">
                            {{ $c->kode }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full {{ in_array($c->semester_ideal, [5, 7]) ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#F4F2FF] text-[#464555]' }} text-xs font-bold">
                            Sem. {{ $c->semester_ideal }}
                        </span>
                    </div>
                    <h3 class="font-bold text-sm text-[#161A30] group-hover:text-[#5B50E5] transition-colors line-clamp-1 mb-1">
                        {{ $c->nama }}
                    </h3>
                    <p class="text-xs text-[#464555] line-clamp-2 leading-relaxed mb-3">
                        {{ $c->meta->silabus }}
                    </p>
                </div>

                <div class="pt-3 border-t border-[#E5E7EB]/60 flex flex-col gap-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#161A30]">{{ $c->meta->sks_detail }}</span>
                        <span class="text-xs text-[#059669] font-semibold">{{ $c->meta->sifat }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-[#8A94A6]">
                        <span class="truncate max-w-[140px]">{{ $c->meta->lab_ruang }}</span>
                        <button onclick="openSyllabusModal('{{ $c->kode }}', '{{ addslashes($c->nama) }}', '{{ $c->sks }}', '{{ $c->semester_ideal }}', '{{ addslashes($c->meta->silabus) }}', '{{ addslashes($c->meta->koordinator) }}', '{{ addslashes($c->meta->lab_ruang) }}', '{{ addslashes($c->meta->prasyarat) }}')"
                                class="text-[#5B50E5] hover:underline font-bold text-xs">
                            RPS & Silabus →
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Grid Footer / Pagination -->
        <div id="gridPaginationContainer" class="px-6 py-4 bg-white rounded-2xl shadow-sm border border-[#E5E7EB]/80 flex flex-col sm:flex-row items-center justify-between gap-4 mt-5">
            <div class="flex items-center gap-2 text-[#464555] text-xs">
                <span id="gridPaginationInfo">Menampilkan 1-10 dari total {{ $stats['total_mk'] }} mata kuliah</span>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap" id="gridPaginationControls">
                <!-- Injected via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Bottom Section: Curriculum Structure Distribution & KBK Labs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Panel: SKS Distribution Visualization (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-6 shadow-sm border border-[#E5E7EB]/80 flex flex-col gap-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Struktur Sebaran</span>
                    <h2 class="text-lg font-bold text-[#161A30]">Peta Distribusi SKS per Semester</h2>
                    <p class="text-xs text-[#464555] mt-0.5">Struktur kurikulum berdasarkan mata kuliah yang tersedia pada periode ini.</p>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-[#ECFDF5] text-[#059669] text-xs font-bold flex items-center gap-1.5 flex-shrink-0">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    <span>Target Lulus 4 Tahun</span>
                </div>
            </div>

            <!-- Custom Mini SVG/Chart Visualization of Semesters 1 to 8 -->
            <div class="space-y-3 pt-2">
                @foreach($semesterDistribution as $dist)
                <div class="flex items-center gap-3 text-xs {{ $dist['active'] ? ($dist['sem'] === 'Sem. 5' ? 'p-1.5 -mx-1.5 rounded-xl bg-[#EEF2FF]/60' : 'p-1.5 -mx-1.5 rounded-xl bg-[#ECFDF5]/40') : '' }}">
                    <span class="w-14 text-xs font-bold {{ $dist['active'] ? ($dist['sem'] === 'Sem. 5' ? 'text-[#4F46E5] flex items-center gap-1' : 'text-[#059669]') : 'text-[#464555]' }}">
                        {{ $dist['sem'] }}
                        @if($dist['active'] && $dist['sem'] === 'Sem. 5')
                            <span class="w-1.5 h-1.5 rounded-full bg-[#4F46E5] animate-pulse"></span>
                        @endif
                    </span>
                    <div class="flex-1 bg-[#ececff] rounded-full h-3 overflow-hidden flex shadow-inner">
                        <div class="bg-[#5B50E5] h-full rounded-l-full" style="width: {{ $dist['percent'] }}%" title="Wajib: {{ $dist['wajib'] }} SKS"></div>
                        @if($dist['pilihan'] > 0)
                        <div class="bg-[#6063ee] h-full" style="width: {{ 100 - $dist['percent'] }}%" title="Pilihan: {{ $dist['pilihan'] }} SKS"></div>
                        @endif
                    </div>
                    <span class="w-16 text-right text-xs font-bold {{ $dist['active'] ? ($dist['sem'] === 'Sem. 5' ? 'text-[#4F46E5]' : 'text-[#059669]') : 'text-[#161A30]' }}">
                        {{ $dist['sks'] }} SKS
                    </span>
                </div>
                @endforeach
            </div>

            <!-- Legend -->
            <div class="flex items-center justify-between pt-3 bg-[#F9FAFC] p-3 rounded-xl border border-[#E5E7EB]/50">
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-md bg-[#5B50E5]"></span>
                        <span class="text-[#464555] font-medium">Mata Kuliah Wajib</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-md bg-[#6063ee]"></span>
                        <span class="text-[#464555] font-medium">Peminatan / MBKM / Skripsi</span>
                    </div>
                </div>
                <span class="text-xs text-[#5B50E5] font-extrabold">{{ $stats['total_sks'] }} SKS Total</span>
            </div>
        </div>

        <!-- Right Panel: Student Research KBK & Laboratories (5 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#E5E7EB]/80">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex flex-col">
                        <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Peminatan Mahasiswa</span>
                        <h3 class="text-lg font-bold text-[#161A30]">Kelompok Bidang Keahlian (KBK)</h3>
                    </div>
                    <span class="material-symbols-outlined text-[20px] text-[#5B50E5]">hub</span>
                </div>

                <!-- 3 KBK Cards -->
                <div class="space-y-3">
                    @foreach($kbkList as $kbk)
                    <div class="p-3.5 rounded-xl bg-[#F9FAFC] hover:bg-[#ececff]/50 transition-all flex items-start gap-3.5 group border border-[#E5E7EB]/40">
                        <div class="w-10 h-10 rounded-xl {{ $kbk['bg_icon'] }} {{ $kbk['text_icon'] }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[20px]">{{ $kbk['icon'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[#161A30] truncate">{{ $kbk['name'] }}</span>
                                <span class="text-[11px] text-[#5B50E5] font-extrabold whitespace-nowrap">{{ $kbk['mk_count'] }} MK Pilihan</span>
                            </div>
                            <p class="text-xs text-[#464555] mt-0.5 line-clamp-1">{{ $kbk['desc'] }}</p>
                            <div class="flex items-center gap-1.5 mt-2 text-[11px] text-[#8A94A6] font-medium">
                                <span class="material-symbols-outlined text-[14px]">person</span>
                                <span>Koordinator: {{ $kbk['coordinator'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Academic Note Box -->
                <div class="mt-4 p-3 rounded-xl bg-[#f4f2ff] flex items-center gap-2.5 border border-[#c8c4d7]/40">
                    <span class="material-symbols-outlined text-[#5B50E5] text-[18px] flex-shrink-0">info</span>
                    <span class="text-xs text-[#464555]">
                        Mahasiswa diperkenankan mengambil mata kuliah pilihan lintas KBK dengan persetujuan Dosen Penasihat Akademik (PA).
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Silabus & RPS Viewer -->
<div id="syllabusModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative border border-[#E5E7EB] animate-in fade-in zoom-in-95 duration-200">
        <!-- Close Button -->
        <button onclick="closeSyllabusModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-[#F4F5F9] hover:bg-[#EEF2FF] text-[#464555] hover:text-[#5B50E5] flex items-center justify-center transition-all">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <span id="modalKode" class="px-2.5 py-1 rounded-lg bg-[#ececff] text-[#5B50E5] font-mono font-bold text-xs">
                -
            </span>
            <span id="modalSks" class="text-xs font-semibold text-[#8A94A6]">
                -
            </span>
        </div>

        <h3 id="modalNama" class="text-xl font-extrabold text-[#161A30] mb-2">
            Mata Kuliah
        </h3>

        <div class="p-4 rounded-xl bg-[#F9FAFC] border border-[#E5E7EB] mb-4">
            <h4 class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider mb-1">Deskripsi & Capaian Pembelajaran (CPMK)</h4>
            <p id="modalSilabus" class="text-xs sm:text-sm text-[#464555] leading-relaxed">
                Silabus mata kuliah ini mencakup arsitektur modern...
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-5 text-xs">
            <div class="p-3 rounded-xl bg-[#F4F2FF]">
                <div class="text-[11px] text-[#8A94A6] font-semibold">Koordinator Dosen</div>
                <div id="modalKoordinator" class="font-bold text-[#161A30] mt-0.5">-</div>
            </div>
            <div class="p-3 rounded-xl bg-[#F4F2FF]">
                <div class="text-[11px] text-[#8A94A6] font-semibold">Laboratorium / Ruang</div>
                <div id="modalRuang" class="font-bold text-[#161A30] mt-0.5">-</div>
            </div>
            <div class="p-3 rounded-xl bg-[#F4F2FF] col-span-2">
                <div class="text-[11px] text-[#8A94A6] font-semibold">Prasyarat Kelulusan</div>
                <div id="modalPrasyarat" class="font-bold text-[#161A30] mt-0.5">-</div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E5E7EB]">
            <button onclick="closeSyllabusModal()" class="px-4 py-2 rounded-xl bg-[#F4F5F9] text-[#464555] font-bold text-xs hover:bg-[#E5E7EB] transition-all">
                Tutup
            </button>
            <button onclick="alert('Mengunduh Rencana Pembelajaran Semester (RPS) format PDF...'); closeSyllabusModal();" class="px-4 py-2 rounded-xl bg-[#5B50E5] text-white font-bold text-xs hover:bg-[#4233cc] shadow-md shadow-[#5B50E5]/25 flex items-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-[16px]">download</span>
                <span>Unduh Dokumen RPS (.pdf)</span>
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live Search Filter
    const searchInput = document.getElementById('courseSearchInput');
    const typeFilter = document.getElementById('courseTypeFilter');
    const kbkFilter = document.getElementById('courseKbkFilter');
    let currentSemester = 'all';

    let currentPage = 1;
    const PAGE_SIZE = 10;
    let matchingRows = [];
    let matchingCards = [];

    function renderPaginationControls(containerId, totalPages) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';

        if (totalPages <= 1) return;

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = `w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors ${currentPage === 1 ? 'bg-[#F9FAFC] text-[#8A94A6] opacity-40 cursor-not-allowed' : 'bg-[#F4F5F9] text-[#161A30] hover:bg-[#EEF2FF] hover:text-[#5B50E5]'}`;
        prevBtn.disabled = currentPage === 1;
        prevBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">chevron_left</span>';
        prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderPage(); } };
        container.appendChild(prevBtn);

        // Page buttons
        for (let p = 1; p <= totalPages; p++) {
            if (totalPages > 7) {
                if (p > 1 && p < totalPages && (p < currentPage - 1 || p > currentPage + 1)) {
                    if (p === 2 || p === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.className = 'px-1 text-[#8A94A6] text-xs';
                        dots.textContent = '...';
                        container.appendChild(dots);
                    }
                    continue;
                }
            }
            const pageBtn = document.createElement('button');
            pageBtn.type = 'button';
            pageBtn.className = `w-8 h-8 rounded-lg text-xs font-bold flex items-center justify-center transition-all ${p === currentPage ? 'bg-[#5B50E5] text-white shadow-sm' : 'bg-[#F9FAFC] hover:bg-[#EEF2FF] text-[#464555] hover:text-[#5B50E5]'}`;
            pageBtn.textContent = p;
            pageBtn.onclick = () => { currentPage = p; renderPage(); };
            container.appendChild(pageBtn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = `w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors ${currentPage === totalPages ? 'bg-[#F9FAFC] text-[#8A94A6] opacity-40 cursor-not-allowed' : 'bg-[#F4F5F9] text-[#161A30] hover:bg-[#EEF2FF] hover:text-[#5B50E5]'}`;
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">chevron_right</span>';
        nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderPage(); } };
        container.appendChild(nextBtn);
    }

    function renderPage() {
        const total = matchingRows.length;
        const totalPages = Math.ceil(total / PAGE_SIZE) || 1;
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * PAGE_SIZE;
        const endIdx = startIdx + PAGE_SIZE;

        // Hide all rows & cards first
        document.querySelectorAll('.course-row').forEach(r => r.style.display = 'none');
        document.querySelectorAll('.course-card').forEach(c => c.style.display = 'none');

        // Show only current page slice
        matchingRows.slice(startIdx, endIdx).forEach(r => r.style.display = '');
        matchingCards.slice(startIdx, endIdx).forEach(c => c.style.display = 'flex');

        const displayStart = total === 0 ? 0 : startIdx + 1;
        const displayEnd = Math.min(endIdx, total);
        const infoText = `Menampilkan ${displayStart}-${displayEnd} dari total ${total} mata kuliah`;

        const infoEl = document.getElementById('coursePaginationInfo');
        if (infoEl) infoEl.textContent = infoText;
        const gridInfoEl = document.getElementById('gridPaginationInfo');
        if (gridInfoEl) gridInfoEl.textContent = infoText;

        const countLabel = document.getElementById('courseCountLabel');
        if (countLabel) countLabel.textContent = `Menampilkan ${total} mata kuliah`;

        renderPaginationControls('coursePaginationControls', totalPages);
        renderPaginationControls('gridPaginationControls', totalPages);
    }

    function applyFilters() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedType = typeFilter ? typeFilter.value : 'all';
        const selectedKbk = kbkFilter ? kbkFilter.value : 'all';

        const rows = Array.from(document.querySelectorAll('.course-row'));
        const cards = Array.from(document.querySelectorAll('.course-card'));

        function matchItem(item) {
            const kode = item.getAttribute('data-kode') || '';
            const nama = item.getAttribute('data-nama') || '';
            const desc = item.getAttribute('data-deskripsi') || '';
            const sem = item.getAttribute('data-semester') || '';
            const tipe = item.getAttribute('data-tipe') || '';
            const kbk = item.getAttribute('data-kbk') || '';

            const matchSearch = !searchTerm || kode.includes(searchTerm) || nama.includes(searchTerm) || desc.includes(searchTerm);
            const matchType = (selectedType === 'all') || (tipe === selectedType);
            const matchKbk = (selectedKbk === 'all') || (kbk === selectedKbk);

            let matchSem = true;
            if (currentSemester === 'pilihan') {
                matchSem = (tipe === 'pilihan');
            } else if (currentSemester !== 'all') {
                matchSem = (sem === currentSemester);
            }

            return matchSearch && matchType && matchKbk && matchSem;
        }

        matchingRows = rows.filter(r => matchItem(r));
        matchingCards = cards.filter(c => matchItem(c));

        currentPage = 1;
        renderPage();
    }

    // Initial load pagination
    applyFilters();

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (typeFilter) typeFilter.addEventListener('change', applyFilters);
    if (kbkFilter) kbkFilter.addEventListener('change', applyFilters);

    // Semester Tabs Filter
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => {
                t.classList.remove('bg-[#5B50E5]', 'text-white', 'shadow-md', 'shadow-[#5B50E5]/25');
                t.classList.add('bg-[#F4F5F9]', 'text-[#464555]');
            });
            this.classList.remove('bg-[#F4F5F9]', 'text-[#464555]');
            this.classList.add('bg-[#5B50E5]', 'text-white', 'shadow-md', 'shadow-[#5B50E5]/25');

            currentSemester = this.getAttribute('data-semester');
            applyFilters();
        });
    });

    // Toggle Table vs Grid View
    const viewTableBtn = document.getElementById('viewTableBtn');
    const viewGridBtn = document.getElementById('viewGridBtn');
    const tableViewContainer = document.getElementById('tableViewContainer');
    const gridViewContainer = document.getElementById('gridViewContainer');

    if (viewTableBtn && viewGridBtn && tableViewContainer && gridViewContainer) {
        viewTableBtn.addEventListener('click', () => {
            viewTableBtn.classList.add('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            viewTableBtn.classList.remove('text-[#8A94A6]');
            viewGridBtn.classList.remove('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            viewGridBtn.classList.add('text-[#8A94A6]');

            tableViewContainer.classList.remove('hidden');
            gridViewContainer.classList.add('hidden');
        });

        viewGridBtn.addEventListener('click', () => {
            viewGridBtn.classList.add('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            viewGridBtn.classList.remove('text-[#8A94A6]');
            viewTableBtn.classList.remove('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            viewTableBtn.classList.add('text-[#8A94A6]');

            gridViewContainer.classList.remove('hidden');
            tableViewContainer.classList.add('hidden');
        });
    }

    // Modal Silabus / RPS Functions
    function openSyllabusModal(kode, nama, sks, sem, silabus, koordinator, ruang, prasyarat) {
        document.getElementById('modalKode').textContent = kode;
        document.getElementById('modalNama').textContent = nama;
        document.getElementById('modalSks').textContent = `${sks} SKS · Semester ${sem}`;
        document.getElementById('modalSilabus').textContent = silabus;
        document.getElementById('modalKoordinator').textContent = koordinator;
        document.getElementById('modalRuang').textContent = ruang;
        document.getElementById('modalPrasyarat').textContent = prasyarat;

        const modal = document.getElementById('syllabusModal');
        modal.classList.remove('hidden');
    }

    function closeSyllabusModal() {
        const modal = document.getElementById('syllabusModal');
        modal.classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('syllabusModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeSyllabusModal();
        }
    });

    // Export Table to CSV
    function exportToCSV() {
        let csv = 'Kode MK,Nama Mata Kuliah,SKS,Semester,Laboratorium/Ruang,Koordinator\n';
        document.querySelectorAll('.course-row').forEach(row => {
            if (row.style.display !== 'none') {
                const kode = row.querySelector('[data-kode]') ? row.getAttribute('data-kode').toUpperCase() : '';
                const nama = row.querySelector('.text-sm')?.textContent.trim() || '';
                const sem = row.getAttribute('data-semester') || '';
                csv += `"${kode}","${nama}","${sem}"\n`;
            }
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Kurikulum_Teknik_Informatika_Universitas_Surakarta.csv';
        a.click();
    }
</script>
@endpush
