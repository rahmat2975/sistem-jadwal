@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEF2F2] text-[#EF4444]">Panel Administrator</span>
                <h1 class="text-xl sm:text-2xl font-bold text-[#161A30]">Kelola Data Dosen Pengajar</h1>
            </div>
            <p class="text-xs sm:text-sm text-[#8A94A6] mt-1">
                Kelola profil dosen, NIDN, bidang, dan akun login.
            </p>
        </div>

        <button type="button" onclick="openCreateModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5B50E5] text-white hover:bg-[#4F46E5] text-xs font-bold shadow-md shadow-[#5B50E5]/25 transition-all">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            <span>Tambah Dosen Pengajar</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 mb-6 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.lecturers.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative flex items-center min-w-[260px]">
                <span class="material-symbols-outlined absolute left-3 text-[#8A94A6] text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIDN, atau lab dosen..." 
                       class="w-full bg-[#F4F5F9] text-[#161A30] pl-9 pr-3 py-2 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <button type="submit" class="px-3 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold hover:bg-[#4F46E5]">
                Cari
            </button>
            <a href="{{ route('admin.lecturers.index') }}" class="px-3 py-2 rounded-xl bg-[#F4F5F9] text-[#464555] text-xs font-semibold hover:bg-[#E5E7EB]">
                Reset
            </a>
        </form>

        <span class="text-xs text-[#8A94A6] font-semibold">
            Menampilkan {{ $lecturers->firstItem() ?? 0 }}–{{ $lecturers->lastItem() ?? 0 }} dari {{ $lecturers->total() }} Dosen Pengajar
        </span>
    </div>

    <!-- Table Container -->
    <div class="rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#F9FAFC] text-[#8A94A6] text-[11px] font-bold uppercase tracking-wider border-b border-[#E5E7EB]">
                        <th class="py-3.5 px-4">PROFIL DOSEN</th>
                        <th class="py-3.5 px-4">NIDN</th>
                        <th class="py-3.5 px-4">EMAIL</th>
                        <th class="py-3.5 px-4">LABORATORIUM / RISET</th>
                        <th class="py-3.5 px-4 text-center">AKUN LOGIN</th>
                        <th class="py-3.5 px-4 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($lecturers as $l)
                        <tr class="hover:bg-[#FBFBFC] transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $l->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}" 
                                         alt="Avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-[#5B50E5]/20 shrink-0">
                                    <div>
                                        <div class="font-bold text-[#161A30] text-[13px]">{{ $l->nama }}</div>
                                        <div class="text-[11px] text-[#8A94A6]">Gelar: {{ $l->gelar ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-mono font-bold text-[#5B50E5]">
                                {{ $l->nidn }}
                            </td>
                            <td class="py-4 px-4 text-[#464555]">
                                {{ $l->email ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-[#161A30] font-medium">
                                {{ $l->laboratorium ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#ECFDF5] text-[#059669]">
                                    Aktif (NIDN: {{ $l->nidn }})
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" onclick='openEditModal(@json($l))' 
                                            class="px-2.5 py-1.5 rounded-lg bg-[#F1F3F7] hover:bg-[#E2E8F0] text-[#475569] text-xs font-bold transition-all">
                                        Ubah
                                    </button>
                                    <form method="POST" action="{{ route('admin.lecturers.destroy', $l->id) }}" onsubmit="return confirm('Nonaktifkan dosen ini?');" class="inline">
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
                            <td colspan="6" class="py-8 text-center text-[#8A94A6] text-xs">
                                Belum ada data dosen pengajar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 px-2">
        {{ $lecturers->onEachSide(1)->links() }}
    </div>

</div>

<!-- MODAL FORM LECTURER -->
<div id="lecturerModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg p-7 shadow-2xl relative">
        <div class="flex items-center justify-between pb-4 border-b border-[#F1F3F7] mb-5">
            <h3 class="text-base font-bold text-[#161A30]" id="modalTitle">Tambah Dosen Pengajar</h3>
            <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-[#F4F5F9] hover:bg-[#E5E7EB] text-[#464555] flex items-center justify-center">✕</button>
        </div>

        <form id="lecturerForm" method="POST" action="{{ route('admin.lecturers.store') }}" class="space-y-4">
            @csrf
            <div id="methodField"></div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">NIDN</label>
                    <input type="text" name="nidn" id="nidn" required placeholder="0023048501" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Gelar (Singkat)</label>
                    <input type="text" name="gelar" id="gelar" placeholder="M.T. / M.Kom." class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" id="nama" required placeholder="Nama lengkap dosen" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">Email Resmi</label>
                <input type="email" name="email" id="email" placeholder="nama@ti.unsa.ac.id" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">Laboratorium / Kelompok Riset</label>
                <input type="text" name="laboratorium" id="laboratorium" placeholder="Lab Pemrograman & Basis Data" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#161A30] mb-1">URL Foto Profil (Opsional)</label>
                <input type="text" name="foto" id="foto" placeholder="https://..." class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F1F3F7]">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-[#F4F5F9] text-[#464555] text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#5B50E5] hover:bg-[#4F46E5] text-white text-xs font-bold shadow-md shadow-[#5B50E5]/25">Simpan Dosen</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('lecturerModal');
    const form = document.getElementById('lecturerForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openCreateModal() {
        modalTitle.textContent = 'Tambah Dosen Pengajar';
        form.action = "{{ route('admin.lecturers.store') }}";
        methodField.innerHTML = '';
        form.reset();
        modal.classList.remove('hidden');
    }

    function openEditModal(l) {
        modalTitle.textContent = 'Ubah Data Dosen';
        form.action = "/admin/pengajar/" + l.id;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('nidn').value = l.nidn;
        document.getElementById('gelar').value = l.gelar || '';
        document.getElementById('nama').value = l.nama;
        document.getElementById('email').value = l.email || '';
        document.getElementById('laboratorium').value = l.laboratorium || '';
        document.getElementById('foto').value = l.foto || '';

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection
