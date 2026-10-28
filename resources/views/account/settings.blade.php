@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm p-6">
        <h1 class="text-xl font-extrabold text-[#161A30]">Pengaturan Akun</h1>
        <p class="text-sm text-[#8A94A6] mt-1">Perbarui password akun Anda.</p>

        @if(session('warning'))
            <div class="mt-5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">{{ session('warning') }}</div>
        @endif
        @if(session('success'))
            <div class="mt-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pengaturan.password') }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-[#464555] mb-1.5">Password saat ini</label>
                <input type="password" name="current_password" required class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#464555] mb-1.5">Password baru</label>
                <input type="password" name="password" required minlength="8" class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>
            <div>
                <label class="block text-xs font-bold text-[#464555] mb-1.5">Konfirmasi password baru</label>
                <input type="password" name="password_confirmation" required minlength="8" class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5B50E5]/20">
            </div>
            <button type="submit" class="w-full rounded-xl bg-[#5B50E5] hover:bg-[#4233cc] text-white font-bold text-sm py-3">Simpan Password</button>
        </form>
    </div>
</div>
@endsection
