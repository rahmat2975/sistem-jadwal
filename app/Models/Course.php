<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'singkatan',
        'tipe',
        'sks',
        'sks_teori',
        'sks_praktikum',
        'semester_ideal',
        'kategori',
        'kelompok_peminatan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'sks' => 'integer',
        'sks_teori' => 'integer',
        'sks_praktikum' => 'integer',
        'semester_ideal' => 'integer',
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
