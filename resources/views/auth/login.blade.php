<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal Akademik - SI Jadwal TI Universitas Surakarta</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F5F9] font-sans text-[#161A30] antialiased min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 selection:bg-[#5B50E5] selection:text-white">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl border border-[#E5E7EB] overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- LEFT BRANDING & INFORMATION PANEL (5 cols) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-[#4233cc] via-[#5B50E5] to-[#6063ee] p-6 sm:p-8 lg:p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Ambient Graphic Glow -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -top-16 w-60 h-60 rounded-full bg-black/10 blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- University Badge -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-inner flex-shrink-0">
                        <span class="material-symbols-outlined text-[24px] sm:text-[26px]">terminal</span>
                    </div>
                    <div>
                        <div class="font-extrabold text-sm sm:text-base tracking-tight leading-tight">SI Jadwal Perkuliahan</div>
                        <div class="text-xs text-[#e8e4ff]">Jurusan Teknik Informatika Universitas Surakarta</div>
                    </div>
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-[#e8e4ff] text-xs font-semibold mb-4 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
                    <span>Portal Mahasiswa & Dosen Terpadu</span>
                </div>

                <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight leading-snug mb-3">
                    Akses Jadwal Kuliah & Plotting Ruang Belajar
                </h2>

                <p class="text-xs sm:text-sm text-[#e8e4ff] leading-relaxed mb-6">
                    Sistem informasi penjadwalan perkuliahan semester aktif Teknik Informatika Universitas Surakarta. Mahasiswa dapat melihat jadwal kelas pagi & sore, ruang kuliah, serta dosen pengampu secara lengkap.
                </p>

                <!-- Feature Highlights -->
                <div class="space-y-3 pt-2 border-t border-white/15">
                    <div class="flex items-center gap-3 text-xs text-[#e8e4ff]">
                        <span class="material-symbols-outlined text-[18px] text-[#89ceff]">school</span>
                        <span>Akses Mahasiswa: Melihat Jadwal Kuliah & Ruang</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-[#e8e4ff]">
                        <span class="material-symbols-outlined text-[18px] text-[#89ceff]">schedule</span>
                        <span>Jadwal Real-Time Zona Waktu WIB (UTC+7)</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-[#e8e4ff]">
                        <span class="material-symbols-outlined text-[18px] text-[#89ceff]">filter_alt</span>
                        <span>Dukungan Shift Kelas Pagi & Kelas Sore</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-[#e8e4ff]">
                        <span class="material-symbols-outlined text-[18px] text-[#89ceff]">meeting_room</span>
                        <span>Ruang Teori & Laboratorium FT Universitas Surakarta</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Info Strip -->
            <div class="relative z-10 pt-6 border-t border-white/15 flex items-center justify-between text-[11px] text-[#e8e4ff]">
                <span>T.A. {{ $activeYear?->tahun ?? '2026/2027' }} {{ ucfirst($activeYear?->semester ?? 'ganjil') }} (Aktif)</span>
                <span>PDDIKTI Terhubung</span>
            </div>
        </div>

        <!-- RIGHT FORM PANEL (7 cols) -->
        <div class="lg:col-span-7 p-6 sm:p-8 lg:p-10 flex flex-col justify-between bg-white">
            <div>
                <!-- FLASH ERROR NOTIFICATION -->
                @if(session('error'))
                    <div class="mb-5 p-3.5 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#EF4444] text-xs font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 p-3.5 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#059669] text-xs font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- ROLE SELECTOR SWITCH TABS (3 ROLES: MAHASISWA, DOSEN, ADMIN) -->
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-[#8A94A6] uppercase tracking-wider">Peran Pengguna</span>
                        <span class="text-[11px] text-[#059669] font-bold" id="activeRoleText">Masuk sebagai Mahasiswa</span>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 p-1.5 rounded-2xl bg-[#F4F5F9] border border-[#E5E7EB]/60">
                        <!-- TAB MAHASISWA -->
                        <button type="button" id="tabMahasiswa" 
                                class="py-2 px-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-[#059669] text-white shadow-md shadow-[#059669]/20">
                            <span class="material-symbols-outlined text-[17px]">school</span>
                            <span>Mahasiswa</span>
                        </button>

                        <!-- TAB DOSEN -->
                        <button type="button" id="tabDosen" 
                                class="py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[17px]">badge</span>
                            <span>Dosen</span>
                        </button>

                        <!-- TAB ADMIN -->
                        <button type="button" id="tabAdmin" 
                                class="py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[17px]">admin_panel_settings</span>
                            <span>Admin</span>
                        </button>
                    </div>
                </div>

                <!-- FORM HEADER -->
                <div class="mb-5">
                    <h3 class="text-xl font-extrabold text-[#161A30] tracking-tight" id="formHeading">
                        Masuk Menggunakan NIM
                    </h3>
                    <p class="text-xs text-[#8A94A6] mt-0.5" id="formSubheading">
                        Masukkan Nomor Induk Mahasiswa (NIM) dan kata sandi akun Anda.
                    </p>
                </div>

                <!-- LOGIN FORM -->
                <form action="{{ route('login.post') }}" method="POST" id="loginForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="mahasiswa">

                    <!-- IDENTITAS (NIM MAHASISWA / NIDN DOSEN / USERNAME ADMIN) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label id="identityLabel" class="block text-xs font-bold text-[#161A30]">
                                Nomor Induk Mahasiswa (NIM)
                            </label>
                            
                            <!-- QUICK STUDENT SELECTOR HELPER -->
                            <div class="relative" id="studentPickerContainer">
                                <button type="button" onclick="toggleStudentDropdown()" class="text-[11px] text-[#059669] hover:underline font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">person_search</span>
                                    <span>Pilih Mahasiswa</span>
                                </button>
                                
                                <div id="studentDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-[#E5E7EB] p-2 z-40 max-h-60 overflow-y-auto">
                                    <div class="px-2 py-1 text-[10px] font-bold text-[#8A94A6] uppercase tracking-wider border-b border-[#E5E7EB] mb-1">
                                        Klik untuk mengisi NIM:
                                    </div>
                                    @foreach($students ?? [] as $stu)
                                    <button type="button" onclick="selectStudent('{{ $stu->username }}', '{{ addslashes($stu->name) }}')" 
                                            class="w-full text-left px-2.5 py-1.5 rounded-xl hover:bg-[#F4F5F9] transition-colors flex items-center gap-2 text-xs">
                                        <div class="w-6 h-6 rounded-full bg-[#ecfdf5] text-[#059669] font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($stu->name, 0, 2)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-[#161A30] truncate text-[11px]">{{ $stu->name }}</div>
                                            <div class="text-[10px] text-[#8A94A6] font-mono">NIM: {{ $stu->username }}</div>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- QUICK LECTURER SELECTOR HELPER -->
                            <div class="relative hidden" id="lecturerPickerContainer">
                                <button type="button" onclick="toggleLecturerDropdown()" class="text-[11px] text-[#5B50E5] hover:underline font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">person_search</span>
                                    <span>Pilih Dosen Terdaftar</span>
                                </button>
                                
                                <div id="lecturerDropdown" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-[#E5E7EB] p-2 z-40 max-h-60 overflow-y-auto">
                                    <div class="px-2 py-1 text-[10px] font-bold text-[#8A94A6] uppercase tracking-wider border-b border-[#E5E7EB] mb-1">
                                        Klik untuk mengisi NIDN:
                                    </div>
                                    @foreach($lecturers ?? [] as $lec)
                                    <button type="button" onclick="selectLecturer('{{ $lec->nidn }}', '{{ addslashes($lec->nama) }}')" 
                                            class="w-full text-left px-2.5 py-1.5 rounded-xl hover:bg-[#F4F5F9] transition-colors flex items-center gap-2 text-xs">
                                        <div class="w-6 h-6 rounded-full bg-[#ececff] text-[#5B50E5] font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($lec->nama, 0, 2)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-[#161A30] truncate text-[11px]">{{ $lec->nama }}</div>
                                            <div class="text-[10px] text-[#8A94A6] font-mono">NIDN: {{ $lec->nidn }}</div>
                                        </div>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-[#8A94A6] text-[18px] pointer-events-none" id="identityIcon">school</span>
                            <input type="text" name="nim" id="identityInput" required
                                   placeholder="Contoh: 220101001 atau 220101045" 
                                   value="{{ old('nim', old('nidn', old('username', ''))) }}"
                                   class="w-full bg-[#F4F5F9] text-[#161A30] placeholder:text-[#8A94A6] pl-10 pr-4 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#059669]/20 border border-transparent focus:border-[#059669]/30 transition-all font-mono">
                        </div>
                        <span id="identityHint" class="text-[11px] text-[#8A94A6] mt-1 block">
                            Gunakan NIM resmi mahasiswa Teknik Informatika. Mahasiswa hanya memiliki akses untuk melihat jadwal perkuliahan.
                        </span>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-[#161A30]">Kata Sandi</label>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-[#8A94A6] text-[18px] pointer-events-none">lock</span>
                            <input type="password" name="password" id="passwordInput" required
                                   placeholder="Masukkan kata sandi..." 
                                   class="w-full bg-[#F4F5F9] text-[#161A30] placeholder:text-[#8A94A6] pl-10 pr-10 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#5B50E5]/20 border border-transparent focus:border-[#5B50E5]/30 transition-all">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3 text-[#8A94A6] hover:text-[#161A30]">
                                <span class="material-symbols-outlined text-[18px]" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- REMEMBER ME -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-[#464555]">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#5B50E5] focus:ring-[#5B50E5]/30 border-[#E5E7EB]" checked>
                            <span>Ingat sesi login di perangkat ini</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[11px] text-[#5B50E5] hover:underline font-semibold">
                            Bantuan Akun?
                        </a>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit" id="submitBtn" class="w-full py-3 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-bold text-xs shadow-md shadow-[#059669]/25 flex items-center justify-center gap-2 transition-all mt-2">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span id="submitBtnText">Masuk sebagai Mahasiswa</span>
                    </button>
                </form>
            </div>

            <!-- FOOTER -->
            <div class="mt-6 pt-5 border-t border-[#F1F3F7]">
                <div class="flex items-center justify-between mt-5 pt-3 border-t border-[#F1F3F7] text-xs">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 font-semibold text-[#8A94A6] hover:text-[#5B50E5] transition-colors">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        <span>Kembali ke Beranda</span>
                    </a>

                    <a href="{{ route('jadwal.semua') }}" class="text-[#5B50E5] hover:underline font-bold">
                        Lihat Semua Jadwal →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const tabMahasiswa = document.getElementById('tabMahasiswa');
        const tabDosen = document.getElementById('tabDosen');
        const tabAdmin = document.getElementById('tabAdmin');
        const roleInput = document.getElementById('roleInput');
        const identityLabel = document.getElementById('identityLabel');
        const identityIcon = document.getElementById('identityIcon');
        const identityInput = document.getElementById('identityInput');
        const identityHint = document.getElementById('identityHint');
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const activeRoleText = document.getElementById('activeRoleText');
        const formHeading = document.getElementById('formHeading');
        const formSubheading = document.getElementById('formSubheading');
        const studentPickerContainer = document.getElementById('studentPickerContainer');
        const lecturerPickerContainer = document.getElementById('lecturerPickerContainer');

        function setRole(role) {
            roleInput.value = role;

            if (role === 'mahasiswa') {
                // Style Tabs
                tabMahasiswa.className = 'py-2 px-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-[#059669] text-white shadow-md shadow-[#059669]/20';
                tabDosen.className = 'py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5';
                tabAdmin.className = 'py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5';
                
                // Form Fields
                identityLabel.textContent = 'Nomor Induk Mahasiswa (NIM)';
                identityIcon.textContent = 'school';
                identityInput.name = 'nim';
                identityInput.placeholder = 'Contoh: 220101001 atau 220101045';
                identityInput.value = '';
                identityHint.textContent = 'Gunakan NIM resmi mahasiswa Teknik Informatika. Mahasiswa hanya memiliki akses untuk melihat jadwal perkuliahan.';
                
                // Button & Heading
                submitBtn.className = 'w-full py-3 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-bold text-xs shadow-md shadow-[#059669]/25 flex items-center justify-center gap-2 transition-all mt-2';
                submitBtnText.textContent = 'Masuk sebagai Mahasiswa';
                activeRoleText.textContent = 'Masuk sebagai Mahasiswa';
                activeRoleText.className = 'text-[11px] text-[#059669] font-bold';
                formHeading.textContent = 'Masuk Menggunakan NIM';
                formSubheading.textContent = 'Masukkan Nomor Induk Mahasiswa (NIM) dan kata sandi akun Anda.';
                
                if (studentPickerContainer) studentPickerContainer.classList.remove('hidden');
                if (lecturerPickerContainer) lecturerPickerContainer.classList.add('hidden');

            } else if (role === 'dosen') {
                // Style Tabs
                tabDosen.className = 'py-2 px-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/20';
                tabMahasiswa.className = 'py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5';
                tabAdmin.className = 'py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5';
                
                // Form Fields
                identityLabel.textContent = 'Nomor Induk Dosen Nasional (NIDN)';
                identityIcon.textContent = 'badge';
                identityInput.name = 'nidn';
                identityInput.placeholder = 'Contoh: 0015066401 atau 0021028704';
                identityInput.value = '';
                identityHint.textContent = 'Gunakan NIDN resmi yang terdaftar di jurusan TI Universitas Surakarta.';
                
                // Button & Heading
                submitBtn.className = 'w-full py-3 rounded-xl bg-[#5B50E5] hover:bg-[#4233cc] text-white font-bold text-xs shadow-md shadow-[#5B50E5]/25 flex items-center justify-center gap-2 transition-all mt-2';
                submitBtnText.textContent = 'Masuk ke Portal Dosen';
                activeRoleText.textContent = 'Masuk sebagai Dosen Pengajar';
                activeRoleText.className = 'text-[11px] text-[#5B50E5] font-bold';
                formHeading.textContent = 'Masuk Menggunakan NIDN';
                formSubheading.textContent = 'Masukkan Nomor Induk Dosen Nasional dan kata sandi akun Anda.';
                
                if (studentPickerContainer) studentPickerContainer.classList.add('hidden');
                if (lecturerPickerContainer) lecturerPickerContainer.classList.remove('hidden');

            } else {
                // Style Tabs
                tabAdmin.className = 'py-2 px-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-[#EF4444] text-white shadow-md shadow-[#EF4444]/20';
                tabMahasiswa.className = 'py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5';
                tabDosen.className = 'py-2 px-2 rounded-xl text-xs font-semibold text-[#464555] hover:text-[#161A30] transition-all flex items-center justify-center gap-1.5';
                
                // Form Fields
                identityLabel.textContent = 'Nama Pengguna Administrator';
                identityIcon.textContent = 'admin_panel_settings';
                identityInput.name = 'username';
                identityInput.placeholder = 'Contoh: admin';
                identityInput.value = 'admin';
                identityHint.textContent = 'Gunakan akun administrator pengelola jadwal jurusan.';
                
                // Button & Heading
                submitBtn.className = 'w-full py-3 rounded-xl bg-[#EF4444] hover:bg-[#dc2626] text-white font-bold text-xs shadow-md shadow-[#EF4444]/25 flex items-center justify-center gap-2 transition-all mt-2';
                submitBtnText.textContent = 'Masuk sebagai Administrator';
                activeRoleText.textContent = 'Masuk sebagai Administrator';
                activeRoleText.className = 'text-[11px] text-[#EF4444] font-bold';
                formHeading.textContent = 'Masuk Panel Administrator';
                formSubheading.textContent = 'Masukkan username admin dan kata sandi untuk mengelola data jadwal.';
                
                if (studentPickerContainer) studentPickerContainer.classList.add('hidden');
                if (lecturerPickerContainer) lecturerPickerContainer.classList.add('hidden');
            }
        }

        tabMahasiswa.addEventListener('click', () => setRole('mahasiswa'));
        tabDosen.addEventListener('click', () => setRole('dosen'));
        tabAdmin.addEventListener('click', () => setRole('admin'));

        function togglePasswordVisibility() {
            const pwd = document.getElementById('passwordInput');
            const eye = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                eye.textContent = 'visibility_off';
            } else {
                pwd.type = 'password';
                eye.textContent = 'visibility';
            }
        }

        function toggleStudentDropdown() {
            const dropdown = document.getElementById('studentDropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function selectStudent(nim, nama) {
            identityInput.value = nim;
            toggleStudentDropdown();
        }

        function toggleLecturerDropdown() {
            const dropdown = document.getElementById('lecturerDropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function selectLecturer(nidn, nama) {
            identityInput.value = nidn;
            toggleLecturerDropdown();
        }

        // Close dropdown when clicked outside
        document.addEventListener('click', function(e) {
            const stuDropdown = document.getElementById('studentDropdown');
            const stuContainer = document.getElementById('studentPickerContainer');
            if (stuDropdown && stuContainer && !stuContainer.contains(e.target)) {
                stuDropdown.classList.add('hidden');
            }

            const lecDropdown = document.getElementById('lecturerDropdown');
            const lecContainer = document.getElementById('lecturerPickerContainer');
            if (lecDropdown && lecContainer && !lecContainer.contains(e.target)) {
                lecDropdown.classList.add('hidden');
            }
        });

        // Initialize based on server-provided role
        document.addEventListener('DOMContentLoaded', function() {
            const initialRole = "{{ $role ?? 'mahasiswa' }}";
            setRole(initialRole);
        });
    </script>
</body>
</html>
