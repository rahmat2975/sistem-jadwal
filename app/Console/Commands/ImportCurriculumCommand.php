<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;
use ZipArchive;

class ImportCurriculumCommand extends Command
{
    protected $signature = 'curriculum:import {file : Path file XLSX kurikulum} {--replace : Nonaktifkan data mata kuliah yang tidak terdapat pada workbook}';
    protected $description = 'Import kurikulum dari workbook XLSX ke data mata kuliah';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (!is_file($path)) {
            $this->error("File tidak ditemukan: {$path}");
            return self::FAILURE;
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            $this->error('File XLSX tidak dapat dibuka.');
            return self::FAILURE;
        }

        $namespace = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        $sharedStrings = [];
        if (($xml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
            $shared = simplexml_load_string($xml);
            $shared->registerXPathNamespace('x', $namespace);
            foreach ($shared->xpath('//x:si') as $item) {
                $item->registerXPathNamespace('x', $namespace);
                $sharedStrings[] = implode('', array_map('strval', $item->xpath('.//x:t')));
            }
        }

        $sheet = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
        $sheet->registerXPathNamespace('x', $namespace);
        $rows = [];
        foreach ($sheet->xpath('//x:sheetData/x:row') as $row) {
            $row->registerXPathNamespace('x', $namespace);
            $values = [];
            foreach ($row->xpath('x:c') as $cell) {
                $cell->registerXPathNamespace('x', $namespace);
                $ref = (string) $cell['r'];
                preg_match('/([A-Z]+)(\d+)/', $ref, $match);
                $column = $this->columnNumber($match[1]);
                $valueNode = $cell->xpath('x:v');
                $value = (string) ($valueNode[0] ?? '');
                if ((string) $cell['t'] === 's') $value = $sharedStrings[(int) $value] ?? '';
                $values[$column] = trim($value);
            }
            $rows[] = $values;
        }
        $zip->close();

        $states = [null, null];
        $lastSks = [null, null];
        $elective = [false, false];
        $electiveGroups = [null, null];
        $items = [];

        foreach ($rows as $row) {
            foreach ([[2, 3, 4], [7, 8, 9]] as $side => [$codeColumn, $nameColumn, $sksColumn]) {
                $heading = $side === 0
                    ? implode(' ', array_map(fn ($column) => $row[$column] ?? '', range(1, 4)))
                    : implode(' ', array_map(fn ($column) => $row[$column] ?? '', range(6, 9)));
                if (preg_match('/mata kuliah semester\s*(\d+)/i', $heading, $match)) {
                    $states[$side] = (int) $match[1];
                    $elective[$side] = false;
                    $electiveGroups[$side] = null;
                    $lastSks[$side] = null;
                }
                if (preg_match('/mata kuliah peminatan\s*(.*)/i', $heading, $groupMatch)) {
                    $states[$side] = 7;
                    $elective[$side] = true;
                    $electiveGroups[$side] = trim($groupMatch[1]) ?: 'Peminatan';
                    $lastSks[$side] = null;
                }

                $code = $row[$codeColumn] ?? null;
                $name = trim((string) ($row[$nameColumn] ?? ''));
                $sks = $row[$sksColumn] ?? null;
                if ($states[$side] === null || !is_numeric($code) || $name === '') continue;

                // Workbook memakai sel SKS yang digabung untuk beberapa mata
                // kuliah agama. Gunakan nilai terakhir pada blok tersebut agar
                // setiap baris tetap memiliki nilai SKS di database.
                if (is_numeric($sks)) {
                    $lastSks[$side] = (int) $sks;
                } elseif ($lastSks[$side] !== null) {
                    $sks = $lastSks[$side];
                }
                if (!is_numeric($sks)) continue;

                $code = (string) (int) $code;
                $name = preg_replace('/\s+/', ' ', $name);
                if (strcasecmp($name, 'PraktikumCloud Computing') === 0) {
                    $code = 'PRAK-CC-2022';
                    $name = 'Praktikum Cloud Computing';
                }

                // Workbook referensi memiliki kode berulang untuk mata kuliah
                // pada blok peminatan. Kolom kode aplikasi bersifat unik, jadi
                // pertahankan kode utama dan beri alias stabil pada duplikatnya.
                if ($code === '1922621' && strcasecmp($name, 'Pemrograman Client Server') === 0) {
                    $code = '1922621B';
                }

                $items[$code] = [
                    'kode' => $code,
                    'nama' => $name,
                    'sks' => (int) $sks,
                    'sks_teori' => stripos($name, 'praktikum') === 0 ? 0 : (int) $sks,
                    'sks_praktikum' => stripos($name, 'praktikum') === 0 ? (int) $sks : 0,
                    'semester_ideal' => $states[$side],
                    'tipe' => $elective[$side] ? 'pilihan' : 'wajib',
                    'kategori' => $elective[$side] ? 'Peminatan' : (stripos($name, 'praktikum') === 0 ? 'Praktikum' : 'Wajib'),
                    'kelompok_peminatan' => $elective[$side] ? $electiveGroups[$side] : null,
                    'is_active' => true,
                ];
            }
        }

        $created = 0;
        $updated = 0;
        foreach ($items as $item) {
            $course = Course::where('kode', $item['kode'])->first();
            if ($course) {
                $course->update($item);
                $updated++;
            } else {
                Course::create($item);
                $created++;
            }
        }

        if ($this->option('replace')) {
            $importedCodes = array_fill_keys(array_map('strval', array_keys($items)), true);
            Course::where('is_active', true)->get()->each(function (Course $course) use ($importedCodes) {
                if (!isset($importedCodes[(string) $course->kode])) {
                    $course->update(['is_active' => false]);
                }
            });
        }

        // Kode MKWU/MKWP adalah kode sementara dari jadwal lama. Setelah kode
        // resmi kurikulum tersedia, kode sementara tidak boleh ikut generator.
        Course::where('is_active', true)
            ->where(function ($query) {
                $query->where('kode', 'like', 'MKWU%')
                    ->orWhere('kode', 'like', 'MKWP%');
            })
            ->update(['is_active' => false]);

        $this->info("Kurikulum berhasil diimpor: {$created} baru, {$updated} diperbarui.");
        if ($this->option('replace')) $this->line('Data di luar workbook dinonaktifkan.');
        $this->line('Alias duplikat: Praktikum Cloud Computing = PRAK-CC-2022, Pemrograman Client Server = 1922621B.');
        return self::SUCCESS;
    }

    private function columnNumber(string $letters): int
    {
        $number = 0;
        foreach (str_split($letters) as $letter) $number = $number * 26 + ord($letter) - 64;
        return $number;
    }
}
