<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::withCount('schedules')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePeriod($request);
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($validated, $isActive) {
            if ($isActive) {
                AcademicYear::query()->update(['is_active' => false]);
            }

            AcademicYear::create($validated + ['is_active' => $isActive]);
        });

        return redirect()->route('admin.academic-years.index')->with('success', 'Periode akademik berhasil ditambahkan.');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $this->validatePeriod($request, $academicYear);
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($academicYear, $validated, $isActive) {
            if ($isActive) {
                AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
            }

            $academicYear->update($validated + ['is_active' => $isActive]);
        });

        return redirect()->route('admin.academic-years.index')->with('success', 'Periode akademik berhasil diperbarui.');
    }

    public function activate(AcademicYear $academicYear)
    {
        DB::transaction(function () use ($academicYear) {
            AcademicYear::query()->update(['is_active' => false]);
            $academicYear->update(['is_active' => true]);
        });

        return redirect()->route('admin.academic-years.index')->with('success', 'Periode akademik aktif berhasil diubah.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->schedules()->exists()) {
            return redirect()->route('admin.academic-years.index')
                ->with('error', 'Periode tidak dapat dihapus karena masih memiliki jadwal. Ubah statusnya melalui periode lain.');
        }

        $academicYear->delete();

        return redirect()->route('admin.academic-years.index')->with('success', 'Periode akademik berhasil dihapus.');
    }

    private function validatePeriod(Request $request, ?AcademicYear $academicYear = null): array
    {
        return $request->validate([
            'tahun' => [
                'required',
                'string',
                'regex:/^\d{4}\/\d{4}$/',
                Rule::unique('academic_years', 'tahun')
                    ->where(fn ($query) => $query->where('semester', $request->input('semester')))
                    ->ignore($academicYear?->id),
            ],
            'semester' => ['required', 'in:ganjil,genap'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'tahun.regex' => 'Format tahun harus seperti 2026/2027.',
            'tahun.unique' => 'Periode tahun dan semester tersebut sudah tersedia.',
        ]);
    }
}
