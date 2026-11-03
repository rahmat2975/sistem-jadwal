<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_generation_id', 'course_id', 'nama_tampilan', 'sks_tampilan', 'room_id', 'lecturer_id', 'kelas',
        'semester', 'hari', 'jam_mulai', 'jam_selesai', 'jumlah_mahasiswa', 'catatan',
    ];

    protected $casts = ['semester' => 'integer', 'hari' => 'integer', 'jumlah_mahasiswa' => 'integer'];

    public function generation(): BelongsTo { return $this->belongsTo(ScheduleGeneration::class, 'schedule_generation_id'); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function lecturer(): BelongsTo { return $this->belongsTo(Lecturer::class); }
}
