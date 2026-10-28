@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#5B50E5]">Administrasi akun</p>
            <h1 class="text-2xl font-extrabold text-[#161A30] mt-1">Kelola Akun Login</h1>
            <p class="text-sm text-[#8A94A6] mt-1">Buat dan kelola akses mahasiswa serta dosen.</p>
        </div>
        <button type="button" onclick="openUserModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#5B50E5] px-4 py-3 text-xs font-bold text-white shadow-md hover:bg-[#4233cc]">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Buat Akun
        </button>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari username, nama, atau email..." class="flex-1 rounded-xl border border-[#E5E7EB] bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5B50E5]/20">
        <select name="role" onchange="this.form.submit()" class="rounded-xl border border-[#E5E7EB] bg-white px-4 py-3 text-sm outline-none">
            <option value="">Semua role</option>
            <option value="mahasiswa" @selected(request('role') === 'mahasiswa')>Mahasiswa</option>
            <option value="dosen" @selected(request('role') === 'dosen')>Dosen</option>
        </select>
        <button class="rounded-xl bg-white border border-[#E5E7EB] px-4 py-3 text-sm font-semibold text-[#464555] hover:text-[#5B50E5]">Cari</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-[#E5E7EB] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#F9FAFC] border-b border-[#E5E7EB] text-[10px] uppercase tracking-wider text-[#8A94A6]"><tr>
                    <th class="px-5 py-4">Akun</th><th class="px-5 py-4">Role</th><th class="px-5 py-4">Email</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-[#F1F3F7]">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#FBFBFC]">
                            <td class="px-5 py-4"><div class="font-bold text-[#161A30]">{{ $user->name }}</div><div class="text-xs text-[#8A94A6] font-mono">{{ $user->username }}</div></td>
                            <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $user->role === 'dosen' ? 'bg-[#EEF2FF] text-[#5B50E5]' : 'bg-[#ECFDF5] text-[#059669]' }}">{{ ucfirst($user->role) }}</span>@if($user->lecturer)<div class="text-[11px] text-[#8A94A6] mt-1">{{ $user->lecturer->nidn }}</div>@endif</td>
                            <td class="px-5 py-4 text-[#464555]">{{ $user->email ?: '-' }}</td>
                            <td class="px-5 py-4"><span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $user->is_active ? 'text-[#059669]' : 'text-[#EF4444]' }}"><span class="h-1.5 w-1.5 rounded-full {{ $user->is_active ? 'bg-[#059669]' : 'bg-[#EF4444]' }}"></span>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-2"><button type="button" onclick="openEditUser({{ $user->id }})" class="rounded-lg bg-[#F4F5F9] px-3 py-2 text-xs font-semibold text-[#464555] hover:text-[#5B50E5]">Edit</button><form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Nonaktifkan akun ini?')">@csrf @method('DELETE')<button class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100">Nonaktifkan</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-[#8A94A6]">Belum ada akun mahasiswa atau dosen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $users->onEachSide(1)->links() }}</div>
</div>

<div id="userModal" class="hidden fixed inset-0 z-50 items-start justify-center overflow-y-auto bg-black/50 p-4 sm:items-center">
    <div class="my-4 w-full max-w-lg max-h-[calc(100vh-2rem)] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl sm:my-8">
        <div class="flex items-center justify-between border-b border-[#F1F3F7] pb-4"><h2 id="userModalTitle" class="text-lg font-extrabold text-[#161A30]">Buat Akun</h2><button type="button" onclick="closeUserModal()" class="text-2xl text-[#8A94A6]">&times;</button></div>
        <form id="userForm" method="POST" action="{{ route('admin.users.store') }}" class="mt-5 space-y-4">
            @csrf <input id="userMethod" type="hidden" name="_method" value="POST">
            <div><label class="mb-1.5 block text-xs font-bold text-[#464555]">Role</label><select id="userRole" name="role" onchange="toggleLecturerField()" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"><option value="mahasiswa">Mahasiswa</option><option value="dosen">Dosen</option></select></div>
            <div id="lecturerField" class="hidden"><label class="mb-1.5 block text-xs font-bold text-[#464555]">Hubungkan data dosen</label><select name="lecturer_id" class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"><option value="">Pilih dosen</option>@foreach($lecturers as $lecturer)<option value="{{ $lecturer->id }}">{{ $lecturer->nama }} — {{ $lecturer->nidn }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-xs font-bold text-[#464555]">Username / NIM / NIDN</label><input id="userUsername" name="username" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"></div>
            <div><label class="mb-1.5 block text-xs font-bold text-[#464555]">Nama lengkap</label><input id="userName" name="name" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"></div>
            <div><label class="mb-1.5 block text-xs font-bold text-[#464555]">Email <span class="font-normal text-[#8A94A6]">(wajib untuk reset password)</span></label><input id="userEmail" type="email" name="email" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"></div>
            <div><label class="mb-1.5 block text-xs font-bold text-[#464555]">Password <span id="passwordHint" class="font-normal text-[#8A94A6]">(minimal 8 karakter)</span></label><input id="userPassword" type="password" name="password" minlength="8" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"></div>
            <div><label class="mb-1.5 block text-xs font-bold text-[#464555]">Konfirmasi password</label><input id="userPasswordConfirmation" type="password" name="password_confirmation" minlength="8" required class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"></div>
            <div id="activeField" class="hidden"><label class="mb-1.5 block text-xs font-bold text-[#464555]">Status</label><select name="is_active" class="w-full rounded-xl border border-[#E5E7EB] px-3 py-3 text-sm"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
            <button class="w-full rounded-xl bg-[#5B50E5] py-3 text-sm font-bold text-white hover:bg-[#4233cc]">Simpan Akun</button>
        </form>
    </div>
</div>

@php
    $userData = $users->getCollection()->keyBy('id')->map(function ($user) {
        return [
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'active' => $user->is_active,
        ];
    });
@endphp
<script>
const userData = @json($userData);
function toggleLecturerField(){ document.getElementById('lecturerField').classList.toggle('hidden', document.getElementById('userRole').value !== 'dosen'); }
function lockPage(){ document.body.classList.add('overflow-hidden'); }
function unlockPage(){ document.body.classList.remove('overflow-hidden'); }
function openUserModal(){ document.getElementById('userForm').reset(); document.getElementById('userForm').action = '{{ route('admin.users.store') }}'; document.getElementById('userMethod').value = 'POST'; document.getElementById('userModalTitle').textContent = 'Buat Akun'; document.getElementById('activeField').classList.add('hidden'); document.getElementById('userPassword').required = true; document.getElementById('userPasswordConfirmation').required = true; toggleLecturerField(); document.getElementById('userModal').classList.remove('hidden'); document.getElementById('userModal').classList.add('flex'); lockPage(); }
function openEditUser(id){ const user = userData[id]; if(!user) return; document.getElementById('userForm').reset(); document.getElementById('userForm').action = `/admin/akun/${id}`; document.getElementById('userMethod').value = 'PUT'; document.getElementById('userModalTitle').textContent = 'Edit Akun'; document.getElementById('userRole').value = user.role; document.getElementById('userUsername').value = user.username; document.getElementById('userName').value = user.name; document.getElementById('userEmail').value = user.email || ''; document.getElementById('userPassword').required = false; document.getElementById('userPasswordConfirmation').required = false; document.getElementById('activeField').classList.remove('hidden'); document.getElementById('userModal').classList.remove('hidden'); document.getElementById('userModal').classList.add('flex'); toggleLecturerField(); lockPage(); }
function closeUserModal(){ document.getElementById('userModal').classList.add('hidden'); document.getElementById('userModal').classList.remove('flex'); unlockPage(); }
</script>
@endsection
