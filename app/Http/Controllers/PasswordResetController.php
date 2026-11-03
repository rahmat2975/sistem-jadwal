<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestForm(Request $request)
    {
        $role = $request->query('role', 'mahasiswa');
        if (!in_array($role, ['mahasiswa', 'dosen', 'admin'], true)) {
            $role = 'mahasiswa';
        }

        return view('auth.forgot-password', compact('role'));
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:mahasiswa,dosen,admin',
        ]);

        $exists = User::where('email', $validated['email'])
            ->where('role', $validated['role'])
            ->where('is_active', true)
            ->exists();

        if ($exists) {
            Password::sendResetLink(['email' => $validated['email']]);
        }

        return back()->with('status', 'Jika email terdaftar, tautan reset password telah dikirim.');
    }

    public function resetForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'must_change_password' => false,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.')
            : back()->withInput()->withErrors(['email' => __($status)]);
    }
}
