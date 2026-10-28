<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RealScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Academic Year 2026/2027 Gasal (Aktif)
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        
        $academicYear = AcademicYear::firstOrCreate(
            ['tahun' => '2026/2027', 'semester' => 'ganjil'],
            [
                'tanggal_mulai' => '2026-09-01',
                'tanggal_selesai' => '2027-01-31',
                'is_active' => true,
            ]
        );
        $academicYear->update(['is_active' => true]);

        // Sediakan periode semester genap tanpa menghapus jadwal semester ganjil.
        AcademicYear::firstOrCreate(
            ['tahun' => '2026/2027', 'semester' => 'genap'],
            [
                'tanggal_mulai' => '2027-02-01',
                'tanggal_selesai' => '2027-07-31',
                'is_active' => false,
            ]
        );

        $adminUser = User::where('role', 'admin')->first() ?? User::first();

        // 2. Daftar Dosen dari Jadwal Kuliah
        $lecturersData = [
            ['code' => 'IT', 'nama' => 'Ir. Tri Irianto Tjendrowasono, M.T.', 'nidn' => '0015066401', 'gelar' => 'Ir., M.T.', 'email' => 'tri.irianto@ti.unsa.ac.id', 'lab' => 'Lab Pemrograman & Hardware'],
            ['code' => 'AR', 'nama' => 'Agus Rianto, S.T., M.T.', 'nidn' => '0012087502', 'gelar' => 'S.T., M.T.', 'email' => 'agus.rianto@ti.unsa.ac.id', 'lab' => 'Lab Jaringan Komputer'],
            ['code' => 'SK', 'nama' => 'Dr. Sukoco, S.Si., M.Si., M.Kom.', 'nidn' => '0024097003', 'gelar' => 'Dr., S.Si., M.Si., M.Kom.', 'email' => 'sukoco@ti.unsa.ac.id', 'lab' => 'Lab Multimedia & Komputasi'],
            ['code' => 'AB', 'nama' => 'Dr. Abdillah Baradja, S.Kom., M.Eng.', 'nidn' => '0005117801', 'gelar' => 'Dr., S.Kom., M.Eng.', 'email' => 'abdillah.baradja@ti.unsa.ac.id', 'lab' => 'Lab Sistem Informasi & Mobile'],
            ['code' => 'RATS', 'nama' => 'Ramadhian Agus Triono S., S.Ag., S.Kom., M.M.', 'nidn' => '0017088002', 'gelar' => 'S.Ag., S.Kom., M.M.', 'email' => 'ramadhian.agus@ti.unsa.ac.id', 'lab' => 'Lab Technopreneurship & Bisnis'],
            ['code' => 'BM', 'nama' => 'Bayu Mukti, S.Kom., M.Kom.', 'nidn' => '0021028704', 'gelar' => 'S.Kom., M.Kom.', 'email' => 'bayu.mukti@ti.unsa.ac.id', 'lab' => 'Lab Multimedia & 3D'],
            ['code' => 'JK', 'nama' => 'Jani Kusanti, S.Kom., M.Cs.', 'nidn' => '0019058301', 'gelar' => 'S.Kom., M.Cs.', 'email' => 'jani.kusanti@ti.unsa.ac.id', 'lab' => 'Lab Rekayasa Perangkat Lunak & AI'],
            ['code' => 'MTR', 'nama' => 'Muhammad Taufik Rusydi, S.Kom., M.H.', 'nidn' => '0003048503', 'gelar' => 'S.Kom., M.H.', 'email' => 'taufik.rusydi@ti.unsa.ac.id', 'lab' => 'Lab Cyber Law & Etika'],
            ['code' => 'NAH', 'nama' => 'Noor Abdul Haris, S.T., M.Kom.', 'nidn' => '0014077902', 'gelar' => 'S.T., M.Kom.', 'email' => 'noor.haris@ti.unsa.ac.id', 'lab' => 'Lab Algoritma & Struktur Data'],
            ['code' => 'RAB', 'nama' => 'Ramadan Adianto Budiman, S.S., M.Li.', 'nidn' => '0028108801', 'gelar' => 'S.S., M.Li.', 'email' => 'ramadan.budiman@ti.unsa.ac.id', 'lab' => 'Lab Bahasa & Komunikasi'],
            ['code' => 'CPR', 'nama' => 'Cicilia Puji Rahayu, S.Si., M.Sc.', 'nidn' => '0009038202', 'gelar' => 'S.Si., M.Sc.', 'email' => 'cicilia.rahayu@ti.unsa.ac.id', 'lab' => 'Lab Matematika Komputasi'],
            ['code' => 'HN', 'nama' => 'Hendri Noviyanto, S.Kom., M.Kom.', 'nidn' => '0016098603', 'gelar' => 'S.Kom., M.Kom.', 'email' => 'hendri.noviyanto@ti.unsa.ac.id', 'lab' => 'Lab Keamanan Jaringan'],
            ['code' => 'NEP', 'nama' => 'Nurita Elfani Prasetyaningrum, S.E., M.Si.', 'nidn' => '0025128401', 'gelar' => 'S.E., M.Si.', 'email' => 'nurita.elfani@ti.unsa.ac.id', 'lab' => 'Lab Akuntansi & Bisnis'],
            ['code' => 'PE', 'nama' => 'Dr. Praptiestrini, S.Pd., S.E., M.M.', 'nidn' => '0002017302', 'gelar' => 'Dr., S.Pd., S.E., M.M.', 'email' => 'praptiestrini@ti.unsa.ac.id', 'lab' => 'Lab Bahasa Indonesia & Humaniora'],
            ['code' => 'FO', 'nama' => 'Frido Oktaviandre, S.Kom., M.M.S.I', 'nidn' => '0011118705', 'gelar' => 'S.Kom., M.M.S.I', 'email' => 'frido.oktaviandre@ti.unsa.ac.id', 'lab' => 'Lab Basis Data & Web'],
            ['code' => 'IOC', 'nama' => 'Ika Oktaria Cahyaningrum, S.S., M.Hum.', 'nidn' => '0018108602', 'gelar' => 'S.S., M.Hum.', 'email' => 'ika.oktaria@ti.unsa.ac.id', 'lab' => 'Lab Bahasa Inggris'],
            ['code' => 'MAK', 'nama' => 'Moh. Abdul Kholik, S.Kom., M.Kom.', 'nidn' => '0022038901', 'gelar' => 'S.Kom., M.Kom.', 'email' => 'abdul.kholik@ti.unsa.ac.id', 'lab' => 'Lab Sistem Informasi'],
            ['code' => 'JM', 'nama' => 'Jaka Maryanto, S.H., M.Si., M.Th.', 'nidn' => '0013067604', 'gelar' => 'S.H., M.Si., M.Th.', 'email' => 'jaka.maryanto@ti.unsa.ac.id', 'lab' => 'Lab Pendidikan Agama Kristen'],
            ['code' => 'CNW', 'nama' => 'Christina Nur Wijayanti, S.Sos., M.I.Kom', 'nidn' => '0007058503', 'gelar' => 'S.Sos., M.I.Kom', 'email' => 'christina.nur@ti.unsa.ac.id', 'lab' => 'Lab Pendidikan Agama Katholik'],
            ['code' => 'IDK', 'nama' => 'I Desak ketut Titis Ary Laksanti, S.S., M.A.', 'nidn' => '0019088402', 'gelar' => 'S.S., M.A.', 'email' => 'desak.titis@ti.unsa.ac.id', 'lab' => 'Lab Bahasa Indonesia'],
        ];

        $lecturerMap = [];
        foreach ($lecturersData as $d) {
            $lecturer = Lecturer::updateOrCreate(
                ['nidn' => $d['nidn']],
                [
                    'nama' => $d['nama'],
                    'gelar' => $d['gelar'],
                    'email' => $d['email'],
                    'laboratorium' => $d['lab'],
                    'is_active' => true,
                ]
            );
            $lecturerMap[$d['code']] = $lecturer;
            $lecturerMap[$d['nama']] = $lecturer;
        }

        // 3. Daftar Ruang Kuliah
        $roomsData = [
            ['kode' => 'B.45', 'nama' => 'Ruang Kelas B.45', 'gedung' => 'Gedung Kuliah Bersama FT Lt. 2', 'kapasitas' => 50, 'jenis' => 'kelas'],
            ['kode' => 'B.44', 'nama' => 'Ruang Kelas B.44', 'gedung' => 'Gedung Kuliah Bersama FT Lt. 2', 'kapasitas' => 50, 'jenis' => 'kelas'],
            ['kode' => 'B.43', 'nama' => 'Ruang Kelas B.43', 'gedung' => 'Gedung Kuliah Bersama FT Lt. 2', 'kapasitas' => 50, 'jenis' => 'kelas'],
            ['kode' => 'Lab 1', 'nama' => 'Laboratorium Komputer 1', 'gedung' => 'Gedung TI Lt. 2', 'kapasitas' => 45, 'jenis' => 'laboratorium'],
            ['kode' => 'Lab 2', 'nama' => 'Laboratorium Komputer 2', 'gedung' => 'Gedung TI Lt. 2', 'kapasitas' => 40, 'jenis' => 'laboratorium'],
            ['kode' => 'Lab 3', 'nama' => 'Laboratorium Komputer 3', 'gedung' => 'Gedung TI Lt. 3', 'kapasitas' => 38, 'jenis' => 'laboratorium'],
            ['kode' => 'Lab J', 'nama' => 'Lab Jaringan Komputer', 'gedung' => 'Gedung TI Lt. 1', 'kapasitas' => 35, 'jenis' => 'laboratorium'],
            ['kode' => 'Lab M', 'nama' => 'Lab Multimedia & Grafis', 'gedung' => 'Gedung TI Lt. 3', 'kapasitas' => 35, 'jenis' => 'laboratorium'],
            ['kode' => 'FISIP', 'nama' => 'Gedung Bersama FISIP', 'gedung' => 'Fakultas FISIP Universitas Surakarta', 'kapasitas' => 60, 'jenis' => 'kelas'],
            ['kode' => 'w', 'nama' => 'Ruang Seminar / Workshop', 'gedung' => 'Gedung TI Lt. 1', 'kapasitas' => 40, 'jenis' => 'kelas'],
        ];

        $roomMap = [];
        foreach ($roomsData as $r) {
            $room = Room::updateOrCreate(
                ['kode' => $r['kode']],
                [
                    'nama' => $r['nama'],
                    'gedung' => $r['gedung'],
                    'kapasitas' => $r['kapasitas'],
                    'jenis' => $r['jenis'],
                    'is_active' => true,
                ]
            );
            $roomMap[$r['kode']] = $room;
            $roomMap[str_replace('.', ' ', $r['kode'])] = $room;
            $roomMap[trim($r['kode'])] = $room;
        }

        // 4. Daftar Mata Kuliah
        $coursesData = [
            ['kode' => 'MKWU002', 'nama' => 'Pendidikan Pancasila', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922406', 'nama' => 'Riset Operasi', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922512', 'nama' => 'Cinematografi', 'sks' => 2, 'sem' => 7, 'tipe' => 'pilihan'],
            ['kode' => '1922213', 'nama' => 'Teknologi Internet', 'sks' => 3, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922705', 'nama' => 'Pemrograman Mobile II', 'sks' => 3, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922709', 'nama' => 'Keamanan Jaringan', 'sks' => 3, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922703', 'nama' => 'Akuntansi Dasar', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922307', 'nama' => 'Desain Basis Data', 'sks' => 3, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922305', 'nama' => 'E-commerce', 'sks' => 3, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922507', 'nama' => 'Pengujian Perangkat Lunak', 'sks' => 2, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922619', 'nama' => 'Jaringan Komputer III', 'sks' => 3, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => 'MKWP005', 'nama' => 'Kalkulus', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922107', 'nama' => 'Matematika Diskrit', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => 'MKWU005', 'nama' => 'Bahasa Inggris I', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => 'MKWP003', 'nama' => 'Pengantar Informatika', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922604', 'nama' => 'Hukum Teknologi dan Etika Profesi', 'sks' => 2, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922603', 'nama' => 'Technopreneurship', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922304', 'nama' => 'Sistem Operasi', 'sks' => 2, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922301', 'nama' => 'Struktur Data', 'sks' => 3, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922314', 'nama' => 'Jaringan Komputer I', 'sks' => 3, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922707', 'nama' => 'Animasi Kartun', 'sks' => 3, 'sem' => 7, 'tipe' => 'pilihan'],
            ['kode' => '1922609', 'nama' => 'Kecerdasan Buatan II', 'sks' => 3, 'sem' => 7, 'tipe' => 'pilihan'],
            ['kode' => 'MKWP001', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 3, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922413', 'nama' => 'Digital Marketing', 'sks' => 3, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922611', 'nama' => 'Multimedia III', 'sks' => 3, 'sem' => 7, 'tipe' => 'pilihan'],
            ['kode' => '1922303', 'nama' => 'Sistem Informasi I', 'sks' => 2, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => '1922505', 'nama' => 'Desain Perangkat Lunak', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => 'MKWU001', 'nama' => 'Pendidikan Agama Islam', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => 'MKWU001-K', 'nama' => 'Pendidikan Agama Kristen', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => 'MKWU001-C', 'nama' => 'Pendidikan Agama Katholik', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922607', 'nama' => 'Web Framework', 'sks' => 3, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922615', 'nama' => 'Produksi Berita', 'sks' => 3, 'sem' => 7, 'tipe' => 'pilihan'],
            ['kode' => '1922401', 'nama' => 'Sistem Informasi II', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => 'MKWP004', 'nama' => 'Computer Technition', 'sks' => 3, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922310', 'nama' => 'Pemodelan 3D', 'sks' => 3, 'sem' => 3, 'tipe' => 'pilihan'],
            ['kode' => '1922601', 'nama' => 'Bahasa Indonesia', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922621', 'nama' => 'Pemrograman Client Server', 'sks' => 3, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922309', 'nama' => 'Bahasa Inggris III', 'sks' => 2, 'sem' => 3, 'tipe' => 'wajib'],
            ['kode' => 'MKWP002', 'nama' => 'Internet of Things', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922506', 'nama' => 'Interaksi Manusia dan Komputer', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922517', 'nama' => 'Komunikasi Data', 'sks' => 2, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => 'MKWP006', 'nama' => 'Komunikasi Data', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922114', 'nama' => 'Desain Grafis', 'sks' => 2, 'sem' => 5, 'tipe' => 'wajib'],
            ['kode' => '1922110', 'nama' => 'Aplikasi Perkantoran', 'sks' => 2, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922202', 'nama' => 'Teknisi Komputer', 'sks' => 3, 'sem' => 1, 'tipe' => 'wajib'],
            ['kode' => '1922602', 'nama' => 'Kerja Praktik', 'sks' => 2, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922704', 'nama' => 'KKL', 'sks' => 2, 'sem' => 7, 'tipe' => 'wajib'],
            ['kode' => '1922801', 'nama' => 'Skripsi', 'sks' => 6, 'sem' => 7, 'tipe' => 'wajib'],
        ];

        $courseMap = [];
        foreach ($coursesData as $c) {
            $course = Course::updateOrCreate(
                ['kode' => $c['kode']],
                [
                    'nama' => $c['nama'],
                    'sks' => $c['sks'],
                    'semester_ideal' => $c['sem'],
                    'tipe' => $c['tipe'],
                    'kategori' => 'Kurikulum Gasal 2026/2027',
                    'deskripsi' => 'Mata kuliah ' . $c['nama'] . ' semester ' . $c['sem'],
                    'is_active' => true,
                ]
            );
            $courseMap[$c['kode']] = $course;
            $courseMap[$c['nama']] = $course;
        }

        // Hapus jadwal lama dari tahun akademik ini jika ada agar bersih dan tidak duplikat
        Schedule::where('academic_year_id', $academicYear->id)->delete();

        // Helper function to insert schedule
        $insertSchedule = function (
            $hari,
            $shift,
            $jamMulai,
            $jamSelesai,
            $kelas,
            $courseCode,
            $roomCode,
            $lecturerKey,
            $catatan = null
        ) use ($academicYear, $courseMap, $roomMap, $lecturerMap, $adminUser) {
            $course = $courseMap[$courseCode] ?? null;
            if (!$course) {
                // try to search by code
                $course = Course::where('kode', $courseCode)->orWhere('nama', 'LIKE', "%$courseCode%")->first();
            }

            // Normalise room key
            $cleanRoom = trim(str_replace(' ', '', $roomCode));
            $room = $roomMap[$roomCode] ?? $roomMap[$cleanRoom] ?? Room::where('kode', 'LIKE', "%$cleanRoom%")->first() ?? Room::first();

            // Parse semester & angkatan from kelas
            $semester = 1;
            $angkatan = 2026;
            if (str_starts_with($kelas, '1') || str_contains($kelas, 'Smt I')) {
                $semester = 1;
                $angkatan = 2026;
            } elseif (str_starts_with($kelas, '3') || str_contains($kelas, 'Smt III')) {
                $semester = 3;
                $angkatan = 2025;
            } elseif (str_starts_with($kelas, '5') || str_contains($kelas, 'Smt V')) {
                $semester = 5;
                $angkatan = 2024;
            } elseif (str_starts_with($kelas, '7') || str_contains($kelas, 'Smt VII')) {
                $semester = 7;
                $angkatan = 2023;
            }

            if (!$course) return;

            $schedule = Schedule::create([
                'academic_year_id' => $academicYear->id,
                'course_id' => $course->id,
                'room_id' => $room->id,
                'kelas' => $kelas,
                'shift' => $shift,
                'angkatan' => $angkatan,
                'semester' => $semester,
                'jumlah_mahasiswa' => 40,
                'hari' => $hari,
                'jam_mulai' => $jamMulai . ':00',
                'jam_selesai' => $jamSelesai . ':00',
                'catatan' => $catatan,
                'created_by' => $adminUser->id,
            ]);

            $lecturer = $lecturerMap[$lecturerKey] ?? Lecturer::where('nama', 'LIKE', "%$lecturerKey%")->first();
            if ($lecturer) {
                $schedule->lecturers()->attach($lecturer->id, ['peran' => 'koordinator', 'urutan' => 1]);
            }
        };

        // ==========================================
        // 5. JADWAL KELAS PAGI (SEMESTER GASAL 2026/2027)
        // ==========================================

        // --- SENIN (Hari = 1) ---
        $insertSchedule(1, 'pagi', '08:00', '09:40', '1 S1', 'MKWU002', 'B.45', 'Muhammad Taufik Rusydi, S.Kom., M.H.');
        $insertSchedule(1, 'pagi', '08:00', '09:40', '5 S1', '1922406', 'B.44', 'Noor Abdul Haris, S.T., M.Kom.');
        $insertSchedule(1, 'pagi', '08:00', '09:40', '7 S1-M', '1922512', 'Lab M', 'Dr. Sukoco, S.Si., M.Si., M.Kom.');
        $insertSchedule(1, 'pagi', '08:00', '10:30', '3KelB', '1922213', 'Lab 2', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(1, 'pagi', '08:00', '10:30', '7S1-S', '1922705', 'Lab 1', 'Dr. Abdillah Baradja, S.Kom., M.Eng.');
        $insertSchedule(1, 'pagi', '08:00', '10:30', '7S1-N', '1922709', 'Lab J', 'Hendri Noviyanto, S.Kom., M.Kom.');
        $insertSchedule(1, 'pagi', '09:40', '11:20', '5 S1', '1922703', 'B.44', 'Nurita Elfani Prasetyaningrum, S.E., M.Si.');
        $insertSchedule(1, 'pagi', '10:30', '13:00', '3KelA', '1922307', 'Lab J', 'Frido Oktaviandre, S.Kom., M.M.S.I');
        $insertSchedule(1, 'pagi', '10:30', '13:00', '3KelB', '1922305', 'Lab 2', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(1, 'pagi', '10:30', '12:10', '7S1-S', '1922507', 'Lab 1', 'Jani Kusanti, S.Kom., M.Cs.');
        $insertSchedule(1, 'pagi', '10:30', '13:00', '7S1-N', '1922619', 'Lab J', 'Hendri Noviyanto, S.Kom., M.Kom.');
        $insertSchedule(1, 'pagi', '11:00', '12:40', '1 S1', 'MKWP005', 'B.45', 'Cicilia Puji Rahayu, S.Si., M.Sc.');
        $insertSchedule(1, 'pagi', '13:00', '14:40', '5 S1', '1922107', 'B.44', 'Noor Abdul Haris, S.T., M.Kom.');
        $insertSchedule(1, 'pagi', '13:00', '14:40', '1 S1', 'MKWU005', 'B.45', 'Ika Oktaria Cahyaningrum, S.S., M.Hum.');

        // --- SELASA (Hari = 2) ---
        $insertSchedule(2, 'pagi', '08:00', '09:40', '1 S1', 'MKWP003', 'Lab 3', 'Dr. Sukoco, S.Si., M.Si., M.Kom.');
        $insertSchedule(2, 'pagi', '08:00', '09:40', '7 S1', '1922604', 'w', 'Muhammad Taufik Rusydi, S.Kom., M.H.');
        $insertSchedule(2, 'pagi', '08:00', '09:40', '5 S1', '1922603', 'Lab 2', 'Ramadhian Agus Triono S., S.Ag., S.Kom., M.M.');
        $insertSchedule(2, 'pagi', '08:00', '09:40', '3 S1', '1922304', 'Lab 1', 'Frido Oktaviandre, S.Kom., M.M.S.I');
        $insertSchedule(2, 'pagi', '09:40', '12:10', '3KelA', '1922301', 'Lab 3', 'Noor Abdul Haris, S.T., M.Kom.');
        $insertSchedule(2, 'pagi', '09:40', '12:10', '3KelB', '1922314', 'Lab J', 'Agus Rianto, S.T., M.T.');
        $insertSchedule(2, 'pagi', '09:40', '12:10', '7S1-M', '1922707', 'Lab M', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(2, 'pagi', '09:40', '12:10', '7S1-S', '1922609', 'B.43', 'Jani Kusanti, S.Kom., M.Cs.');
        $insertSchedule(2, 'pagi', '10:30', '13:00', '1KelA', 'MKWP001', 'Lab 1', 'Ir. Tri Irianto Tjendrowasono, M.T.');
        $insertSchedule(2, 'pagi', '11:00', '13:30', '5KelB', '1922413', 'Lab 2', 'Ramadhian Agus Triono S., S.Ag., S.Kom., M.M.');
        $insertSchedule(2, 'pagi', '12:10', '14:40', '7S1-M', '1922611', 'Lab M', 'Dr. Sukoco, S.Si., M.Si., M.Kom.');
        $insertSchedule(2, 'pagi', '12:10', '13:50', '3 S1', '1922303', 'Lab 3', 'Moh. Abdul Kholik, S.Kom., M.Kom.');
        $insertSchedule(2, 'pagi', '13:00', '15:30', '1KelB', 'MKWP001', 'Lab 1', 'Ir. Tri Irianto Tjendrowasono, M.T.');

        // --- RABU (Hari = 3) ---
        $insertSchedule(3, 'pagi', '08:00', '09:40', '5 S1', '1922505', 'B.43', 'Jani Kusanti, S.Kom., M.Cs.');
        $insertSchedule(3, 'pagi', '08:00', '10:30', '3KelB', '1922301', 'Lab J', 'Noor Abdul Haris, S.T., M.Kom.');
        $insertSchedule(3, 'pagi', '08:00', '10:30', '3KelA', '1922305', 'Lab 2', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(3, 'pagi', '08:00', '10:30', '1 S1', 'MKWU001', 'B.45', 'Ramadhian Agus Triono S., S.Ag., S.Kom., M.M.');
        $insertSchedule(3, 'pagi', '08:00', '10:30', '1 S1', 'MKWU001-K', 'FISIP', 'Jaka Maryanto, S.H., M.Si., M.Th.');
        $insertSchedule(3, 'pagi', '08:00', '10:30', '1 S1', 'MKWU001-C', 'FISIP', 'Christina Nur Wijayanti, S.Sos., M.I.Kom');
        $insertSchedule(3, 'pagi', '09:40', '12:00', '7S1-S', '1922607', 'Lab 2', 'Frido Oktaviandre, S.Kom., M.M.S.I');
        $insertSchedule(3, 'pagi', '09:40', '12:00', '7S1-M', '1922615', 'Lab M', 'Dr. Sukoco, S.Si., M.Si., M.Kom.');
        $insertSchedule(3, 'pagi', '09:40', '11:20', '5 S1', '1922401', 'B.44', 'Dr. Abdillah Baradja, S.Kom., M.Eng.');
        $insertSchedule(3, 'pagi', '10:30', '13:00', '1KelA', 'MKWP004', 'Lab 1', 'Ir. Tri Irianto Tjendrowasono, M.T.');
        $insertSchedule(3, 'pagi', '10:30', '13:00', '3KelA', '1922310', 'Lab 2', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(3, 'pagi', '11:20', '13:00', '5 S1', '1922601', 'B.44', 'I Desak ketut Titis Ary Laksanti, S.S., M.A.');
        $insertSchedule(3, 'pagi', '12:00', '14:30', '7S1-N', '1922621', 'Lab J', 'Hendri Noviyanto, S.Kom., M.Kom.');
        $insertSchedule(3, 'pagi', '13:00', '15:30', '1KelB', 'MKWP004', 'Lab 1', 'Ir. Tri Irianto Tjendrowasono, M.T.');
        $insertSchedule(3, 'pagi', '13:00', '14:30', '3 S1', '1922309', 'B.45', 'Ika Oktaria Cahyaningrum, S.S., M.Hum.');

        // --- KAMIS (Hari = 4) ---
        $insertSchedule(4, 'pagi', '08:00', '10:30', '3KelA', '1922314', 'Lab J', 'Agus Rianto, S.T., M.T.');
        $insertSchedule(4, 'pagi', '08:00', '10:30', '3KelB', '1922310', 'Lab M', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(4, 'pagi', '08:00', '10:30', '5KelA', '1922413', 'Lab 2', 'Ramadhian Agus Triono S., S.Ag., S.Kom., M.M.');
        $insertSchedule(4, 'pagi', '08:00', '09:40', '1 S1', 'MKWP002', 'B.44', 'Agus Rianto, S.T., M.T.');
        $insertSchedule(4, 'pagi', '10:30', '12:00', '5 S1', '1922506', 'B.44', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(4, 'pagi', '10:30', '12:00', '7S1-N', '1922517', 'Lab J', 'Agus Rianto, S.T., M.T.');
        $insertSchedule(4, 'pagi', '10:30', '12:00', '1 S1', 'MKWP006', 'Lab J', 'Agus Rianto, S.T., M.T.');
        $insertSchedule(4, 'pagi', '10:30', '13:00', '3KelB', '1922307', 'Lab 1', 'Frido Oktaviandre, S.Kom., M.M.S.I');
        $insertSchedule(4, 'pagi', '10:30', '13:00', '3KelA', '1922213', 'Lab 3', 'Bayu Mukti, S.Kom., M.Kom.');
        $insertSchedule(4, 'pagi', '12:00', '13:40', '5 S1', '1922114', 'Lab 1', 'Dr. Sukoco, S.Si., M.Si., M.Kom.');

        // --- JUM'AT (Hari = 5) ---
        $insertSchedule(5, 'pagi', '08:00', '11:00', '7 S1', '1922602', 'w', 'Dr. Sukoco, S.Si., M.Si., M.Kom.', 'Kerja Praktik');
        $insertSchedule(5, 'pagi', '08:00', '11:00', '7 S1', '1922704', 'w', 'Dr. Abdillah Baradja, S.Kom., M.Eng.', 'KKL');
        $insertSchedule(5, 'pagi', '13:30', '16:30', '7 S1', '1922801', 'w', 'Ir. Tri Irianto Tjendrowasono, M.T.', 'Bimbingan & Ujian Skripsi');


        // ==========================================
        // 6. JADWAL KELAS SORE (SEMESTER GASAL 2026/2027)
        // ==========================================

        // --- PERIODE 1 ---
        // RABU (Hari = 3)
        $insertSchedule(3, 'sore', '16:00', '17:40', 'Smt I (Sore P1)', 'MKWU001', 'B.44', 'RATS', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '16:00', '17:40', 'Smt III (Sore P1)', '1922303', 'B.43', 'AB', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '16:00', '17:40', 'Smt V (Sore P1)', '1922114', 'Lab 1', 'BM', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '16:00', '17:40', 'Smt VII (Sore P1)', '1922604', 'B.45', 'MTR', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '18:00', '19:40', 'Smt I (Sore P1)', 'MKWP002', 'Lab 1', 'AR', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '18:00', '19:40', 'Smt III (Sore P1)', '1922304', 'B.44', 'FO', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '18:00', '19:40', 'Smt V (Sore P1)', '1922401', 'B.45', 'AB', 'Periode 1 (16 Sep - 11 Nov 2026)');
        $insertSchedule(3, 'sore', '18:00', '19:40', 'Smt VII (Sore P1)', '1922507', 'Lab 2', 'JK', 'Periode 1 (16 Sep - 11 Nov 2026)');

        // KAMIS (Hari = 4)
        $insertSchedule(4, 'sore', '16:00', '18:50', 'Smt I (Sore P1)', 'MKWP001', 'Lab 1', 'IT', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '16:00', '18:30', 'Smt III (Sore P1)', '1922301', 'Lab 2', 'NAH', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '16:00', '17:40', 'Smt V (Sore P1)', '1922703', 'B.45', 'NEP', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '16:00', '18:30', 'Smt VII (Sore P1)', '1922607', 'B.45', 'FO', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '16:00', '18:30', 'Smt VII (Sore P1)', '1922611', 'B.44', 'SK', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '16:00', '18:30', 'Smt VII (Sore P1)', '1922619', 'Lab J', 'AR', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '18:00', '19:40', 'Smt I (Sore P1)', 'MKWU005', 'B.43', 'RAB', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '18:30', '21:00', 'Smt III (Sore P1)', '1922305', 'Lab 2', 'BM', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '18:00', '19:40', 'Smt V (Sore P1)', '1922603', 'B.44', 'RATS', 'Periode 1 (17 Sep - 12 Nov 2026)');
        $insertSchedule(4, 'sore', '18:00', '19:40', 'Smt VII (Sore P1)', '1922512', 'Lab 1', 'SK', 'Periode 1 (17 Sep - 12 Nov 2026)');

        // JUMAT (Hari = 5)
        $insertSchedule(5, 'sore', '16:00', '17:40', 'Smt I (Sore P1)', 'MKWP003', 'B.44', 'AB', 'Periode 1 (18 Sep - 13 Nov 2026)');
        $insertSchedule(5, 'sore', '16:00', '18:30', 'Smt III (Sore P1)', '1922307', 'Lab 1', 'FO', 'Periode 1 (18 Sep - 13 Nov 2026)');
        $insertSchedule(5, 'sore', '16:00', '17:40', 'Smt V (Sore P1)', '1922505', 'B.43', 'JK', 'Periode 1 (18 Sep - 13 Nov 2026)');
        $insertSchedule(5, 'sore', '16:00', '18:30', 'Smt VII (Sore P1)', '1922707', 'Lab M', 'BM', 'Periode 1 (18 Sep - 13 Nov 2026)');

        // --- PERIODE 2 ---
        // RABU (Hari = 3)
        $insertSchedule(3, 'sore', '16:00', '18:30', 'Smt I (Sore P2)', 'MKWP004', 'Lab 1', 'IT', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '16:00', '18:30', 'Smt I (Sore P2)', '1922202', 'Lab 1', 'IT', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '16:00', '18:30', 'Smt III (Sore P2)', '1922310', 'Lab 2', 'BM', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '16:00', '17:40', 'Smt V (Sore P2)', '1922601', 'B.43', 'PE', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '16:00', '18:30', 'Smt VII (Sore P2)', '1922705', 'B.44', 'AB', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '16:00', '18:30', 'Smt VII (Sore P2)', '1922621', 'Lab J', 'AR', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '18:30', '20:10', 'Smt I (Sore P2)', 'MKWP005', 'B.45', 'CPR', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '18:30', '21:00', 'Smt III (Sore P2)', '1922213', 'Lab 1', 'BM', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '18:30', '21:00', 'Smt V (Sore P2)', '1922413', 'Lab 1', 'RATS', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');
        $insertSchedule(3, 'sore', '18:30', '21:00', 'Smt VII (Sore P2)', '1922709', 'Lab J', 'HN', 'Periode 2 (18 Nov 2026 - 6 Jan 2027)');

        // KAMIS (Hari = 4)
        $insertSchedule(4, 'sore', '16:00', '17:40', 'Smt I (Sore P2)', 'MKWP006', 'Lab J', 'AR', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '16:00', '17:40', 'Smt III (Sore P2)', '1922309', 'B.44', 'RAB', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '16:00', '17:40', 'Smt V (Sore P2)', '1922107', 'B.43', 'JK', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '16:00', '17:40', 'Smt VII (Sore P2)', '1922517', 'Lab J', 'AR', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '16:00', '18:30', 'Smt VII (Sore P2)', '1922615', 'Lab 1', 'SK', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '17:40', '20:10', 'Smt III (Sore P2)', '1922314', 'Lab J', 'AR', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '18:00', '19:40', 'Smt I (Sore P2)', 'MKWU002', 'B.44', 'MTR', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '18:00', '19:40', 'Smt V (Sore P2)', '1922506', 'B.45', 'SK', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');
        $insertSchedule(4, 'sore', '18:30', '21:00', 'Smt VII (Sore P2)', '1922609', 'B.45', 'JK', 'Periode 2 (19 Nov 2026 - 7 Jan 2027)');

        // JUMAT (Hari = 5)
        $insertSchedule(5, 'sore', '16:00', '17:40', 'Smt I (Sore P2)', '1922110', 'B.44', 'FO', 'Periode 2 (20 Nov 2026 - 8 Jan 2027)');
        $insertSchedule(5, 'sore', '16:00', '17:40', 'Smt V (Sore P2)', '1922406', 'B.43', 'NAH', 'Periode 2 (20 Nov 2026 - 8 Jan 2027)');
        $insertSchedule(5, 'sore', '18:00', '20:00', 'Smt V (Sore P2)', '1922602', 'w', 'SK', 'Kerja Praktik');
        $insertSchedule(5, 'sore', '18:00', '20:00', 'Smt VII (Sore P2)', '1922704', 'w', 'AB', 'KKL');
        $insertSchedule(5, 'sore', '18:00', '21:00', 'Smt VII (Sore P2)', '1922801', 'w', 'IT', 'Skripsi');
    }
}
