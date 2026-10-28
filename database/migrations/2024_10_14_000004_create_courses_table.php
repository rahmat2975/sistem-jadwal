<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->string('singkatan', 10)->nullable(); // e.g. PW, RP, AI, JK
            $table->enum('tipe', ['wajib', 'pilihan'])->default('wajib');
            $table->unsignedTinyInteger('sks');
            $table->unsignedTinyInteger('semester_ideal')->nullable();
            $table->string('kategori', 100)->nullable(); // e.g. Lab Pemrograman, Teori & Praktik, Konsentrasi Cerdas
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
