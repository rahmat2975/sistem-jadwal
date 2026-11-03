@extends('layouts.app')
@section('content')
@php
    $roleLabels = ['mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', 'admin' => 'Administrator'];
    $roleLabel = $roleLabels[$role] ?? 'Pengguna';
    $isAdmin = $role === 'admin';
@endphp
<div class="mx-auto max-w-md px-4 py-10 sm:py-16">
    <div class="rounded-3xl border border-[#E5E7EB] bg-white p-6 shadow-sm sm:p-7">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl {{ $isAdmin ? 'bg-[#FEF2F2] text-[#EF4444]' : 'bg-[#EEF2FF] text-[#5B50E5]' }}">
            <span class="material-symbols-outlined">{{ $isAdmin ? 'admin_panel_settings' : 'lock_reset' }}</span>
        </div>
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-extrabold text-[#161A30]">Lupa Password</h1>
            <span class="rounded-full {{ $isAdmin ? 'bg-[#FEF2F2] text-[#EF4444]' : 'bg-[#EEF2FF] text-[#5B50E5]' }} px-2 py-1 text-[10px] font-bold">{{ $roleLabel }}</span>
        </div>
        <p class="mt-2 text-sm leading-6 text-[#8A94A6]">
            Masukkan email akun {{ strtolower($roleLabel) }}. Tautan untuk membuat password baru akan dikirim ke email tersebut.
        </p>

        @if(session('status'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            <div>
                <label for="resetEmail" class="mb-1.5 block text-xs font-bold text-[#464555]">Email akun {{ $roleLabel }}</label>
                <input id="resetEmail" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="contoh@domain.com" class="w-full rounded-xl border border-[#E5E7EB] bg-[#F9FAFC] px-3 py-3 text-sm text-[#161A30] outline-none transition focus:border-[#5B50E5]/30 focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>
            <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#5B50E5] py-3 text-sm font-bold text-white transition hover:bg-[#4233cc]">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                Kirim Tautan Reset
            </button>
        </form>
        <a href="{{ route('login', ['role' => $role]) }}" class="mt-5 flex items-center justify-center gap-1 text-xs font-semibold text-[#5B50E5] hover:underline">
            <span class="material-symbols-outlined text-[15px]">arrow_back</span>
            Kembali ke login {{ strtolower($roleLabel) }}
        </a>
    </div>
</div>
@endsection
