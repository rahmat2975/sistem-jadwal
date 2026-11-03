@extends('layouts.app')

@section('content')
<div class="flex w-full flex-col gap-6">
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-xl font-bold text-[#161A30] sm:text-2xl">Periode Akademik</h1>
            <p class="mt-1 text-xs text-[#8A94A6] sm:text-sm">Tambah dan atur periode yang digunakan oleh jadwal perkuliahan.</p>
        </div>
        <button type="button" onclick="openCreateModal()" class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#5B50E5] px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-[#5B50E5]/25 transition hover:bg-[#4F46E5]">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Tambah Periode
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-semibold text-red-700">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
            <ul class="list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-[#E5E7EB]/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] border-collapse text-left text-xs">
                <thead class="border-b border-[#E5E7EB] bg-[#F9FAFC] text-[11px] font-bold uppercase tracking-wider text-[#8A94A6]">
                    <tr>
                        <th class="px-5 py-3.5">Periode</th>
                        <th class="px-5 py-3.5">Tanggal Perkuliahan</th>
                        <th class="px-5 py-3.5">Jadwal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($academicYears as $year)
                        <tr class="transition hover:bg-[#FBFBFC]">
                            <td class="px-5 py-4"><div class="font-bold text-[#161A30]">{{ $year->tahun }}</div><div class="mt-1 text-[11px] text-[#8A94A6]">Semester {{ ucfirst($year->semester) }}</div></td>
                            <td class="px-5 py-4 text-[#475569]">{{ $year->tanggal_mulai?->format('d/m/Y') }} – {{ $year->tanggal_selesai?->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 font-semibold text-[#475569]">{{ $year->schedules_count }} jadwal</td>
                            <td class="px-5 py-4">
                                @if($year->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500">Arsip</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    @unless($year->is_active)
                                        <form method="POST" action="{{ route('admin.academic-years.activate', $year) }}">@csrf<button class="rounded-lg bg-emerald-50 px-2.5 py-1.5 text-[11px] font-bold text-emerald-700 transition hover:bg-emerald-100">Aktifkan</button></form>
                                    @endunless
                                    <button type="button" onclick='openEditModal(@json($year))' class="rounded-lg bg-[#F1F3F7] px-2.5 py-1.5 text-[11px] font-bold text-[#475569] transition hover:bg-[#E2E8F0]">Ubah</button>
                                    @if($year->schedules_count === 0)
                                        <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" onsubmit="return confirm('Hapus periode ini?');">@csrf @method('DELETE')<button class="rounded-lg bg-red-50 px-2.5 py-1.5 text-[11px] font-bold text-red-600 transition hover:bg-red-100">Hapus</button></form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-xs text-[#8A94A6]">Belum ada periode akademik.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="academicYearModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-3xl bg-white p-7 shadow-2xl">
        <div class="mb-5 flex items-center justify-between border-b border-[#F1F3F7] pb-4"><h2 id="modalTitle" class="text-base font-bold text-[#161A30]">Tambah Periode</h2><button type="button" onclick="closeModal()" class="rounded-full bg-[#F4F5F9] px-3 py-1.5 text-[#464555]">✕</button></div>
        <form id="academicYearForm" method="POST" action="{{ route('admin.academic-years.store') }}" class="space-y-4">
            @csrf
            <div id="methodField"></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="mb-1 block text-xs font-bold text-[#161A30]">Tahun Akademik</label><input id="tahun" name="tahun" required placeholder="2027/2028" pattern="[0-9]{4}/[0-9]{4}" class="w-full rounded-xl bg-[#F4F5F9] px-3.5 py-2.5 text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20"></div>
                <div><label class="mb-1 block text-xs font-bold text-[#161A30]">Semester</label><select id="semester" name="semester" required class="w-full rounded-xl bg-[#F4F5F9] px-3.5 py-2.5 text-xs outline-none"><option value="ganjil">Ganjil</option><option value="genap">Genap</option></select></div>
                <div><label class="mb-1 block text-xs font-bold text-[#161A30]">Tanggal Mulai</label><input id="tanggal_mulai" type="date" name="tanggal_mulai" required class="w-full rounded-xl bg-[#F4F5F9] px-3.5 py-2.5 text-xs outline-none"></div>
                <div><label class="mb-1 block text-xs font-bold text-[#161A30]">Tanggal Selesai</label><input id="tanggal_selesai" type="date" name="tanggal_selesai" required class="w-full rounded-xl bg-[#F4F5F9] px-3.5 py-2.5 text-xs outline-none"></div>
            </div>
            <label class="flex items-center gap-2 text-xs font-semibold text-[#475569]"><input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-[#5B50E5]"> Jadikan periode aktif</label>
            <div class="flex justify-end gap-3 border-t border-[#F1F3F7] pt-4"><button type="button" onclick="closeModal()" class="rounded-xl bg-[#F4F5F9] px-4 py-2.5 text-xs font-bold text-[#464555]">Batal</button><button class="rounded-xl bg-[#5B50E5] px-5 py-2.5 text-xs font-bold text-white">Simpan Periode</button></div>
        </form>
    </div>
</div>
@push('scripts')
<script>
const academicYearModal=document.getElementById('academicYearModal');
const academicYearForm=document.getElementById('academicYearForm');
function openCreateModal(){document.getElementById('modalTitle').textContent='Tambah Periode';academicYearForm.action="{{ route('admin.academic-years.store') }}";document.getElementById('methodField').innerHTML='';academicYearForm.reset();academicYearModal.classList.remove('hidden');academicYearModal.classList.add('flex');}
function openEditModal(year){document.getElementById('modalTitle').textContent='Ubah Periode';academicYearForm.action='/admin/periode-akademik/'+year.id;document.getElementById('methodField').innerHTML='<input type="hidden" name="_method" value="PUT">';document.getElementById('tahun').value=year.tahun;document.getElementById('semester').value=year.semester;document.getElementById('tanggal_mulai').value=(year.tanggal_mulai||'').substring(0,10);document.getElementById('tanggal_selesai').value=(year.tanggal_selesai||'').substring(0,10);document.getElementById('is_active').checked=!!year.is_active;academicYearModal.classList.remove('hidden');academicYearModal.classList.add('flex');}
function closeModal(){academicYearModal.classList.add('hidden');academicYearModal.classList.remove('flex');}
</script>
@endpush
@endsection
