<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'gedung',
        'kapasitas',
        'jenis',
        'is_active',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getCurrentScheduleAttribute(): ?Schedule
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayNumber = $now->dayOfWeekIso;
        $timeStr = $now->format('H:i:s');

        return $this->schedules
            ->where('hari', $todayNumber)
            ->filter(function ($s) use ($timeStr) {
                return $s->jam_mulai <= $timeStr && $s->jam_selesai >= $timeStr;
            })
            ->first();
    }

    public function getNextScheduleTodayAttribute(): ?Schedule
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayNumber = $now->dayOfWeekIso;
        $timeStr = $now->format('H:i:s');

        return $this->schedules
            ->where('hari', $todayNumber)
            ->filter(function ($s) use ($timeStr) {
                return $s->jam_mulai > $timeStr;
            })
            ->sortBy('jam_mulai')
            ->first();
    }

    public function getTodaySchedulesAttribute()
    {
        $now = Carbon::now('Asia/Jakarta');
        $todayNumber = $now->dayOfWeekIso;

        return $this->schedules
            ->where('hari', $todayNumber)
            ->sortBy('jam_mulai');
    }

    public function getIsOccupiedAttribute(): bool
    {
        return $this->current_schedule !== null;
    }
}
