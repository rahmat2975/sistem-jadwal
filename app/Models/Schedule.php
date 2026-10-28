<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'course_id',
        'room_id',
        'kelas',
        'shift',
        'angkatan',
        'semester',
        'jumlah_mahasiswa',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'angkatan' => 'integer',
        'semester' => 'integer',
        'jumlah_mahasiswa' => 'integer',
        'hari' => 'integer',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(Lecturer::class, 'schedule_lecturers')
            ->withPivot('peran', 'urutan')
            ->orderByPivot('urutan')
            ->withTimestamps();
    }

    public function coordinator()
    {
        return $this->lecturers()->wherePivot('peran', 'koordinator')->first()
            ?? $this->lecturers()->first();
    }

    public static function getNamaHari(int $hari): string
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $days[$hari] ?? 'Senin';
    }

    public function getHariNamaAttribute(): string
    {
        return self::getNamaHari($this->hari);
    }

    public function getStatusAttribute(): string
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayNumber = $now->dayOfWeekIso;

        if ($this->hari != $todayNumber) {
            return self::getNamaHari($this->hari);
        }

        $currentTime = $now->format('H:i:s');

        if ($currentTime < $this->jam_mulai) {
            return 'Belum Mulai';
        } elseif ($currentTime >= $this->jam_mulai && $currentTime <= $this->jam_selesai) {
            return 'Sedang Berlangsung';
        } else {
            return 'Selesai';
        }
    }

    public function getStatusDetailAttribute(): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayNumber = $now->dayOfWeekIso;
        $isToday = ($this->hari == $todayNumber);

        $startTime = Carbon::createFromTimeString($this->jam_mulai, 'Asia/Jakarta');
        $endTime = Carbon::createFromTimeString($this->jam_selesai, 'Asia/Jakarta');

        if (!$isToday) {
            return [
                'status' => 'Hari ' . self::getNamaHari($this->hari),
                'badge' => 'scheduled',
                'message' => 'Jadwal Hari ' . self::getNamaHari($this->hari) . ' (' . substr($this->jam_mulai, 0, 5) . ' WIB)',
                'progress' => 0,
                'is_today' => false,
            ];
        }

        if ($now->lt($startTime)) {
            $diffMins = (int) $now->diffInMinutes($startTime, false);
            if ($diffMins <= 60) {
                $msg = "Mulai dalam {$diffMins} menit";
            } else {
                $hours = floor($diffMins / 60);
                $rem = $diffMins % 60;
                $msg = "Mulai dalam {$hours} jam" . ($rem > 0 ? " {$rem} mnt" : "");
            }

            return [
                'status' => 'Belum Mulai',
                'badge' => 'upcoming',
                'message' => $msg,
                'progress' => 0,
                'is_today' => true,
            ];
        } elseif ($now->between($startTime, $endTime)) {
            $totalMins = max(1, $startTime->diffInMinutes($endTime));
            $passedMins = $startTime->diffInMinutes($now);
            $remainingMins = max(0, $now->diffInMinutes($endTime));
            $progress = min(100, max(0, round(($passedMins / $totalMins) * 100)));

            return [
                'status' => 'Sedang Berlangsung',
                'badge' => 'active',
                'message' => "Tersisa {$remainingMins} menit",
                'progress' => $progress,
                'is_today' => true,
            ];
        } else {
            $passedMins = (int) $endTime->diffInMinutes($now);
            if ($passedMins < 60) {
                $msg = "Selesai {$passedMins} mnt lalu";
            } else {
                $hours = floor($passedMins / 60);
                $msg = "Selesai {$hours} jam lalu";
            }

            return [
                'status' => 'Selesai',
                'badge' => 'done',
                'message' => $msg,
                'progress' => 100,
                'is_today' => true,
            ];
        }
    }
}
