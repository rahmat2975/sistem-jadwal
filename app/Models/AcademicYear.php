<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public static function getActive(): ?self
    {
        return self::where('is_active', true)->first();
    }

    public static function resolve(?int $id = null): ?self
    {
        if ($id) {
            $selected = self::find($id);
            if ($selected) {
                return $selected;
            }
        }

        return self::getActive() ?? self::latest('tanggal_mulai')->first();
    }
}
