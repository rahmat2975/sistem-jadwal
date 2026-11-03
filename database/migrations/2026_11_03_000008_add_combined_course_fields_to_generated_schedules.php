<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generated_schedules', function (Blueprint $table) {
            $table->string('nama_tampilan')->nullable()->after('course_id');
            $table->unsignedTinyInteger('sks_tampilan')->nullable()->after('nama_tampilan');
        });
    }

    public function down(): void
    {
        Schema::table('generated_schedules', function (Blueprint $table) {
            $table->dropColumn(['nama_tampilan', 'sks_tampilan']);
        });
    }
};
