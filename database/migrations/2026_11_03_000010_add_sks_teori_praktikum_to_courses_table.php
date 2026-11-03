<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedTinyInteger('sks_teori')->default(0)->after('sks');
            $table->unsignedTinyInteger('sks_praktikum')->default(0)->after('sks_teori');
        });

        DB::table('courses')->orderBy('id')->get()->each(function ($course) {
            $isPracticalOnly = str_contains(strtolower((string) $course->nama), 'praktikum');
            $total = (int) $course->sks;

            if ($isPracticalOnly) {
                $teori = 0;
                $praktikum = $total;
            } elseif ($total >= 3) {
                $teori = $total - 1;
                $praktikum = 1;
            } else {
                $teori = $total;
                $praktikum = 0;
            }

            DB::table('courses')->where('id', $course->id)->update([
                'sks_teori' => $teori,
                'sks_praktikum' => $praktikum,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['sks_teori', 'sks_praktikum']);
        });
    }
};
