<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 9); // e.g. 2024/2025
            $table->enum('semester', ['ganjil', 'genap']);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['tahun', 'semester'], 'uq_academic_years');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
