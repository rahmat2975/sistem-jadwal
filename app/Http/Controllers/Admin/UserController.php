<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('lecturer')->whereIn('role', ['mahasiswa', 'dosen']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($userQuery) use ($search) {
                $userQuery->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && in_array($request->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(10)->withQueryString();
        $lecturers = Lecturer::where('is_active', true)->whereNull('user_id')->orderBy('nama')->get();

        return view('admin.users.index', compact('users', 'lecturers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:mahasiswa,dosen',
            'username' => 'required|string|max:50|unique:users,username',
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'lecturer_id' => ['nullable', 'required_if:role,dosen', Rule::exists('lecturers', 'id')->where('is_active', true)->whereNull('user_id')],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => true,
                'must_change_password' => false,
            ]);

            if ($validated['role'] === 'dosen' && !empty($validated['lecturer_id'])) {
                Lecturer::whereKey($validated['lecturer_id'])->update(['user_id' => $user->id]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $user = User::whereIn('role', ['mahasiswa', 'dosen'])->findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
            $data['must_change_password'] = false;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::whereIn('role', ['mahasiswa', 'dosen'])->findOrFail($id);
        $user->update(['is_active' => false]);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dinonaktifkan.');
    }
}
