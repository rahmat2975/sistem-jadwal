<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('shift', ['pagi', 'sore']);
            $table->unsignedTinyInteger('semester')->nullable();
            $table->string('nama', 150);
            $table->enum('status', ['draft', 'archived'])->default('draft');
            $table->timestamps();
        });

        Schema::create('generated_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_generation_id')->constrained('schedule_generations')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->string('kelas', 50);
            $table->unsignedTinyInteger('semester');
            $table->unsignedTinyInteger('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedSmallInteger('jumlah_mahasiswa')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['schedule_generation_id', 'hari', 'jam_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_schedules');
        Schema::dropIfExists('schedule_generations');
    }
};
