@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full">

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEF2F2] text-[#EF4444]">Panel Administrator</span>
                <h1 class="text-xl sm:text-2xl font-bold text-[#161A30]">Kelola Jadwal Perkuliahan</h1>
            </div>
            <p class="text-xs sm:text-sm text-[#8A94A6] mt-1">
                Tambah, ubah, dan hapus jadwal perkuliahan {{ ucfirst($activeYear->semester ?? 'ganjil') }} (Shift Pagi & Sore)
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- PRD Button: Unggah Excel (Biru Muda) -->
            <button type="button" onclick="alert('Fitur unggah file Excel siap diintegrasikan!')" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#E0F2FE] text-[#0284C7] hover:bg-[#BAE6FD] text-xs font-bold transition-all">
                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                <span>Unggah Excel</span>
            </button>

            <!-- PRD Button: Tambah Jadwal (Biru Tua/Indigo) -->
            <button type="button" onclick="openCreateModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5B50E5] text-white hover:bg-[#4F46E5] text-xs font-bold shadow-md shadow-[#5B50E5]/25 transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Jadwal</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 mb-6 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <!-- Search -->
            <div class="relative flex items-center min-w-[240px]">
                <span class="material-symbols-outlined absolute left-3 text-[#8A94A6] text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mata kuliah, kelas..." 
                       class="w-full bg-[#F4F5F9] text-[#161A30] pl-9 pr-3 py-2 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>

            <!-- Filter Shift -->
            <select name="shift" onchange="this.form.submit()" class="bg-[#F4F5F9] text-[#161A30] px-3 py-2 rounded-xl text-xs outline-none cursor-pointer">
                <option value="all">Semua Shift</option>
                <option value="pagi" {{ request('shift') === 'pagi' ? 'selected' : '' }}>Shift Pagi</option>
                <option value="sore" {{ request('shift') === 'sore' ? 'selected' : '' }}>Shift Sore</option>
            </select>

            <select name="academic_year_id" onchange="this.form.submit()" class="bg-[#F4F5F9] text-[#161A30] px-3 py-2 rounded-xl text-xs outline-none cursor-pointer">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $activeYear?->id === $year->id ? 'selected' : '' }}>{{ $year->tahun }} · {{ ucfirst($year->semester) }}</option>
                @endforeach
            </select>

            <!-- Filter Hari -->
            <select name="hari" onchange="this.form.submit()" class="bg-[#F4F5F9] text-[#161A30] px-3 py-2 rounded-xl text-xs outline-none cursor-pointer">
                <option value="all">Semua Hari</option>
                @foreach($days as $k => $d)
                    <option value="{{ $k }}" {{ request('hari') == $k ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold hover:bg-[#4F46E5]">
                Filter
            </button>
            <a href="{{ route('admin.jadwal.index') }}" class="px-3 py-2 rounded-xl bg-[#F4F5F9] text-[#464555] text-xs font-semibold hover:bg-[#E5E7EB]">
                Reset
            </a>
        </form>

        <span class="text-xs text-[#8A94A6] font-semibold">
            Menampilkan {{ $schedules->firstItem() ?? 0 }}–{{ $schedules->lastItem() ?? 0 }} dari {{ $schedules->total() }} Data Jadwal
        </span>
    </div>

    <!-- Table Container -->
    <div class="rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#F9FAFC] text-[#8A94A6] text-[11px] font-bold uppercase tracking-wider border-b border-[#E5E7EB]">
                        <th class="py-3.5 px-4">HARI & WAKTU</th>
                        <th class="py-3.5 px-4">SHIFT</th>
                        <th class="py-3.5 px-4">MATA KULIAH</th>
                        <th class="py-3.5 px-4">KELAS & SMT</th>
                        <th class="py-3.5 px-4">RUANGAN</th>
                        <th class="py-3.5 px-4">DOSEN KOORDINATOR</th>
                        <th class="py-3.5 px-4 text-center">STATUS</th>
                        <th class="py-3.5 px-4 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($schedules as $s)
                        @php
                            $koor = $s->coordinator();
                            $detail = $s->status_detail;
                        @endphp
                        <tr class="hover:bg-[#FBFBFC] transition-colors">
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-bold text-[#161A30]">{{ $s->hari_nama }}</div>
                                <div class="text-[11px] text-[#8A94A6]">{{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }} WITA</div>
                            </td>

                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($s->shift === 'sore')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#D97706]">
                                        <span class="material-symbols-outlined text-[12px]">dark_mode</span>
                                        <span>Shift Sore</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#E0F2FE] text-[#0284C7]">
                                        <span class="material-symbols-outlined text-[12px]">light_mode</span>
                                        <span>Shift Pagi</span>
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-4">
                                <div class="font-bold text-[#161A30]">{{ $s->course?->nama }}</div>
                                <div class="text-[11px] text-[#8A94A6]">{{ $s->course?->kode }} · {{ $s->course?->sks }} SKS ({{ ucfirst($s->course?->tipe) }})</div>
                            </td>

                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-semibold text-[#161A30]">{{ $s->kelas }}</div>
                                <div class="text-[11px] text-[#8A94A6]">Smt {{ $s->semester }} · {{ $s->jumlah_mahasiswa }} Mhs</div>
                            </td>

                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-medium text-[#161A30]">{{ $s->room?->nama ?? 'Belum Ditentukan' }}</div>
                                <div class="text-[10px] text-[#8A94A6]">{{ $s->room?->gedung }}</div>
                            </td>

                            <td class="py-4 px-4">
                                <div class="font-semibold text-[#161A30]">{{ $koor?->nama ?? '-' }}</div>
                                <div class="text-[10px] text-[#8A94A6]">NIDN: {{ $koor?->nidn ?? '-' }}</div>
                            </td>

                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @if($detail['badge'] === 'active')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EEF2FF] text-[#4F46E5]">Berlangsung</span>
                                @elseif($detail['badge'] === 'done')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F3F7] text-[#64748B]">Selesai</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#ECFDF5] text-[#059669]">Belum Mulai</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- PRD Button: Ubah (Abu-abu) -->
                                    <button type="button" 
                                            onclick='openEditModal(@json($s), {{ $koor?->id ?? "null" }})'
                                            class="px-2.5 py-1.5 rounded-lg bg-[#F1F3F7] hover:bg-[#E2E8F0] text-[#475569] text-xs font-bold transition-all">
                                        Ubah
                                    </button>

                                    <!-- PRD Button: Hapus (Merah) -->
                                    <form method="POST" action="{{ route('admin.jadwal.destroy', $s->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');" class="inline">
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
                            <td colspan="8" class="py-8 text-center text-[#8A94A6] text-xs">
                                Tidak ada data jadwal yang ditemukan. Silakan klik tombol "Tambah Jadwal".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 px-2">
        {{ $schedules->onEachSide(1)->links() }}
    </div>

</div>

<!-- MODAL FORM (CREATE / EDIT JADWAL) -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl w-full max-w-2xl p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-[#F1F3F7] mb-5">
            <div>
                <h3 class="text-base font-bold text-[#161A30]" id="modalTitle">Tambah Jadwal Perkuliahan</h3>
                <p class="text-xs text-[#8A94A6]">Lengkapi rincian mata kuliah, jam perkuliahan, dan pengampu</p>
            </div>
            <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-[#F4F5F9] hover:bg-[#E5E7EB] text-[#464555] flex items-center justify-center">
                ✕
            </button>
        </div>

        <form id="scheduleForm" method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $activeYear?->id }}">
            <div id="methodField"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Mata Kuliah -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Mata Kuliah</label>
                    <select name="course_id" id="course_id" required class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->kode }} - {{ $c->nama }} ({{ $c->sks }} SKS, {{ ucfirst($c->tipe) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Shift Kelas (Pagi / Sore) -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Shift Kelas</label>
                    <select name="shift" id="shift" required class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        <option value="pagi">Shift Pagi</option>
                        <option value="sore">Shift Sore</option>
                    </select>
                </div>

                <!-- Hari -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Hari</label>
                    <select name="hari" id="hari" required class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        <option value="1">Senin</option>
                        <option value="2">Selasa</option>
                        <option value="3">Rabu</option>
                        <option value="4">Kamis</option>
                        <option value="5">Jumat</option>
                    </select>
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Jam Mulai (WITA)</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" required value="08:00" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Jam Selesai (WITA)</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" required value="10:30" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>

                <!-- Ruangan -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Ruangan / Lab</label>
                    <select name="room_id" id="room_id" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        <option value="">-- Belum Ditentukan --</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->nama }} ({{ $r->gedung }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Kelas -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Kelas (Contoh: Kelas A)</label>
                    <input type="text" name="kelas" id="kelas" required placeholder="Contoh kelas" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Semester</label>
                    <input type="number" name="semester" id="semester" required min="1" max="8" value="5" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>

                <!-- Angkatan -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Tahun Angkatan</label>
                    <input type="number" name="angkatan" id="angkatan" required min="2018" max="2026" value="2022" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>

                <!-- Jumlah Mahasiswa -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Jumlah Mahasiswa</label>
                    <input type="number" name="jumlah_mahasiswa" id="jumlah_mahasiswa" value="40" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>

                <!-- Dosen Koordinator -->
                <div>
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Dosen Koordinator</label>
                    <select name="lecturer_id" id="lecturer_id" required class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        @foreach($lecturers as $l)
                            <option value="{{ $l->id }}">{{ $l->nama }} (NIDN: {{ $l->nidn }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dosen Anggota (Optional) -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Dosen Anggota (Opsional)</label>
                    <select name="member_id" id="member_id" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                        <option value="">-- Tidak Ada Dosen Anggota --</option>
                        @foreach($lecturers as $l)
                            <option value="{{ $l->id }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Catatan -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-[#161A30] mb-1">Catatan / Topik Modul</label>
                    <input type="text" name="catatan" id="catatan" placeholder="Praktikum Modul atau Teori" class="w-full bg-[#F4F5F9] text-[#161A30] px-3.5 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F1F3F7] mt-5">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 rounded-xl bg-[#F4F5F9] text-[#464555] hover:bg-[#E5E7EB] text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#5B50E5] hover:bg-[#4F46E5] text-white text-xs font-bold shadow-md shadow-[#5B50E5]/25 transition-all">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('scheduleModal');
    const form = document.getElementById('scheduleForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openCreateModal() {
        modalTitle.textContent = 'Tambah Jadwal Perkuliahan';
        form.action = "{{ route('admin.jadwal.store') }}";
        methodField.innerHTML = '';
        form.reset();
        modal.classList.remove('hidden');
    }

    function openEditModal(sched, koorId) {
        modalTitle.textContent = 'Ubah Jadwal Perkuliahan';
        form.action = "/admin/jadwal/" + sched.id;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('course_id').value = sched.course_id;
        document.getElementById('shift').value = sched.shift || 'pagi';
        document.getElementById('hari').value = sched.hari;
        document.getElementById('jam_mulai').value = sched.jam_mulai.substring(0, 5);
        document.getElementById('jam_selesai').value = sched.jam_selesai.substring(0, 5);
        document.getElementById('room_id').value = sched.room_id || '';
        document.getElementById('kelas').value = sched.kelas;
        document.getElementById('semester').value = sched.semester;
        document.getElementById('angkatan').value = sched.angkatan;
        document.getElementById('jumlah_mahasiswa').value = sched.jumlah_mahasiswa || 40;
        document.getElementById('catatan').value = sched.catatan || '';
        if (koorId) {
            document.getElementById('lecturer_id').value = koorId;
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
@endpush
@endsection
