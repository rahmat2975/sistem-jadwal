<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->string('kelas', 20); // Kelas A, Kelas B, Kelas C
            $table->unsignedSmallInteger('angkatan'); // e.g. 2022, 2023, 2024
            $table->unsignedTinyInteger('semester'); // e.g. 1, 3, 5
            $table->unsignedSmallInteger('jumlah_mahasiswa')->nullable();
            $table->unsignedTinyInteger('hari'); // 1 = Senin, 2 = Selasa, 3 = Rabu, 4 = Kamis, 5 = Jumat, 6 = Sabtu, 7 = Minggu
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('catatan', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['academic_year_id', 'hari', 'jam_mulai'], 'idx_sched_day');
            $table->index(['room_id', 'hari', 'jam_mulai'], 'idx_sched_room');
            $table->index('course_id', 'idx_sched_course');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
