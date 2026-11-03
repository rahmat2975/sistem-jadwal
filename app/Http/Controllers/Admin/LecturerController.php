<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%")
                  ->orWhere('laboratorium', 'like', "%{$search}%");
        }

        $lecturers = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|string|max:20|unique:lecturers,nidn',
            'nama' => 'required|string|max:150',
            'gelar' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'laboratorium' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Automatically create login user account for lecturer.
        $initialPassword = Str::random(12);
        $user = User::create([
            'username' => $validated['nidn'],
            'name' => $validated['nama'],
            'email' => $validated['email'] ?: null,
            'password' => Hash::make($initialPassword),
            'role' => 'dosen',
            'is_active' => true,
            'must_change_password' => true,
        ]);

        $validated['foto'] = $request->hasFile('foto')
            ? $request->file('foto')->store('profile-photos', 'public')
            : null;
        $validated['user_id'] = $user->id;
        Lecturer::create($validated);

        return redirect()->route('admin.lecturers.index')->with('success', "Dosen berhasil ditambahkan. Password awal akun: {$initialPassword}");
    }

    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);

        $validated = $request->validate([
            'nidn' => 'required|string|max:20|unique:lecturers,nidn,' . $lecturer->id,
            'nama' => 'required|string|max:150',
            'gelar' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'laboratorium' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($lecturer->foto) {
                Storage::disk('public')->delete($lecturer->foto);
            }
            $validated['foto'] = $request->file('foto')->store('profile-photos', 'public');
        } else {
            unset($validated['foto']);
        }

        $lecturer->update($validated);

        if ($lecturer->user) {
            $lecturer->user->update([
                'name' => $validated['nama'],
                'username' => $validated['nidn'],
                'email' => $validated['email'] ?: $lecturer->user->email,
            ]);
        }

        return redirect()->route('admin.lecturers.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        if ($lecturer->schedules()->exists()) {
            return redirect()->route('admin.lecturers.index')
                ->with('error', 'Dosen tidak dapat dihapus karena masih terhubung dengan jadwal. Pindahkan jadwal terlebih dahulu.');
        }

        DB::transaction(function () use ($lecturer) {
            $lecturer->delete();
            $lecturer->user?->delete();
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Dosen berhasil dinonaktifkan.');
    }
}
