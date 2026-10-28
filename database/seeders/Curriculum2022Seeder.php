<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Schedule;

class Curriculum2022Seeder extends Seeder
{
    public function run(): void
    {
        $curriculum = [
            // ==========================================
            // SEMESTER 1 (Total: 20 SKS)
            // ==========================================
            [
                'kode' => '1922101',
                'nama' => 'Pendidikan Agama Islam',
                'singkatan' => 'PAI',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Penguatan akidah, syariah, dan akhlak dalam kehidupan bermasyarakat dan etika berprofesi di bidang teknologi.'
            ],
            [
                'kode' => '1922102',
                'nama' => 'Pendidikan Agama Kristen',
                'singkatan' => 'PAK',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Penguatan iman kristiani dan pengabdian dalam pengembangan ilmu pengetahuan dan teknologi.'
            ],
            [
                'kode' => '1922103',
                'nama' => 'Pendidikan Agama Katholik',
                'singkatan' => 'PAKat',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Pendidikan nilai-nilai iman Katolik, moralitas, dan tanggung jawab sosial sarjana teknologi.'
            ],
            [
                'kode' => '1922104',
                'nama' => 'Pendidikan Agama Hindu',
                'singkatan' => 'PAH',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Pemahaman ajaran Tri Hita Karana dan dharma dalam berkehidupan serta pemanfaatan teknologi.'
            ],
            [
                'kode' => '1922105',
                'nama' => 'Pendidikan Agama Budha',
                'singkatan' => 'PAB',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Penerapan ajaran Buddha, kebijaksanaan, dan welas asih dalam pengembangan sains dan teknologi.'
            ],
            [
                'kode' => '1922106',
                'nama' => 'Bahasa Inggris I',
                'singkatan' => 'ENG1',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Bahasa & Komunikasi',
                'deskripsi' => 'Tata bahasa dasar, kosa kata komputasi dan teknik membaca literatur teknologi informasi berbahasa Inggris.'
            ],
            [
                'kode' => '1922107',
                'nama' => 'Matematika Diskrit',
                'singkatan' => 'MD',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Matematika & Komputasi',
                'deskripsi' => 'Himpunan, relasi, fungsi, teori graf, pohon, dan kombinatorika sebagai landasan struktur algoritma.'
            ],
            [
                'kode' => '1922108',
                'nama' => 'Algoritma dan Pemrograman I',
                'singkatan' => 'ALPRO1',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Konsep dasar algoritma, flowchart, pseudocode, tipe data, struktur kendali, dan modularisasi.'
            ],
            [
                'kode' => '1922109',
                'nama' => 'Praktikum Algoritma dan Pemrograman I',
                'singkatan' => 'PALPRO1',
                'sks' => 1,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Implementasi langsung algoritma dasar menggunakan bahasa pemrograman C/C++ di laboratorium komputer.'
            ],
            [
                'kode' => '1922110',
                'nama' => 'Aplikasi Perkantoran',
                'singkatan' => 'AP',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Aplikasi & Produktivitas',
                'deskripsi' => 'Penguasaan perangkat lunak pengolah kata, lembar sebar, presentasi tingkat lanjut, dan kolaborasi cloud.'
            ],
            [
                'kode' => '1922111',
                'nama' => 'Pengantar Informatika',
                'singkatan' => 'PI',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Informatika Dasar',
                'deskripsi' => 'Wawasan ekosistem ilmu informatika, sejarah komputasi, hardware, software, jaringan, dan tren masa depan.'
            ],
            [
                'kode' => '1922202',
                'nama' => 'Teknisi Komputer',
                'singkatan' => 'TK',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Hardware & Arsitektur',
                'deskripsi' => 'Arsitektur PC, perakitan, troubleshooting hardware, instalasi sistem operasi, dan perawatan perangkat.'
            ],
            [
                'kode' => '1922203',
                'nama' => 'Praktikum Teknisi Komputer',
                'singkatan' => 'PTK',
                'sks' => 1,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Hardware & Arsitektur',
                'deskripsi' => 'Praktik merakit PC, diagnosa kerusakan komponen, instalasi driver, dan optimasi kinerja komputer.'
            ],
            [
                'kode' => '1922114',
                'nama' => 'Desain Grafis',
                'singkatan' => 'DG',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Prinsip desain visual, tipografi, warna, dan pengolahan citra vektor serta bitmap (Photoshop/Illustrator).'
            ],
            [
                'kode' => '1922115',
                'nama' => 'Pendidikan Pancasila',
                'singkatan' => 'PP',
                'sks' => 2,
                'sem' => 1,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Ideologi bangsa, nilai luhur Pancasila, kebangsaan, dan penerapannya dalam era transformasi digital.'
            ],

            // ==========================================
            // SEMESTER 2 (Total: 20 SKS)
            // ==========================================
            [
                'kode' => '1922201',
                'nama' => 'Bahasa Inggris II',
                'singkatan' => 'ENG2',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Bahasa & Komunikasi',
                'deskripsi' => 'Kecakapan presentasi teknis, komunikasi profesional, dan penulisan dokumentasi teknis dalam bahasa Inggris.'
            ],
            [
                'kode' => '1922112',
                'nama' => 'Multimedia I',
                'singkatan' => 'MM1',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Pengenalan elemen multimedia: teks, audio, video, gambar, dan integrasi konten digital interaktif.'
            ],
            [
                'kode' => '1922113',
                'nama' => 'Praktikum Multimedia I',
                'singkatan' => 'PMM1',
                'sks' => 1,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Praktik produksi aset multimedia, editing audio-video, dan penyusunan prototipe media digital interaktif.'
            ],
            [
                'kode' => '1922204',
                'nama' => 'Teknik Digital',
                'singkatan' => 'TD',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Hardware & Arsitektur',
                'deskripsi' => 'Sistem bilangan, aljabar Boolean, gerbang logika, rangkaian kombinasional, flip-flop, dan register.'
            ],
            [
                'kode' => '1922205',
                'nama' => 'Animasi 2D I',
                'singkatan' => 'A2D1',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Prinsip 12 animasi, pembuatan karakter 2 dimensi, storyboard, dan animasi berbasis frame & vector.'
            ],
            [
                'kode' => '1922206',
                'nama' => 'Praktikum Animasi 2D I',
                'singkatan' => 'PA2D1',
                'sks' => 1,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Praktik pembuatan animasi 2D menggunakan software industri (Animate/Toon Boom) di laboratorium komputer.'
            ],
            [
                'kode' => '1922207',
                'nama' => 'Rekayasa Perangkat Lunak',
                'singkatan' => 'RPL',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Siklus hidup perangkat lunak (SDLC), metodologi Agile/Scrum, pemodelan UML, dan jaminan mutu software.'
            ],
            [
                'kode' => '1922208',
                'nama' => 'Logika Informatika',
                'singkatan' => 'LI',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Matematika & Komputasi',
                'deskripsi' => 'Logika proposisi, logika predikat, pembuktian formal, inferensi logika, dan penerapannya pada komputasi.'
            ],
            [
                'kode' => '1922211',
                'nama' => 'Algoritma dan Pemrograman II',
                'singkatan' => 'ALPRO2',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Rekursi, pointer, manipulasi berkas (file I/O), algoritma searching, dan sorting lanjutan.'
            ],
            [
                'kode' => '1922212',
                'nama' => 'Praktikum Algoritma dan Pemrograman II',
                'singkatan' => 'PALPRO2',
                'sks' => 1,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Eksperimen laboratorium penerapan algoritma kompleks, manipulasi memori, dan pembuatan aplikasi modular.'
            ],
            [
                'kode' => '1922312',
                'nama' => 'Pemrograman Berorientasi Objek',
                'singkatan' => 'PBO',
                'sks' => 2,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Paradigma OOP: Class, Object, Encapsulation, Inheritance, Polymorphism, Interface, dan Exception Handling.'
            ],
            [
                'kode' => '1922313',
                'nama' => 'Praktikum Pemrograman Berorientasi Objek',
                'singkatan' => 'PPBO',
                'sks' => 1,
                'sem' => 2,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Praktik pemrograman Java/C# berorientasi objek dalam perancangan aplikasi modular skala menengah.'
            ],

            // ==========================================
            // SEMESTER 3 (Total: 24 SKS)
            // ==========================================
            [
                'kode' => '1922301',
                'nama' => 'Struktur Data',
                'singkatan' => 'SD',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Array, Linked List, Stack, Queue, Tree, Binary Search Tree, Graph, dan Hashing.'
            ],
            [
                'kode' => '1922302',
                'nama' => 'Praktikum Struktur Data',
                'singkatan' => 'PSD',
                'sks' => 1,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman & Algoritma',
                'deskripsi' => 'Pengkodean struktur data dinamis dan analisis kompleksitas algoritma (Big O Notation) di lab.'
            ],
            [
                'kode' => '1922303',
                'nama' => 'Sistem Informasi I',
                'singkatan' => 'SI1',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Sistem Informasi',
                'deskripsi' => 'Komponen sistem informasi, organisasi bisnis, rantai nilai, dan peran TI dalam pengambilan keputusan manajerial.'
            ],
            [
                'kode' => '1922304',
                'nama' => 'Sistem Operasi',
                'singkatan' => 'SO',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Sistem & Jaringan',
                'deskripsi' => 'Manajemen proses, thread, penjadwalan CPU, memori virtual, file system, deadlock, dan arsitektur Linux/Unix.'
            ],
            [
                'kode' => '1922305',
                'nama' => 'E-Commerce',
                'singkatan' => 'EC',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Sistem Informasi & Bisnis',
                'deskripsi' => 'Model bisnis digital (B2B, B2C, C2C), payment gateway, keamanan transaksi online, dan arsitektur toko online.'
            ],
            [
                'kode' => '1922306',
                'nama' => 'Praktikum E-Commerce',
                'singkatan' => 'PEC',
                'sks' => 1,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Sistem Informasi & Bisnis',
                'deskripsi' => 'Pembangunan platform e-commerce terintegrasi, konfigurasi katalog produk, dan gateway pembayaran digital.'
            ],
            [
                'kode' => '1922307',
                'nama' => 'Desain Basis Data',
                'singkatan' => 'DBD',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Basis Data',
                'deskripsi' => 'Model Entity-Relationship (ERD), normalisasi data (1NF-BCNF), perancangan konseptual, logikal, dan fisikal.'
            ],
            [
                'kode' => '1922308',
                'nama' => 'Praktikum Desain Basis Data',
                'singkatan' => 'PDBD',
                'sks' => 1,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Basis Data',
                'deskripsi' => 'Implementasi DDL, DML, relasi tabel foreign key, dan query kompleks menggunakan RDBMS MySQL/PostgreSQL.'
            ],
            [
                'kode' => '1922309',
                'nama' => 'Bahasa Inggris III',
                'singkatan' => 'ENG3',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Bahasa & Komunikasi',
                'deskripsi' => 'Penulisan proposal teknis, artikel ilmiah, abstrak penelitian komputasi, dan persiapan TOEFL/IELTS.'
            ],
            [
                'kode' => '1922310',
                'nama' => 'Pemodelan 3D',
                'singkatan' => 'P3D',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Teori geometri ruang, vertex, poligon, mesh, texturing, pencahayaan, dan rigging model 3D (Blender).'
            ],
            [
                'kode' => '1922311',
                'nama' => 'Praktikum Pemodelan 3D',
                'singkatan' => 'PP3D',
                'sks' => 1,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Praktik modeling objek 3D arsitektural dan karakter, pemberian material, serta rendering fotorealistik di lab.'
            ],
            [
                'kode' => '1922213',
                'nama' => 'Teknologi Internet',
                'singkatan' => 'TI',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Web & Internet',
                'deskripsi' => 'Protokol TCP/IP, DNS, HTTP/HTTPS, Web Server, arsitektur client-server, dan fondasi jaringan internet global.'
            ],
            [
                'kode' => '1922214',
                'nama' => 'Praktikum Teknologi Internet',
                'singkatan' => 'PTI',
                'sks' => 1,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Web & Internet',
                'deskripsi' => 'Konfigurasi web server Apache/Nginx, domain virtual host, setup SSL, dan troubleshooting koneksi internet.'
            ],
            [
                'kode' => '1922314',
                'nama' => 'Jaringan Komputer I',
                'singkatan' => 'JK1',
                'sks' => 2,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Jaringan Komputer',
                'deskripsi' => 'Model OSI 7 Layer, pengkabelan UTP, IPv4 addressing, subnetting (CIDR/VLSM), dan dasar switching.'
            ],
            [
                'kode' => '1922315',
                'nama' => 'Praktikum Jaringan Komputer I',
                'singkatan' => 'PJK1',
                'sks' => 1,
                'sem' => 3,
                'tipe' => 'wajib',
                'kategori' => 'Jaringan Komputer',
                'deskripsi' => 'Praktik crimping kabel LAN, konfigurasi switch, setup LAN fisik, dan simulasi topologi Cisco Packet Tracer.'
            ],

            // ==========================================
            // SEMESTER 4 (Total: 23 SKS)
            // ==========================================
            [
                'kode' => '1922503',
                'nama' => 'Kecakapan Antar Personal',
                'singkatan' => 'KAP',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Soft Skills & Kepemimpinan',
                'deskripsi' => 'Komunikasi interpersonal, resolusi konflik, negosiasi, kepemimpinan tim, dan kolaborasi tim proyek TI.'
            ],
            [
                'kode' => '1922402',
                'nama' => 'Analisis Sistem',
                'singkatan' => 'AS',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Sistem Informasi',
                'deskripsi' => 'Analisis kebutuhan sistem (requirement engineering), Data Flow Diagram (DFD), use case, dan kelayakan sistem.'
            ],
            [
                'kode' => '1922403',
                'nama' => 'Pemrograman I',
                'singkatan' => 'PROG1',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman',
                'deskripsi' => 'Konsep pemrograman berorientasi solusi, perancangan antarmuka grafis (GUI desktop), dan integrasi event-driven.'
            ],
            [
                'kode' => '1922404',
                'nama' => 'Praktikum Pemrograman I',
                'singkatan' => 'PPROG1',
                'sks' => 1,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Pemrograman',
                'deskripsi' => 'Pembuatan aplikasi desktop interaktif berbasis C# .NET atau Java Swing di laboratorium komputer.'
            ],
            [
                'kode' => '1922405',
                'nama' => 'Metodologi Penelitian',
                'singkatan' => 'METPEN',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Penelitian & Skripsi',
                'deskripsi' => 'Perumusan masalah penelitian, studi literatur, metodologi riset komputasi, dan penyusunan proposal skripsi.'
            ],
            [
                'kode' => '1922504',
                'nama' => 'Kalkulus',
                'singkatan' => 'KAL',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Matematika & Komputasi',
                'deskripsi' => 'Fungsi, limit, turunan, integral diferensial, dan penerapannya dalam pemodelan sinyal dan algoritma komputasi.'
            ],
            [
                'kode' => '1922407',
                'nama' => 'Pemrograman Web I',
                'singkatan' => 'WEB1',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Rekayasa Web',
                'deskripsi' => 'HTML5 semantik, modern CSS3 (Flexbox/Grid), dasar JavaScript, dan desain responsif mobile-friendly.'
            ],
            [
                'kode' => '1922408',
                'nama' => 'Praktikum Pemrograman Web I',
                'singkatan' => 'PWEB1',
                'sks' => 1,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Rekayasa Web',
                'deskripsi' => 'Pembuatan landing page responsif, manipulasi DOM JavaScript, dan styling web interaktif di lab komputer.'
            ],
            [
                'kode' => '1922409',
                'nama' => 'Animasi 3D I',
                'singkatan' => 'A3D1',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Prinsip animasi 3D, keyframing, timeline animation, pergerakan kamera, dan teknik pencahayaan adegan.'
            ],
            [
                'kode' => '1922410',
                'nama' => 'Praktikum Animasi 3D I',
                'singkatan' => 'PA3D1',
                'sks' => 1,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Praktik penganimasian objek dan karakter 3 dimensi dengan software Blender/Maya di lab multimedia.'
            ],
            [
                'kode' => '1922411',
                'nama' => 'Kecerdasan Buatan I',
                'singkatan' => 'AI1',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Kecerdasan Buatan',
                'deskripsi' => 'Pencarian ruang keadaan (BFS, DFS, A*), sistem pakar (Expert System), logika fuzzy, dan representasi pengetahuan.'
            ],
            [
                'kode' => '1922412',
                'nama' => 'Praktikum Kecerdasan Buatan I',
                'singkatan' => 'PAI1',
                'sks' => 1,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Kecerdasan Buatan',
                'deskripsi' => 'Implementasi algoritma pencarian rute, inferensi sistem pakar, dan logika fuzzy menggunakan Python di lab.'
            ],
            [
                'kode' => '1922501',
                'nama' => 'Pengolahan Citra Digital',
                'singkatan' => 'PCD',
                'sks' => 2,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Kecerdasan Buatan & Multimedia',
                'deskripsi' => 'Operasi titik, konvolusi spasial, filtering noise, deteksi tepi (Sobel, Canny), dan segmentasi citra digital.'
            ],
            [
                'kode' => '1922502',
                'nama' => 'Praktikum Pengolahan Citra Digital',
                'singkatan' => 'PPCD',
                'sks' => 1,
                'sem' => 4,
                'tipe' => 'wajib',
                'kategori' => 'Kecerdasan Buatan & Multimedia',
                'deskripsi' => 'Praktik pengolahan gambar digital dan ekstraksi fitur visual menggunakan pustaka OpenCV dan Python di lab.'
            ],

            // ==========================================
            // SEMESTER 5 (Total: 20 SKS)
            // ==========================================
            [
                'kode' => '1922413',
                'nama' => 'Digital Marketing',
                'singkatan' => 'DM',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Bisnis & Manajemen',
                'deskripsi' => 'Strategi pemasaran digital, Search Engine Optimization (SEO), Social Media Marketing (SMM), dan web analytics.'
            ],
            [
                'kode' => '1922414',
                'nama' => 'Praktikum Digital Marketing',
                'singkatan' => 'PDM',
                'sks' => 1,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Bisnis & Manajemen',
                'deskripsi' => 'Kampanye iklan digital, riset kata kunci Google Ads/Trends, dan optimasi performa digital brand di lab.'
            ],
            [
                'kode' => '1922401',
                'nama' => 'Sistem Informasi II',
                'singkatan' => 'SI2',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Sistem Informasi',
                'deskripsi' => 'Enterprise Resource Planning (ERP), Customer Relationship Management (CRM), dan integrasi sistem korporasi.'
            ],
            [
                'kode' => '1922406',
                'nama' => 'Riset Operasi',
                'singkatan' => 'RO',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Matematika & Optimasi',
                'deskripsi' => 'Pemodelan linear programming, metode simpleks, masalah transportasi, penugasan, dan teori antrian.'
            ],
            [
                'kode' => '1922505',
                'nama' => 'Desain Perangkat Lunak',
                'singkatan' => 'DPL',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Design pattern (Creational, Structural, Behavioral), arsitektur Clean/Layered, dan refactoring kode software.'
            ],
            [
                'kode' => '1922506',
                'nama' => 'Interaksi Manusia dan Komputer',
                'singkatan' => 'IMK',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'User Interface (UI) dan User Experience (UX), Design Thinking, prototipe Figma, dan usability testing.'
            ],
            [
                'kode' => '1922601',
                'nama' => 'Bahasa Indonesia',
                'singkatan' => 'BIND',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Kaidah Ejaan Bahasa Indonesia (EBI), penulisan karya ilmiah teknologi, perumusan argumen, dan sitasi akademik.'
            ],
            [
                'kode' => '1922602',
                'nama' => 'Kerja Praktik',
                'singkatan' => 'KP',
                'sks' => 3,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Praktik Lapangan',
                'deskripsi' => 'Magang kerja profesional di instansi atau industri teknologi untuk mengaplikasikan keilmuan informatika.'
            ],
            [
                'kode' => '1922603',
                'nama' => 'Technopreneurship',
                'singkatan' => 'TECH',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Kewirausahaan',
                'deskripsi' => 'Inkubasi startup berbasis teknologi, Business Model Canvas (BMC), validasi pasar, dan pitching investor.'
            ],
            [
                'kode' => '1922703',
                'nama' => 'Akuntansi Dasar',
                'singkatan' => 'AD',
                'sks' => 2,
                'sem' => 5,
                'tipe' => 'wajib',
                'kategori' => 'Bisnis & Manajemen',
                'deskripsi' => 'Persamaan dasar akuntansi, jurnal umum, buku besar, neraca saldo, dan penyusunan laporan keuangan usaha.'
            ],

            // ==========================================
            // SEMESTER 6 (5 Wajib = 10 SKS + Peminatan 9 SKS = 19 SKS)
            // ==========================================
            [
                'kode' => '1922702',
                'nama' => 'Pendidikan Kewarganegaraan',
                'singkatan' => 'PKN',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'wajib',
                'kategori' => 'Mata Kuliah Wajib Umum',
                'deskripsi' => 'Hak dan kewajiban warga negara, demokrasi, wawasan nusantara, ketahanan nasional, dan bela negara digital.'
            ],
            [
                'kode' => '1922704',
                'nama' => 'KKL',
                'singkatan' => 'KKL',
                'sks' => 3,
                'sem' => 6,
                'tipe' => 'wajib',
                'kategori' => 'Praktik Lapangan',
                'deskripsi' => 'Kuliah Kerja Lapangan: kunjungan industri dan studi komparasi teknologi pada institusi teknologi terkemuka.'
            ],
            [
                'kode' => '1922701',
                'nama' => 'Manajemen Proyek',
                'singkatan' => 'MANPRO',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'wajib',
                'kategori' => 'Manajemen Proyek',
                'deskripsi' => 'Perencanaan proyek TI, estimasi biaya (COCOMO), penjadwalan (Gantt Chart/CPM), manajemen risiko, dan evaluasi.'
            ],
            [
                'kode' => '1922209',
                'nama' => 'Basis Data',
                'singkatan' => 'BD',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'wajib',
                'kategori' => 'Basis Data',
                'deskripsi' => 'Transaksi basis data (ACID), stored procedure, trigger, index optimization, dan pengantar database NoSQL.'
            ],
            [
                'kode' => '1922210',
                'nama' => 'Praktikum Basis Data',
                'singkatan' => 'PBD',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'wajib',
                'kategori' => 'Basis Data',
                'deskripsi' => 'Pembuatan trigger, stored procedure, tuning performa query, dan manajemen backup-restore database di lab.'
            ],

            // ==========================================
            // SEMESTER 7 (1 Wajib = 2 SKS + Peminatan 11 SKS = 13 SKS)
            // ==========================================
            [
                'kode' => '1922604',
                'nama' => 'Hukum Teknologi dan Etika Profesi',
                'singkatan' => 'HTEP',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'wajib',
                'kategori' => 'Etika & Hukum TI',
                'deskripsi' => 'UU ITE, perlindungan data pribadi (PDP), Hak Kekayaan Intelektual (HAKI), lisensi open source, dan kode etik ACM/IEEE.'
            ],

            // ==========================================
            // SEMESTER 8 (Total: 6 SKS)
            // ==========================================
            [
                'kode' => '1922801',
                'nama' => 'Skripsi',
                'singkatan' => 'SKRIPSI',
                'sks' => 6,
                'sem' => 8,
                'tipe' => 'wajib',
                'kategori' => 'Tugas Akhir',
                'deskripsi' => 'Tugas akhir mandiri mahasiswa berupa karya ilmiah dan pengembangan produk teknologi informasi berbobot sarjana.'
            ],

            // ==========================================
            // PEMINATAN SISTEM INFORMASI (13 MK - 20 SKS)
            // ==========================================
            [
                'kode' => '1922507',
                'nama' => 'Pengujian Perangkat Lunak',
                'singkatan' => 'PPL',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Black-box testing, white-box testing, automated unit testing, end-to-end testing, dan pelaporan bug software.'
            ],
            [
                'kode' => '1922508',
                'nama' => 'Pemrograman II',
                'singkatan' => 'PROG2',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Pemrograman multi-threading, socket network, integrasi REST API, dan konektivitas database enterprise.'
            ],
            [
                'kode' => '1922509',
                'nama' => 'Praktikum Pemrograman II',
                'singkatan' => 'PPROG2',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Implementasi sistem aplikasi modular berbasis network service dan integrasi API eksternal di lab.'
            ],
            [
                'kode' => '1922510',
                'nama' => 'Pemrograman Web II',
                'singkatan' => 'WEB2',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Pemrograman web sisi server (Server-Side), pengelolaan session, otentikasi JWT, dan interaksi basis data dinamis.'
            ],
            [
                'kode' => '1922511',
                'nama' => 'Praktikum Pemrograman Web II',
                'singkatan' => 'PWEB2',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Pembangunan aplikasi web dinamis dengan autentikasi multi-user, upload berkas, dan dashboard analitik di lab.'
            ],
            [
                'kode' => '1922605',
                'nama' => 'Pemrograman Mobile I',
                'singkatan' => 'MOB1',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Pengembangan aplikasi mobile Android natif (Kotlin) atau cross-platform (Flutter), layout UI, dan activity lifecycle.'
            ],
            [
                'kode' => '1922606',
                'nama' => 'Praktikum Pemrograman Mobile I',
                'singkatan' => 'PMOB1',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Praktik coding aplikasi Android di smartphone, penggunaan widget, dan penyimpanan lokal SQLite di lab.'
            ],
            [
                'kode' => '1922607',
                'nama' => 'Web Framework',
                'singkatan' => 'FRAME',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Arsitektur MVC pada web framework modern (Laravel/Django), routing, middleware, ORM Eloquent, dan RESTful API.'
            ],
            [
                'kode' => '1922608',
                'nama' => 'Praktikum Web Framework',
                'singkatan' => 'PFRAME',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Pembuatan web aplikasi fullstack berbasis framework Laravel dan pengujian endpoint API dengan Postman di lab.'
            ],
            [
                'kode' => '1922609',
                'nama' => 'Kecerdasan Buatan II',
                'singkatan' => 'AI2',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Machine Learning: Supervised learning, Unsupervised learning, evaluasi model klasifikasi, dan Natural Language Processing (NLP).'
            ],
            [
                'kode' => '1922610',
                'nama' => 'Praktikum Kecerdasan Buatan II',
                'singkatan' => 'PAI2',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Implementasi model machine learning menggunakan Scikit-Learn dan Pandas Python pada dataset riil di lab.'
            ],
            [
                'kode' => '1922705',
                'nama' => 'Pemrograman Mobile II',
                'singkatan' => 'MOB2',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Integrasi API cloud di aplikasi mobile, push notification, geolocation GPS, dan deployment ke Google Play Store.'
            ],
            [
                'kode' => '1922706',
                'nama' => 'Praktikum Pemrograman Mobile II',
                'singkatan' => 'PMOB2',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Sistem Informasi',
                'deskripsi' => 'Pembuatan aplikasi mobile terkoneksi Firebase, sinkronisasi cloud real-time, dan build APK siap produksi di lab.'
            ],

            // ==========================================
            // PEMINATAN MULTIMEDIA (13 MK - 20 SKS)
            // ==========================================
            [
                'kode' => '1922512',
                'nama' => 'Cinematografi',
                'singkatan' => 'CINEMA',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Teknik pengambilan gambar sinematik, komposisi visual, sudut kamera (camera angle), dan pencahayaan studio film.'
            ],
            [
                'kode' => '1922513',
                'nama' => 'Multimedia II',
                'singkatan' => 'MM2',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Pengolahan audio profesional, sound design, efek suara, editing video non-linear lanjutan (Premiere/DaVinci).'
            ],
            [
                'kode' => '1922514',
                'nama' => 'Praktikum Multimedia II',
                'singkatan' => 'PMM2',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Perekaman audio berkualitas studio, color grading video, dan komposing efek visual di lab multimedia.'
            ],
            [
                'kode' => '1922515',
                'nama' => 'Animasi 3D II',
                'singkatan' => 'A3D2',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Rigging kerangka karakter 3D lanjutan, inverse kinematics (IK), dynamic simulation (kain, partikel, fluida), dan motion capture.'
            ],
            [
                'kode' => '1922516',
                'nama' => 'Praktikum Animasi 3D II',
                'singkatan' => 'PA3D2',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Praktik animasi karakter bertutur (lip-sync), interaksi fisik objek 3D, dan rendering animasi resolusi tinggi di lab.'
            ],
            [
                'kode' => '1922613',
                'nama' => 'Produksi Iklan',
                'singkatan' => 'IKLAN',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Konsep kreatif periklanan komersial, penyusunan copywriting, strategi penayangan, dan produksi TVC / media sosial.'
            ],
            [
                'kode' => '1922614',
                'nama' => 'Praktikum Produksi Iklan',
                'singkatan' => 'PIKLAN',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Pembuatan video iklan komersial dari tahapan pra-produksi, syuting, hingga pasca-produksi final di lab.'
            ],
            [
                'kode' => '1922611',
                'nama' => 'Multimedia III',
                'singkatan' => 'MM3',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Virtual Reality (VR), Augmented Reality (AR), dan teknologi media imersif interaktif berbasis game engine.'
            ],
            [
                'kode' => '1922612',
                'nama' => 'Praktikum Multimedia III',
                'singkatan' => 'PMM3',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Pembuatan aplikasi Augmented Reality mobile menggunakan Unity dan Vuforia di laboratorium multimedia.'
            ],
            [
                'kode' => '1922615',
                'nama' => 'Produksi Berita',
                'singkatan' => 'BERITA',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Jurnalistik multimedia, reportase berita digital, teknik wawancara, penulisan naskah siaran, dan etika penyiaran.'
            ],
            [
                'kode' => '1922616',
                'nama' => 'Praktikum Produksi Berita',
                'singkatan' => 'PBERITA',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Simulasi siaran studio berita televisi, live reporting lapangan, dan editing paket berita audio-visual di lab.'
            ],
            [
                'kode' => '1922707',
                'nama' => 'Animasi Kartun',
                'singkatan' => 'KARTUN',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Eksplorasi gaya animasi kartun komedi/edukasi, ekspresi wajah ekstrem, dan penyusunan serial kartun pendek.'
            ],
            [
                'kode' => '1922708',
                'nama' => 'Praktikum Animasi Kartun',
                'singkatan' => 'PKARTUN',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Multimedia',
                'deskripsi' => 'Produksi film kartun animasi pendek berdurasi 3-5 menit lengkap dengan audio dialog dan sound effect di lab.'
            ],

            // ==========================================
            // PEMINATAN JARINGAN KOMPUTER (13 MK - 20 SKS)
            // ==========================================
            [
                'kode' => '1922517',
                'nama' => 'Komunikasi Data',
                'singkatan' => 'KOMDAT',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Transmisi sinyal analog & digital, modulasi, multiplexing, deteksi error (CRC), dan media transmisi kabel & nirkabel.'
            ],
            [
                'kode' => '1922518',
                'nama' => 'Perancangan Jaringan',
                'singkatan' => 'PJ',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Metodologi desain jaringan kampus/perusahaan (PPDIOO), perancangan hierarki core-distribution-access, dan redundancy.'
            ],
            [
                'kode' => '1922519',
                'nama' => 'Praktikum Perancangan Jaringan',
                'singkatan' => 'PPJ',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Simulasi perancangan infrastruktur jaringan skala enterprise menggunakan Cisco Packet Tracer dan GNS3 di lab.'
            ],
            [
                'kode' => '1922620A',
                'nama' => 'Jaringan Komputer II',
                'singkatan' => 'JK2',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Routing dinamis (OSPF, EIGRP, BGP), Virtual LAN (VLAN), Spanning Tree Protocol (STP), dan Access Control List (ACL).'
            ],
            [
                'kode' => '1922621A',
                'nama' => 'Praktikum Jaringan Komputer II',
                'singkatan' => 'PJK2',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Konfigurasi router dan switch Cisco riil pada rack laboratorium, setup inter-VLAN routing dan OSPF di lab.'
            ],
            [
                'kode' => '1922617',
                'nama' => 'Teknologi Sistem Operasi',
                'singkatan' => 'TSO',
                'sks' => 2,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Administrasi server Linux, shell scripting, manajemen permission, systemd service, dan hardening server jaringan.'
            ],
            [
                'kode' => '1922618',
                'nama' => 'Praktikum Teknologi Sistem Operasi',
                'singkatan' => 'PTSO',
                'sks' => 1,
                'sem' => 6,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Implementasi server Linux Debian/Ubuntu, otomatisasi bash script, dan konfigurasi daemon server di lab.'
            ],
            [
                'kode' => '1922619',
                'nama' => 'Cloud Computing',
                'singkatan' => 'CLOUD',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Model layanan cloud (IaaS, PaaS, SaaS), virtualisasi hypervisor, containerization Docker, dan orkestrasi Kubernetes.'
            ],
            [
                'kode' => '1922620B',
                'nama' => 'Praktikum Cloud Computing',
                'singkatan' => 'PCLOUD',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Deploy container Docker, konfigurasi cluster virtual machine, dan manajemen cloud instance (AWS/GCP/Proxmox) di lab.'
            ],
            [
                'kode' => '1922621B',
                'nama' => 'Pemrograman Client Server',
                'singkatan' => 'PCS',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Arsitektur client-server, socket programming TCP/UDP, remote procedure call (RPC), dan komunikasi pesan real-time.'
            ],
            [
                'kode' => '1922622',
                'nama' => 'Praktikum Pemrograman Client Server',
                'singkatan' => 'PPCS',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Pembangunan aplikasi chat client-server dan file transfer berbasis socket programming di lab.'
            ],
            [
                'kode' => '1922709',
                'nama' => 'Keamanan Jaringan',
                'singkatan' => 'KAMJAR',
                'sks' => 2,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Kriptografi, konfigurasi firewall, Intrusion Detection/Prevention System (IDS/IPS), VPN tunneling, dan audit kerentanan jaringan.'
            ],
            [
                'kode' => '1922710',
                'nama' => 'Praktikum Keamanan Jaringan',
                'singkatan' => 'PKAMJAR',
                'sks' => 1,
                'sem' => 7,
                'tipe' => 'pilihan',
                'kategori' => 'Peminatan Jaringan Komputer',
                'deskripsi' => 'Praktik pengujian penetrasi (PenTest), analisis paket Wireshark, setup Snort IDS, dan pengamanan server di lab.'
            ],
        ];

        // Mapping from old placeholder codes to new official curriculum codes
        $codeReplacements = [
            'MKWU001'   => '1922101', // Pendidikan Agama Islam
            'MKWU001-K' => '1922102', // Pendidikan Agama Kristen
            'MKWU001-C' => '1922103', // Pendidikan Agama Katholik
            'MKWU002'   => '1922115', // Pendidikan Pancasila
            'MKWU005'   => '1922106', // Bahasa Inggris I
            'MKWP001'   => '1922108', // Algoritma dan Pemrograman I
            'MKWP002'   => '1922213', // Teknologi Internet
            'MKWP003'   => '1922111', // Pengantar Informatika
            'MKWP004'   => '1922202', // Teknisi Komputer
            'MKWP005'   => '1922504', // Kalkulus
            'MKWP006'   => '1922517', // Komunikasi Data
            '1922621'   => '1922621B', // Pemrograman Client Server (Official Peminatan)
            'EEA66053'  => '1922510', // Pemrograman Web II
            'EEA64022'  => '1922207', // Rekayasa Perangkat Lunak
            'EEA68099'  => '1922609', // Kecerdasan Buatan II
            'EEA65014'  => '1922620A', // Jaringan Komputer II
            'EEA62011'  => '1922301', // Struktur Data
            'EEA67041'  => '1922209', // Basis Data
            'EEA66034'  => '1922617', // Teknologi Sistem Operasi
            'EEA64032'  => '1922709', // Keamanan Jaringan
        ];

        $insertedCount = 0;
        $updatedCount = 0;

        foreach ($curriculum as $item) {
            $course = Course::where('kode', $item['kode'])->first();
            if ($course) {
                $course->update([
                    'nama' => $item['nama'],
                    'singkatan' => $item['singkatan'],
                    'sks' => $item['sks'],
                    'semester_ideal' => $item['sem'],
                    'tipe' => $item['tipe'],
                    'kategori' => $item['kategori'],
                    'deskripsi' => $item['deskripsi'],
                    'is_active' => true,
                ]);
                $updatedCount++;
            } else {
                Course::create([
                    'kode' => $item['kode'],
                    'nama' => $item['nama'],
                    'singkatan' => $item['singkatan'],
                    'sks' => $item['sks'],
                    'semester_ideal' => $item['sem'],
                    'tipe' => $item['tipe'],
                    'kategori' => $item['kategori'],
                    'deskripsi' => $item['deskripsi'],
                    'is_active' => true,
                ]);
                $insertedCount++;
            }
        }

        // Remap any schedules pointing to placeholder codes
        foreach ($codeReplacements as $oldCode => $newCode) {
            $oldCourse = Course::where('kode', $oldCode)->first();
            $newCourse = Course::where('kode', $newCode)->first();

            if ($oldCourse && $newCourse && $oldCourse->id !== $newCourse->id) {
                Schedule::where('course_id', $oldCourse->id)->update(['course_id' => $newCourse->id]);
                // Delete the old placeholder course
                $oldCourse->forceDelete();
            }
        }

        echo "Curriculum 2022 seeded successfully!\n";
        echo "Inserted: {$insertedCount}, Updated: {$updatedCount}, Total Courses: " . Course::count() . "\n";
    }
}
