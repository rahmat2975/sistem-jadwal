<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleGeneration extends Model
{
    use HasFactory;

    protected $fillable = ['academic_year_id', 'created_by', 'shift', 'semester', 'nama', 'status'];

    protected $casts = ['semester' => 'integer'];

    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function schedules(): HasMany { return $this->hasMany(GeneratedSchedule::class); }
}
