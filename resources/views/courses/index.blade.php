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
            <h1 class="text-xl lg:text-2xl font-extrabold text-[#161A30] tracking-tight">Mata Kuliah</h1>
            <p class="text-xs sm:text-sm text-[#464555] flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-[#161A30]">Departemen Teknik Informatika</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#c8c4d7]"></span>
                <span>Fakultas Teknik</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#c8c4d7]"></span>
                <span class="px-2.5 py-0.5 rounded-md bg-[#ececff] text-xs text-[#5B50E5] font-semibold">{{ $stats['total_sks'] }} SKS</span>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('mata-kuliah.export.pdf', request()->query()) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] hover:bg-[#ececff] transition-all shadow-sm border border-[#E5E7EB] text-xs font-bold">
                <span class="material-symbols-outlined text-[18px] text-[#464555]">picture_as_pdf</span>
                <span>Unduh PDF</span>
            </a>
            <a href="{{ route('mata-kuliah.export', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] hover:bg-[#ececff] transition-all shadow-sm border border-[#E5E7EB] text-xs font-bold">
                <span class="material-symbols-outlined text-[18px] text-[#059669]">table_view</span>
                <span>Ekspor Excel</span>
            </a>
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
                <span class="text-2xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['total_mk'] }} MK</span>
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
                <span class="text-2xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['total_sks'] }} SKS</span>
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
                <span class="text-2xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['sks_semester_aktif'] }} SKS</span>
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
                <span class="text-2xl font-extrabold text-[#161A30] tracking-tight">{{ $stats['sks_lab'] }} SKS ({{ $stats['percent_lab'] }}%)</span>
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
                        <option value="all">Semua Kategori</option>
                        @foreach($kbkOptions as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
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
        <div class="flex flex-wrap items-center gap-2 pt-1 select-none" id="semesterPills">
            <button data-semester="all" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/25 transition-all" type="button">
                Semua <span class="ml-1 opacity-80 font-normal">({{ $enrichedCourses->count() }})</span>
            </button>
            @for($semester = 1; $semester <= 8; $semester++)
                <button data-semester="{{ $semester }}" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                    Semester {{ $semester }} <span class="ml-1 text-[#8A94A6] font-normal">({{ $semesterSks[$semester] ?? 0 }} SKS)</span>
                </button>
            @endfor
            <button data-semester="pilihan" class="filter-tab px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#F4F5F9] text-[#464555] hover:bg-[#ececff] transition-all" type="button">
                Mata Kuliah Pilihan <span class="ml-1 text-[#8A94A6] font-normal">({{ $stats['mk_pilihan'] }})</span>
            </button>
        </div>
    </div>

    <!-- TABLE VIEW CONTAINER -->
    <div id="tableViewContainer" class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <!-- Table Header Bar -->
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-[#F9FAFC] border-b border-[#E5E7EB]/80">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-[#5B50E5]"></div>
                <span class="text-sm font-extrabold text-[#161A30]">Katalog Mata Kuliah</span>
                <span class="text-xs text-[#8A94A6]" id="courseCountLabel">Menampilkan {{ $enrichedCourses->count() }} mata kuliah pilihan & wajib</span>
            </div>
        </div>

        <!-- Table Responsive Wrapper -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="courseTable">
                <thead>
                    <tr class="bg-[#F9FAFC] text-[#8A94A6] text-[11px] uppercase tracking-wider border-b border-[#E5E7EB]">
                        <th class="py-3 px-6 font-bold">Kode MK</th>
                        <th class="py-3 px-6 font-bold">Nama Mata Kuliah</th>
                        <th class="py-3 px-6 font-bold">Jenis</th>
                        <th class="py-3 px-6 font-bold">SKS Teori / Praktikum</th>
                        <th class="py-3 px-6 font-bold">Semester</th>
                        <th class="py-3 px-6 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]/50 text-xs">
                    @forelse($enrichedCourses as $c)
                    <tr class="hover:bg-[#F9FAFC] transition-colors group course-row"
                        data-kode="{{ strtolower($c->kode) }}"
                        data-nama="{{ strtolower($c->nama) }}"
                        data-semester="{{ $c->semester_ideal }}"
                        data-tipe="{{ $c->tipe }}"
                        data-kbk="{{ $c->meta->kbk }}">
                        
                        <!-- Kode MK -->
                        <td class="py-4 px-6 align-top">
                            <span class="px-2.5 py-1 rounded-lg bg-[#ececff] text-[#5B50E5] font-mono font-bold text-xs inline-block">
                                {{ $c->kode }}
                            </span>
                        </td>

                        <!-- Nama Mata Kuliah -->
                        <td class="py-4 px-6 align-top max-w-xs">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-[#161A30] group-hover:text-[#5B50E5] transition-colors">
                                    {{ $c->nama }}
                                </span>
                            </div>
                        </td>

                        <!-- Jenis Mata Kuliah -->
                        <td class="py-4 px-6 align-top whitespace-nowrap">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold {{ $c->tipe === 'pilihan' ? 'bg-emerald-50 text-emerald-700' : 'bg-[#EEF2FF] text-[#4F46E5]' }}">
                                {{ $c->tipe === 'pilihan' ? 'Pilihan' : 'Wajib' }}
                            </span>
                        </td>

                        <!-- SKS & Sifat -->
                        <td class="py-4 px-6 align-top whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-[#161A30]">{{ $c->meta->sks_teori }} / {{ $c->meta->sks_praktikum }} SKS</span>
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

                        <!-- Aksi -->
                        <td class="py-4 px-6 align-top text-right">
                            <div class="flex items-center justify-end gap-1.5">
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
                        <td colspan="6" class="text-center py-12 text-[#8A94A6]">
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
                </div>

                <div class="pt-3 border-t border-[#E5E7EB]/60 flex flex-col gap-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-[#161A30]">{{ $c->meta->sks_detail }}</span>
                        <span class="text-xs text-[#059669] font-semibold">{{ $c->meta->sifat }}</span>
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
            const sem = item.getAttribute('data-semester') || '';
            const tipe = item.getAttribute('data-tipe') || '';
            const kbk = item.getAttribute('data-kbk') || '';

            const matchSearch = !searchTerm || kode.includes(searchTerm) || nama.includes(searchTerm);
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

</script>
@endpush
