<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('lecturers')->restrictOnDelete();
            $table->enum('peran', ['koordinator', 'anggota'])->default('anggota');
            $table->unsignedTinyInteger('urutan')->default(1);
            $table->timestamps();

            $table->unique(['schedule_id', 'lecturer_id'], 'uq_sched_lect');
            $table->index('lecturer_id', 'idx_sl_lecturer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_lecturers');
    }
};
