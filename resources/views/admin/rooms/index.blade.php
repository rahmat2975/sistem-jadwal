@extends('layouts.app')

@section('content')
<div class="flex flex-col w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#161A30]">Kelola Ruangan</h1>
            <p class="text-xs sm:text-sm text-[#8A94A6] mt-1">Atur kode, nama, kapasitas, dan status ruang kuliah.</p>
        </div>
        <button type="button" onclick="openRoomModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#5B50E5] text-white hover:bg-[#4F46E5] text-xs font-bold shadow-md shadow-[#5B50E5]/25">
            <span class="material-symbols-outlined text-[18px]">add_business</span> Tambah Ruangan
        </button>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl bg-[#ECFDF5] px-4 py-3 text-xs font-semibold text-[#047857]">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-5 rounded-xl bg-[#FEF2F2] px-4 py-3 text-xs text-[#B91C1C]">{{ $errors->first() }}</div>
    @endif

    <div class="p-4 rounded-2xl bg-white shadow-sm border border-[#E5E7EB]/80 mb-6 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.rooms.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <div class="relative min-w-[250px] flex-1 sm:flex-none">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#8A94A6] text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau gedung..." class="w-full bg-[#F4F5F9] text-[#161A30] pl-9 pr-3 py-2 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>
            <button type="submit" class="px-3 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold">Cari</button>
            <a href="{{ route('admin.rooms.index') }}" class="px-3 py-2 rounded-xl bg-[#F4F5F9] text-[#464555] text-xs font-semibold">Reset</a>
        </form>
        <span class="text-xs text-[#8A94A6] font-semibold">{{ $rooms->firstItem() ?? 0 }}–{{ $rooms->lastItem() ?? 0 }} dari {{ $rooms->total() }} ruangan</span>
    </div>

    <div class="rounded-3xl bg-white shadow-sm border border-[#E5E7EB]/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead><tr class="bg-[#F9FAFC] text-[#8A94A6] text-[11px] font-bold uppercase tracking-wider border-b border-[#E5E7EB]">
                    <th class="py-3.5 px-4">KODE</th><th class="py-3.5 px-4">NAMA RUANG</th><th class="py-3.5 px-4">GEDUNG</th><th class="py-3.5 px-4">KAPASITAS</th><th class="py-3.5 px-4">JENIS</th><th class="py-3.5 px-4">STATUS</th><th class="py-3.5 px-4 text-right">AKSI</th>
                </tr></thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                @forelse($rooms as $room)
                    <tr class="hover:bg-[#FBFBFC]">
                        <td class="py-4 px-4 font-mono font-bold text-[#5B50E5]">{{ $room->kode }}</td>
                        <td class="py-4 px-4 font-bold text-[#161A30]">{{ $room->nama }}<div class="text-[11px] text-[#8A94A6] mt-1">{{ $room->schedules_count }} jadwal terkait</div></td>
                        <td class="py-4 px-4 text-[#464555]">{{ $room->gedung ?: '-' }}</td>
                        <td class="py-4 px-4 text-[#464555]">{{ $room->kapasitas }} kursi</td>
                        <td class="py-4 px-4 text-[#464555]">{{ ucfirst($room->jenis) }}</td>
                        <td class="py-4 px-4"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $room->is_active ? 'bg-[#ECFDF5] text-[#047857]' : 'bg-[#F3F4F6] text-[#6B7280]' }}">{{ $room->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="py-4 px-4 text-right whitespace-nowrap"><div class="inline-flex items-center gap-1.5">
                            <button type="button" onclick='editRoom(@json($room))' class="px-2.5 py-1.5 rounded-lg bg-[#F1F3F7] text-[#475569] text-xs font-bold">Ubah</button>
                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus atau nonaktifkan ruangan ini?')" class="inline">@csrf @method('DELETE')<button class="px-2.5 py-1.5 rounded-lg bg-[#FEF2F2] text-[#EF4444] text-xs font-bold">Hapus</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-[#8A94A6]">Belum ada data ruangan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 px-2">{{ $rooms->onEachSide(1)->links() }}</div>
</div>

<div id="roomModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-7 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-[#F1F3F7] mb-5"><h3 id="roomModalTitle" class="text-base font-bold text-[#161A30]">Tambah Ruangan</h3><button type="button" onclick="closeRoomModal()" class="w-8 h-8 rounded-full bg-[#F4F5F9] text-[#464555]">✕</button></div>
        <form id="roomForm" method="POST" action="{{ route('admin.rooms.store') }}" class="space-y-4">@csrf<div id="roomMethod"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3"><div><label class="block text-xs font-bold mb-1">Kode Ruangan</label><input id="roomKode" name="kode" required class="w-full bg-[#F4F5F9] px-3.5 py-2.5 rounded-xl text-xs"></div><div><label class="block text-xs font-bold mb-1">Kapasitas</label><input id="roomKapasitas" type="number" min="1" name="kapasitas" required class="w-full bg-[#F4F5F9] px-3.5 py-2.5 rounded-xl text-xs"></div></div>
            <div><label class="block text-xs font-bold mb-1">Nama Ruangan</label><input id="roomNama" name="nama" required class="w-full bg-[#F4F5F9] px-3.5 py-2.5 rounded-xl text-xs"></div>
            <div><label class="block text-xs font-bold mb-1">Gedung / Lokasi</label><input id="roomGedung" name="gedung" class="w-full bg-[#F4F5F9] px-3.5 py-2.5 rounded-xl text-xs"></div>
            <div><label class="block text-xs font-bold mb-1">Jenis</label><select id="roomJenis" name="jenis" required class="w-full bg-[#F4F5F9] px-3.5 py-2.5 rounded-xl text-xs"><option value="kelas">Kelas</option><option value="laboratorium">Laboratorium</option></select></div>
            <label id="roomActiveWrap" class="hidden items-center gap-2 text-xs font-semibold"><input id="roomActive" type="checkbox" name="is_active" value="1" class="rounded"> Ruangan aktif</label>
            <div class="flex justify-end gap-3 pt-4 border-t border-[#F1F3F7]"><button type="button" onclick="closeRoomModal()" class="px-4 py-2.5 rounded-xl bg-[#F4F5F9] text-xs font-bold">Batal</button><button class="px-5 py-2.5 rounded-xl bg-[#5B50E5] text-white text-xs font-bold">Simpan Ruangan</button></div>
        </form>
    </div>
</div>
@push('scripts')
<script>
const roomModal=document.getElementById('roomModal'),roomForm=document.getElementById('roomForm');
function openRoomModal(){roomForm.reset();roomForm.action='{{ route('admin.rooms.store') }}';document.getElementById('roomMethod').innerHTML='';document.getElementById('roomModalTitle').textContent='Tambah Ruangan';document.getElementById('roomActiveWrap').classList.add('hidden');roomModal.classList.remove('hidden');roomModal.classList.add('flex');}
function editRoom(room){roomForm.action='/admin/ruang/'+room.id;document.getElementById('roomMethod').innerHTML='<input type="hidden" name="_method" value="PUT">';document.getElementById('roomModalTitle').textContent='Ubah Ruangan';document.getElementById('roomKode').value=room.kode;document.getElementById('roomNama').value=room.nama;document.getElementById('roomGedung').value=room.gedung||'';document.getElementById('roomKapasitas').value=room.kapasitas;document.getElementById('roomJenis').value=room.jenis;document.getElementById('roomActive').checked=!!room.is_active;document.getElementById('roomActiveWrap').classList.remove('hidden');document.getElementById('roomActiveWrap').classList.add('flex');roomModal.classList.remove('hidden');roomModal.classList.add('flex');}
function closeRoomModal(){roomModal.classList.add('hidden');roomModal.classList.remove('flex');}
</script>
@endpush
@endsection
