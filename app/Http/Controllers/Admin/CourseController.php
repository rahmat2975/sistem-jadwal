<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($courseQuery) use ($search) {
                $courseQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipe') && $request->tipe !== 'all') {
            $query->where('tipe', $request->tipe);
        }

        $courses = $query->orderBy('semester_ideal')->orderBy('kode')->paginate(10)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:courses,kode',
            'nama' => 'required|string|max:150',
            'singkatan' => 'nullable|string|max:10',
            'tipe' => 'required|in:wajib,pilihan',
            'sks' => 'required|integer|between:1,6',
            'semester_ideal' => 'nullable|integer|between:1,8',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|unique:courses,kode,' . $course->id,
            'nama' => 'required|string|max:150',
            'singkatan' => 'nullable|string|max:10',
            'tipe' => 'required|in:wajib,pilihan',
            'sks' => 'required|integer|between:1,6',
            'semester_ideal' => 'nullable|integer|between:1,8',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->schedules()->exists()) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Mata kuliah tidak dapat dihapus karena masih terhubung dengan jadwal.');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
