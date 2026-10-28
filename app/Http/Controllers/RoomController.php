<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $now = Carbon::now('Asia/Jakarta');
        $currentTimeStr = $now->format('H:i:s');
        $todayNumber = $now->dayOfWeekIso;

        $activeYear = AcademicYear::resolve($request->integer('academic_year_id'));
        $academicYears = AcademicYear::orderByDesc('tanggal_mulai')->get();
        
        $rooms = Room::with(['schedules' => function ($q) use ($activeYear) {
            $q->where('academic_year_id', $activeYear?->id);
            $q->with(['course', 'lecturers'])->orderBy('hari')->orderBy('jam_mulai');
        }])->where('is_active', true)->orderBy('jenis', 'desc')->orderBy('kode')->get();

        $totalRooms = $rooms->count();
        $totalLab = $rooms->where('jenis', 'laboratorium')->count();
        $totalKelas = $rooms->where('jenis', 'kelas')->count();
        $totalKapasitas = $rooms->sum('kapasitas');

        // Calculate live occupancy for right now in WIB
        $occupiedCount = 0;
        foreach ($rooms as $room) {
            if ($room->is_occupied) {
                $occupiedCount++;
            }
        }
        $availableCount = max(0, $totalRooms - $occupiedCount);

        return view('rooms.index', compact(
            'activeYear',
            'academicYears',
            'rooms',
            'totalRooms',
            'totalLab',
            'totalKelas',
            'totalKapasitas',
            'occupiedCount',
            'availableCount',
            'now',
            'todayNumber',
            'currentTimeStr'
        ));
    }
}
