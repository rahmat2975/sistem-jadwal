<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::withCount('schedules');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('gedung', 'like', "%{$search}%");
            });
        }

        $rooms = $query->orderByDesc('is_active')->orderBy('kode')->paginate(10)->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRoom($request);
        Room::create($validated + ['is_active' => true]);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $this->validateRoom($request, $room);
        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        if ($room->schedules()->exists()) {
            $room->update(['is_active' => false]);

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Ruangan memiliki jadwal terkait, jadi dinonaktifkan agar riwayat tetap aman.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    private function validateRoom(Request $request, ?Room $room = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:30', Rule::unique('rooms', 'kode')->ignore($room?->id)],
            'nama' => ['required', 'string', 'max:150'],
            'gedung' => ['nullable', 'string', 'max:150'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:1000'],
            'jenis' => ['required', Rule::in(['kelas', 'laboratorium'])],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
