@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEF2F2] text-[#EF4444]">Panel Administrator</span>
                <h1 class="text-xl sm:text-2xl font-bold text-[#161A30]">Kelola Mata Kuliah</h1>
            </div>
            <p class="text-xs sm:text-sm text-[#8A94A6] mt-1">
                Kelola mata kuliah, SKS, dan tipe perkuliahan.
            </p>
        </div>

        <button type="button" onclick="openCreateModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5B50E5] text-white hover:bg-[#4F46E5] text-xs font-bold shadow-md shadow-[#5B50E5]/25 transition-all">
            <span class="material-symbols-outlined text-[18px]">add</span>
            <span>Tambah Mata Kuliah</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 mb-6 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative flex items-center min-w-[260px]">
                <span class="material-symbols-outlined absolute left-3 text-[#8A94A6] text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama mata kuliah..." 
                       class="w-full bg-[#F4F5F9] text-[#161A30] pl-9 pr-3 py-2 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <select name="tipe" onchange="this.form.submit()" class="bg-[#F4F5F9] text-[#161A30] px-3 py-2 rounded-xl text-xs outline-none cursor-pointer">
                <option value="all">Semua Tipe</option>
                <option value="wajib" {{ request('tipe') === 'wajib' ? 'selected' : '' }}>Wajib</option>
                <option value="pilihan" {{ request('tipe') === 'pilihan' ? 'selected' : '' }}>Pilihan</option>
            </select>

            <button type="submit" class="px-3 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold hover:bg-[#4F46E5]">
                Cari
            </button>
            <a href="{{ route('admin.courses.index') }}" class="px-3 py-2 rounded-xl bg-[#F4F5F9] text-[#464555] text-xs font-semibold hover:bg-[#E5E7EB]">
                Reset
            </a>
        </form>

        <span class="text-xs text-[#8A94A6] font-semibold">
            Menampilkan {{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }} dari {{ $courses->total() }} Mata Kuliah
        </span>
    </div>

    <!-- Table Container -->
    <div class="rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#F9FAFC] text-[#8A94A6] text-[11px] font-bold uppercase tracking-wider border-b border-[#E5E7EB]">
                        <th class="py-3.5 px-4">KODE MK</th>
                        <th class="py-3.5 px-4">NAMA MATA KULIAH</th>
                        <th class="py-3.5 px-4">TIPE</th>
                        <th class="py-3.5 px-4">BOBOT SKS</th>
                        <th class="py-3.5 px-4">SEMESTER IDEAL</th>
                        <th class="py-3.5 px-4">KATEGORI / LAB</th>
                        <th class="py-3.5 px-4 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($courses as $c)
                        <tr class="hover:bg-[#FBFBFC] transition-colors">
                            <td class="py-4 px-4 font-mono font-bold text-[#5B50E5]">
                                {{ $c->kode }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-[#161A30] text-[13px]">{{ $c->nama }}</div>
                                <div class="text-[11px] text-[#8A94A6]">{{ $c->deskripsi }}</div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $c->tipe === 'wajib' ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'bg-[#ECFDF5] text-[#059669]' }}">
                                    {{ ucfirst($c->tipe) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-[#161A30]">
                                {{ $c->sks }} SKS
                            </td>
                            <td class="py-4 px-4 text-[#464555]">
                                Semester {{ $c->semester_ideal ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-[#464555]">
                                {{ $c->kategori ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" onclick='openEditModal(@json($c))' 
                                            class="px-2.5 py-1.5 rounded-lg bg-[#F1F3F7] hover:bg-[#E2E8F0] text-[#475569] text-xs font-bold transition-all">
                                        Ubah
                                    </button>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $c->id) }}" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#EF4444] text-xs font-bold transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#8A94A6] text-xs">
                                Belum ada data mata kuliah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 px-2">
        {{ $courses->onEachSide(1)->links() }}
    </div>

</div>

<!-- MODAL FORM COURSE -->
<div id="courseModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg p-7 shadow-2xl relative">
        <div class="flex items-center justify-between pb-4 border-b border-[#F1F3F7] mb-5">
            <h3 class="text-base font-bold text-[#161A30]" id="modalTitle">Tambah Mata Kuliah</h3>
            <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-[#F4F5F9] hover:bg-[#E5E7EB] text-[#464555] flex items-center justify-center">✕</button>
        </div>

        <form id="courseForm" method="POST" action="{{ route('admin.courses.store') }}" class="space-y-4">
            @csrf
            <div id="methodField"></div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">Kode Mata Kuliah</label>
                <input type="text" name="kode" id="kode" required placeholder="Contoh kode mata kuliah" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">Nama Mata Kuliah</label>
                <input type="text" name="nama" id="nama" required placeholder="Contoh: Pemrograman Web Lanjut" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Tipe</label>
                    <select name="tipe" id="tipe" required class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        <option value="wajib">Wajib</option>
                        <option value="pilihan">Pilihan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Bobot SKS</label>
                    <input type="number" name="sks" id="sks" required min="1" max="6" value="3" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Semester Ideal</label>
                    <input type="number" name="semester_ideal" id="semester_ideal" min="1" max="8" value="5" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Kategori / Topik</label>
                    <input type="text" name="kategori" id="kategori" placeholder="Lab Pemrograman" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi" rows="2" placeholder="Deskripsi silabus perkuliahan..." class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F1F3F7]">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-[#F4F5F9] text-[#464555] text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#5B50E5] hover:bg-[#4F46E5] text-white text-xs font-bold shadow-md shadow-[#5B50E5]/25">Simpan Mata Kuliah</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('courseModal');
    const form = document.getElementById('courseForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openCreateModal() {
        modalTitle.textContent = 'Tambah Mata Kuliah';
        form.action = "{{ route('admin.courses.store') }}";
        methodField.innerHTML = '';
        form.reset();
        modal.classList.remove('hidden');
    }

    function openEditModal(c) {
        modalTitle.textContent = 'Ubah Mata Kuliah';
        form.action = "/admin/mata-kuliah/" + c.id;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('kode').value = c.kode;
        document.getElementById('nama').value = c.nama;
        document.getElementById('tipe').value = c.tipe;
        document.getElementById('sks').value = c.sks;
        document.getElementById('semester_ideal').value = c.semester_ideal || '';
        document.getElementById('kategori').value = c.kategori || '';
        document.getElementById('deskripsi').value = c.deskripsi || '';

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection
