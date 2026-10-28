<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $role = $request->query('role', 'mahasiswa'); // Default to mahasiswa or query param
        if (!in_array($role, ['mahasiswa', 'dosen', 'admin'])) {
            $role = 'mahasiswa';
        }
        $lecturers = Lecturer::where('is_active', true)->orderBy('nama')->get();
        $students = User::where('role', 'mahasiswa')->where('is_active', true)->orderBy('username')->get();
        $activeYear = AcademicYear::resolve();
        
        return view('auth.login', compact('role', 'lecturers', 'students', 'activeYear'));
    }

    public function login(Request $request)
    {
        $role = $request->input('role', 'mahasiswa');

        if ($role === 'mahasiswa') {
            $request->validate([
                'nim' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->nim)
                ->where('role', 'mahasiswa')
                ->where('is_active', true)
                ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                if ($user->must_change_password) {
                    return redirect()->route('pengaturan.akun')->with('warning', 'Silakan ganti password awal sebelum melanjutkan.');
                }
                return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->name . ' (Mahasiswa). Anda dapat melihat jadwal perkuliahan.');
            }

            return back()->withInput()->with('error', 'NIM atau Password Mahasiswa tidak sesuai.');
        } elseif ($role === 'dosen') {
            $request->validate([
                'nidn' => 'required',
                'password' => 'required',
            ]);

            // Match user by username (NIDN) or through Lecturer table
            $user = User::where('username', $request->nidn)
                ->where('role', 'dosen')
                ->where('is_active', true)
                ->first();

            if (!$user) {
                $lecturer = Lecturer::where('nidn', $request->nidn)->where('is_active', true)->first();
                if ($lecturer && $lecturer->user?->is_active) {
                    $user = $lecturer->user;
                }
            }

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                if ($user->must_change_password) {
                    return redirect()->route('pengaturan.akun')->with('warning', 'Silakan ganti password awal sebelum melanjutkan.');
                }
                return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, ' . ($user->lecturer?->nama ?? $user->name));
            }

            return back()->withInput()->with('error', 'NIDN atau Password Dosen tidak sesuai.');
        } else {
            // Admin login
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->username)
                ->where('role', 'admin')
                ->where('is_active', true)
                ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                if ($user->must_change_password) {
                    return redirect()->route('pengaturan.akun')->with('warning', 'Silakan ganti password awal sebelum melanjutkan.');
                }
                return redirect()->route('admin.jadwal.index')->with('success', 'Selamat datang di Panel Admin, ' . $user->name);
            }

            return back()->withInput()->with('error', 'Username atau Password Administrator salah.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
