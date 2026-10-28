@extends('layouts.app')

@section('title', 'Daftar Dosen Pengampu - SI Jadwal TI')

@section('content')
<div class="flex flex-col w-full pb-12 gap-y-6">

    <!-- Top Navigation & Header Row -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mt-1">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Akademik</span>
                <span class="material-symbols-outlined text-[14px] text-[#8A94A6]">chevron_right</span>
                <span class="text-[11px] font-bold text-[#5B50E5]">Daftar Pengajar & Tenaga Pendidik</span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-[#161A30] tracking-tight">Dosen Pengampu</h1>
            <p class="text-xs sm:text-sm text-[#464555] flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-[#161A30]">Departemen Teknik Informatika</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#c8c4d7]"></span>
                <span>Fakultas Teknik</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#c8c4d7]"></span>
                <span class="px-2.5 py-0.5 rounded-md bg-[#ececff] text-xs text-[#5B50E5] font-semibold">T.A. {{ $activeYear->tahun ?? '2026/2027' }} {{ ucfirst($activeYear->semester ?? 'ganjil') }}</span>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] hover:bg-[#ececff] transition-all shadow-sm border border-[#E5E7EB] text-xs font-bold" type="button">
                <span class="material-symbols-outlined text-[18px] text-[#464555]">print</span>
                <span>Cetak Daftar (.pdf)</span>
            </button>
            <button onclick="exportLecturersCSV()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white text-[#161A30] hover:bg-[#ececff] transition-all shadow-sm border border-[#E5E7EB] text-xs font-bold" type="button">
                <span class="material-symbols-outlined text-[18px] text-[#059669]">table_view</span>
                <span>Ekspor Excel (.xlsx)</span>
            </button>
            <a href="{{ route('admin.lecturers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#5B50E5] text-white hover:bg-[#4233cc] transition-all shadow-md shadow-[#5B50E5]/25 text-xs font-bold">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Kelola Dosen (Admin)</span>
            </a>
        </div>
    </div>

    <!-- Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Total Dosen Terdata</span>
                <div class="w-10 h-10 rounded-xl bg-[#EEF2FF] flex items-center justify-center text-[#4F46E5] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">groups</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $totalLecturers }} Dosen</span>
                <p class="text-xs text-[#464555] mt-1">Memiliki NIDN & akun</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#059669] font-semibold">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                <span>PDDIKTI Terverifikasi</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Total Jadwal Mengajar</span>
                <div class="w-10 h-10 rounded-xl bg-[#ececff] flex items-center justify-center text-[#4233cc] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">calendar_month</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $totalSchedules }} Kelas</span>
                <p class="text-xs text-[#464555] mt-1">Shift pagi & sore</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#5B50E5] font-semibold">
                <span class="material-symbols-outlined text-[16px]">task_alt</span>
                <span>Terplot Bebas Tabrakan</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Bidang Keahlian / Lab</span>
                <div class="w-10 h-10 rounded-xl bg-[#ECFDF5] flex items-center justify-center text-[#059669] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">science</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">{{ $labs->count() }} Lab</span>
                <p class="text-xs text-[#464555] mt-1">RPL · Jaringan · AI · Siber</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#059669] font-semibold">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                <span>Akreditasi unggul</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between group hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Akses Portal Mandiri</span>
                <div class="w-10 h-10 rounded-xl bg-[#e3dfff] flex items-center justify-center text-[#4233cc] group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[22px]">key</span>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-3xl font-extrabold text-[#161A30] tracking-tight">Login NIDN</span>
                <p class="text-xs text-[#464555] mt-1">Akun dikelola administrator</p>
            </div>
            <div class="flex items-center gap-1.5 mt-3 text-xs text-[#5B50E5] font-semibold">
                <a href="{{ route('login') }}" class="hover:underline flex items-center gap-1">
                    <span>Masuk sebagai Dosen</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#E5E7EB]/80 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#8A94A6] text-[20px]">search</span>
            <input class="w-full pl-10 pr-4 py-2.5 rounded-full bg-[#F4F5F9] text-xs sm:text-sm text-[#161A30] placeholder:text-[#8A94A6] focus:outline-none focus:ring-2 focus:ring-[#5B50E5]/30 focus:bg-white transition-all" 
                   id="lecturerSearchInput" 
                   placeholder="Cari nama dosen, NIDN, atau bidang keahlian (contoh: Sukoco, 0015066401, Jaringan)..." 
                   type="text"/>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <div class="relative">
                <select id="labFilter" class="appearance-none pl-3.5 pr-8 py-2 rounded-full bg-[#F4F5F9] text-xs font-semibold text-[#161A30] focus:outline-none focus:ring-2 focus:ring-[#5B50E5]/30 cursor-pointer transition-all">
                    <option value="all">Semua Bidang Keahlian / Lab</option>
                    @foreach($labs as $lab)
                        <option value="{{ $lab }}">{{ $lab }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-[18px] text-[#8A94A6] pointer-events-none">expand_more</span>
            </div>

            <!-- Toggle View Mode -->
            <div class="flex items-center bg-[#F4F5F9] p-1 rounded-full">
                <button class="px-3 py-1.5 rounded-full bg-white text-[#5B50E5] font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all" id="btnViewGrid" type="button">
                    <span class="material-symbols-outlined text-[16px]">grid_view</span>
                    <span>Kartu</span>
                </button>
                <button class="px-3 py-1.5 rounded-full text-[#8A94A6] hover:text-[#161A30] font-bold text-xs flex items-center gap-1.5 transition-all" id="btnViewTable" type="button">
                    <span class="material-symbols-outlined text-[16px]">table_rows</span>
                    <span>Tabel</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 1. GRID CARDS VIEW (DEFAULT) -->
    <div id="gridContainer" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($lecturers as $l)
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB]/80 flex flex-col justify-between hover:shadow-lg hover:border-[#5B50E5]/40 transition-all group lecturer-card"
             data-nama="{{ strtolower($l->nama) }}"
             data-nidn="{{ $l->nidn }}"
             data-lab="{{ $l->laboratorium }}">
            
            <div>
                <!-- Top Row: Avatar & NIDN badge -->
                <div class="flex items-start gap-4 mb-4">
                    <div class="relative">
                        <img src="{{ $l->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $l->nama }}" 
                             class="w-14 h-14 rounded-2xl object-cover ring-2 ring-[#ececff] group-hover:scale-105 transition-transform flex-shrink-0 shadow-sm">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-[#059669] border-2 border-white rounded-full"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <span class="px-2.5 py-0.5 rounded-md bg-[#ececff] text-[#5B50E5] font-mono text-[11px] font-bold inline-block mb-1">
                            NIDN: {{ $l->nidn }}
                        </span>
                        <h3 class="font-extrabold text-sm text-[#161A30] group-hover:text-[#5B50E5] transition-colors leading-snug line-clamp-2">
                            {{ $l->nama }}
                        </h3>
                        <p class="text-[11px] text-[#8A94A6] font-medium mt-0.5">
                            {{ $l->laboratorium ?? 'Dosen Teknik Informatika' }}
                        </p>
                    </div>
                </div>

                <!-- Teaching info & tags -->
                <div class="p-3 rounded-xl bg-[#F9FAFC] border border-[#E5E7EB]/60 flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-[#5B50E5]">calendar_month</span>
                        <span class="text-xs font-semibold text-[#161A30]">Beban Mengajar</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full {{ $l->schedules_count > 0 ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#F4F2FF] text-[#8A94A6]' }} text-xs font-bold">
                        {{ $l->schedules_count }} Jadwal Kuliah
                    </span>
                </div>
            </div>

            <!-- Card Footer: Contact & Modal trigger -->
            <div class="pt-3 border-t border-[#E5E7EB]/60 flex items-center justify-between text-xs">
                <span class="text-[#8A94A6] truncate max-w-[160px] text-[11px]" title="{{ $l->email }}">
                    {{ $l->email }}
                </span>
                
                <button onclick="openLecturerScheduleModal('{{ addslashes($l->nama) }}', '{{ $l->nidn }}', '{{ addslashes($l->laboratorium) }}', {{ json_encode($l->schedules->map(function($s) {
                    return [
                        'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'][$s->hari - 1] ?? 'Hari',
                        'jam' => substr($s->jam_mulai, 0, 5) . ' - ' . substr($s->jam_selesai, 0, 5),
                        'shift' => ucfirst($s->shift),
                        'kode' => $s->course?->kode ?? '-',
                        'mata_kuliah' => $s->course?->nama ?? '-',
                        'kelas' => $s->kelas,
                        'ruang' => $s->room?->nama ?? '-',
                    ];
                })) }})" 
                        class="px-3 py-1.5 rounded-xl bg-[#EEF2FF] text-[#5B50E5] hover:bg-[#5B50E5] hover:text-white font-bold text-xs transition-all shadow-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">event</span>
                    <span>Lihat Jadwal</span>
                </button>
            </div>
        </div>
        @endforeach

        <!-- Grid Footer / Pagination -->
        <div id="gridLecturerPagination" class="col-span-full px-6 py-4 bg-white rounded-2xl shadow-sm border border-[#E5E7EB]/80 flex flex-col sm:flex-row items-center justify-between gap-4 mt-2">
            <div class="flex items-center gap-2 text-[#464555] text-xs">
                <span id="gridLecturerPaginationInfo">Menampilkan 1-10 dari total {{ $lecturers->count() }} dosen</span>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap" id="gridLecturerPaginationControls">
            </div>
        </div>
    </div>

    <!-- 2. TABLE VIEW (HIDDEN BY DEFAULT) -->
    <div id="tableContainer" class="hidden bg-white rounded-2xl shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="lecturerTable">
                <thead>
                    <tr class="bg-[#F9FAFC] text-[#64748B] text-xs uppercase tracking-wider border-b border-[#E5E7EB]">
                        <th class="py-3 px-6 font-bold">Dosen Pengampu</th>
                        <th class="py-3 px-6 font-bold">NIDN</th>
                        <th class="py-3 px-6 font-bold">Laboratorium / Keahlian</th>
                        <th class="py-3 px-6 font-bold">Email</th>
                        <th class="py-3 px-6 font-bold text-center">Jumlah Kelas</th>
                        <th class="py-3 px-6 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]/50 text-xs">
                    @foreach($lecturers as $l)
                    <tr class="hover:bg-[#F9FAFC] transition-colors lecturer-row"
                        data-nama="{{ strtolower($l->nama) }}"
                        data-nidn="{{ $l->nidn }}"
                        data-lab="{{ $l->laboratorium }}">
                        
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <img src="{{ $l->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}" 
                                     alt="{{ $l->nama }}" 
                                     class="w-9 h-9 rounded-full object-cover ring-1 ring-[#ececff] flex-shrink-0">
                                <div class="flex flex-col">
                                    <span class="font-bold text-[#161A30] text-sm">{{ $l->nama }}</span>
                                    <span class="text-xs text-[#8A94A6]">Gelar: {{ $l->gelar ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-6 font-mono font-bold text-[#5B50E5]">
                            {{ $l->nidn }}
                        </td>

                        <td class="py-4 px-6 text-[#464555]">
                            <span class="px-2.5 py-1 rounded-lg bg-[#F4F2FF] text-xs font-medium text-[#464555]">
                                {{ $l->laboratorium ?? '-' }}
                            </span>
                        </td>

                        <td class="py-4 px-6 text-[#464555]">
                            {{ $l->email }}
                        </td>

                        <td class="py-4 px-6 text-center">
                            <span class="px-2.5 py-0.5 rounded-full {{ $l->schedules_count > 0 ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#F4F2FF] text-[#8A94A6]' }} text-xs font-bold">
                                {{ $l->schedules_count }}
                            </span>
                        </td>

                        <td class="py-4 px-6 text-right">
                            <button onclick="openLecturerScheduleModal('{{ addslashes($l->nama) }}', '{{ $l->nidn }}', '{{ addslashes($l->laboratorium) }}', {{ json_encode($l->schedules->map(function($s) {
                                return [
                                    'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'][$s->hari - 1] ?? 'Hari',
                                    'jam' => substr($s->jam_mulai, 0, 5) . ' - ' . substr($s->jam_selesai, 0, 5),
                                    'shift' => ucfirst($s->shift),
                                    'kode' => $s->course?->kode ?? '-',
                                    'mata_kuliah' => $s->course?->nama ?? '-',
                                    'kelas' => $s->kelas,
                                    'ruang' => $s->room?->nama ?? '-',
                                ];
                            })) }})" 
                                    class="p-2 rounded-xl bg-[#EEF2FF] text-[#5B50E5] hover:bg-[#5B50E5] hover:text-white transition-all shadow-xs" 
                                    title="Lihat Jadwal Mengajar">
                                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="px-6 py-4 bg-white border-t border-[#E5E7EB]/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-[#464555] text-xs">
                <span id="tableLecturerPaginationInfo">Menampilkan 1-10 dari total {{ $lecturers->count() }} dosen</span>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap" id="tableLecturerPaginationControls">
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Jadwal Mengajar Dosen -->
<div id="lecturerScheduleModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative border border-[#E5E7EB] max-h-[90vh] flex flex-col animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Top Bar -->
        <div class="flex items-start justify-between pb-4 border-b border-[#E5E7EB]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span id="modalLecturerNidn" class="px-2.5 py-0.5 rounded-md bg-[#ececff] text-[#5B50E5] font-mono text-[11px] font-bold">
                        NIDN
                    </span>
                    <span id="modalLecturerLab" class="text-xs text-[#8A94A6]">
                        Laboratorium
                    </span>
                </div>
                <h3 id="modalLecturerName" class="text-xl font-extrabold text-[#161A30]">
                    Nama Dosen
                </h3>
            </div>

            <button onclick="closeLecturerScheduleModal()" class="w-8 h-8 rounded-full bg-[#F4F5F9] hover:bg-[#EEF2FF] text-[#464555] hover:text-[#5B50E5] flex items-center justify-center transition-all">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Schedule Items List -->
        <div class="overflow-y-auto py-4 flex-1 space-y-3" id="modalScheduleList">
            <!-- Dynamic Schedule Rows -->
        </div>

        <!-- Modal Footer -->
        <div class="pt-4 border-t border-[#E5E7EB] flex items-center justify-between">
            <span class="text-xs text-[#8A94A6]" id="modalScheduleCount">
                0 Jadwal Perkuliahan
            </span>
            <button onclick="closeLecturerScheduleModal()" class="px-4 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold hover:bg-[#4233cc] transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('lecturerSearchInput');
    const labFilter = document.getElementById('labFilter');

    let currentLecPage = 1;
    const LEC_PAGE_SIZE = 10;
    let matchingCards = [];
    let matchingRows = [];

    function renderLecPaginationControls(containerId, totalPages) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';

        if (totalPages <= 1) return;

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = `w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors ${currentLecPage === 1 ? 'bg-[#F9FAFC] text-[#8A94A6] opacity-40 cursor-not-allowed' : 'bg-[#F4F5F9] text-[#161A30] hover:bg-[#EEF2FF] hover:text-[#5B50E5]'}`;
        prevBtn.disabled = currentLecPage === 1;
        prevBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">chevron_left</span>';
        prevBtn.onclick = () => { if (currentLecPage > 1) { currentLecPage--; renderLecPage(); } };
        container.appendChild(prevBtn);

        // Page buttons
        for (let p = 1; p <= totalPages; p++) {
            const pageBtn = document.createElement('button');
            pageBtn.type = 'button';
            pageBtn.className = `w-8 h-8 rounded-lg text-xs font-bold flex items-center justify-center transition-all ${p === currentLecPage ? 'bg-[#5B50E5] text-white shadow-sm' : 'bg-[#F9FAFC] hover:bg-[#EEF2FF] text-[#464555] hover:text-[#5B50E5]'}`;
            pageBtn.textContent = p;
            pageBtn.onclick = () => { currentLecPage = p; renderLecPage(); };
            container.appendChild(pageBtn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = `w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors ${currentLecPage === totalPages ? 'bg-[#F9FAFC] text-[#8A94A6] opacity-40 cursor-not-allowed' : 'bg-[#F4F5F9] text-[#161A30] hover:bg-[#EEF2FF] hover:text-[#5B50E5]'}`;
        nextBtn.disabled = currentLecPage === totalPages;
        nextBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">chevron_right</span>';
        nextBtn.onclick = () => { if (currentLecPage < totalPages) { currentLecPage++; renderLecPage(); } };
        container.appendChild(nextBtn);
    }

    function renderLecPage() {
        const total = matchingCards.length;
        const totalPages = Math.ceil(total / LEC_PAGE_SIZE) || 1;
        if (currentLecPage > totalPages) currentLecPage = totalPages;
        if (currentLecPage < 1) currentLecPage = 1;

        const startIdx = (currentLecPage - 1) * LEC_PAGE_SIZE;
        const endIdx = startIdx + LEC_PAGE_SIZE;

        // Hide all first
        document.querySelectorAll('.lecturer-card').forEach(c => c.style.display = 'none');
        document.querySelectorAll('.lecturer-row').forEach(r => r.style.display = 'none');

        // Show page slice
        matchingCards.slice(startIdx, endIdx).forEach(c => c.style.display = 'flex');
        matchingRows.slice(startIdx, endIdx).forEach(r => r.style.display = '');

        const displayStart = total === 0 ? 0 : startIdx + 1;
        const displayEnd = Math.min(endIdx, total);
        const infoText = `Menampilkan ${displayStart}-${displayEnd} dari total ${total} dosen`;

        const gridInfo = document.getElementById('gridLecturerPaginationInfo');
        if (gridInfo) gridInfo.textContent = infoText;
        const tableInfo = document.getElementById('tableLecturerPaginationInfo');
        if (tableInfo) tableInfo.textContent = infoText;

        renderLecPaginationControls('gridLecturerPaginationControls', totalPages);
        renderLecPaginationControls('tableLecturerPaginationControls', totalPages);
    }

    function filterLecturers() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedLab = labFilter ? labFilter.value : 'all';

        const cards = Array.from(document.querySelectorAll('.lecturer-card'));
        const rows = Array.from(document.querySelectorAll('.lecturer-row'));

        function match(item) {
            const nama = item.getAttribute('data-nama') || '';
            const nidn = item.getAttribute('data-nidn') || '';
            const lab = item.getAttribute('data-lab') || '';

            const matchQuery = !query || nama.includes(query) || nidn.includes(query) || lab.toLowerCase().includes(query);
            const matchLab = (selectedLab === 'all') || (lab === selectedLab);

            return matchQuery && matchLab;
        }

        matchingCards = cards.filter(c => match(c));
        matchingRows = rows.filter(r => match(r));

        currentLecPage = 1;
        renderLecPage();
    }

    // Initial load pagination
    filterLecturers();

    if (searchInput) searchInput.addEventListener('input', filterLecturers);
    if (labFilter) labFilter.addEventListener('change', filterLecturers);

    // Toggle Grid / Table
    const btnViewGrid = document.getElementById('btnViewGrid');
    const btnViewTable = document.getElementById('btnViewTable');
    const gridContainer = document.getElementById('gridContainer');
    const tableContainer = document.getElementById('tableContainer');

    if (btnViewGrid && btnViewTable && gridContainer && tableContainer) {
        btnViewGrid.addEventListener('click', () => {
            btnViewGrid.classList.add('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            btnViewGrid.classList.remove('text-[#8A94A6]');
            btnViewTable.classList.remove('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            btnViewTable.classList.add('text-[#8A94A6]');

            gridContainer.classList.remove('hidden');
            tableContainer.classList.add('hidden');
        });

        btnViewTable.addEventListener('click', () => {
            btnViewTable.classList.add('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            btnViewTable.classList.remove('text-[#8A94A6]');
            btnViewGrid.classList.remove('bg-white', 'text-[#5B50E5]', 'shadow-sm');
            btnViewGrid.classList.add('text-[#8A94A6]');

            tableContainer.classList.remove('hidden');
            gridContainer.classList.add('hidden');
        });
    }

    // Modal Jadwal Mengajar Dosen
    function openLecturerScheduleModal(nama, nidn, lab, schedules) {
        document.getElementById('modalLecturerName').textContent = nama;
        document.getElementById('modalLecturerNidn').textContent = 'NIDN: ' + nidn;
        document.getElementById('modalLecturerLab').textContent = lab || 'Teknik Informatika';
        document.getElementById('modalScheduleCount').textContent = schedules.length + ' Jadwal Perkuliahan Gasal 2026/2027';

        const listContainer = document.getElementById('modalScheduleList');
        listContainer.innerHTML = '';

        if (!schedules || schedules.length === 0) {
            listContainer.innerHTML = `
                <div class="text-center py-8 text-[#8A94A6] text-xs">
                    Belum ada plotting jadwal perkuliahan aktif untuk dosen ini.
                </div>
            `;
        } else {
            schedules.forEach(item => {
                const row = document.createElement('div');
                row.className = 'p-3.5 rounded-2xl bg-[#F9FAFC] border border-[#E5E7EB] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs';
                row.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="px-2.5 py-1 rounded-xl ${item.shift === 'Pagi' ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#EEF2FF] text-[#4F46E5]'} font-bold text-[11px] whitespace-nowrap">
                            ${item.hari}, ${item.jam}
                        </div>
                        <div>
                            <div class="font-bold text-[#161A30] text-sm">${item.mata_kuliah}</div>
                            <div class="text-[#8A94A6] text-[11px] mt-0.5">Kode: <span class="font-mono text-[#5B50E5] font-semibold">${item.kode}</span> · Kelas: <span class="font-semibold text-[#161A30]">${item.kelas}</span></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-[#464555] bg-white px-3 py-1.5 rounded-xl border border-[#E5E7EB] self-start sm:self-auto">
                        <span class="material-symbols-outlined text-[16px] text-[#5B50E5]">meeting_room</span>
                        <span class="font-semibold">${item.ruang}</span>
                    </div>
                `;
                listContainer.appendChild(row);
            });
        }

        document.getElementById('lecturerScheduleModal').classList.remove('hidden');
    }

    function closeLecturerScheduleModal() {
        document.getElementById('lecturerScheduleModal').classList.add('hidden');
    }

    document.getElementById('lecturerScheduleModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeLecturerScheduleModal();
    });

    function exportLecturersCSV() {
        let csv = 'NIDN,Nama Dosen,Laboratorium,Email\n';
        document.querySelectorAll('.lecturer-row').forEach(row => {
            const nama = row.querySelector('.text-sm')?.textContent.trim() || '';
            const nidn = row.getAttribute('data-nidn') || '';
            const lab = row.getAttribute('data-lab') || '';
            csv += `"${nidn}","${nama}","${lab}"\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Daftar_Dosen_TI_Universitas_Surakarta_2026_2027.csv';
        a.click();
    }
</script>
@endpush
