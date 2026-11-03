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

    <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-[#E5E7EB] bg-white shadow-2xl">
        <div class="flex flex-col justify-between bg-white p-5 sm:p-7 lg:p-8">
            <div>
                <!-- BRAND -->
                <div class="mb-7 flex items-center gap-3">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-[#5B50E5] to-[#818CF8] text-white shadow-md shadow-[#5B50E5]/25">
                        <span class="material-symbols-outlined text-[24px]">school</span>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <div class="truncate text-sm font-extrabold tracking-tight text-[#161A30]">SI Jadwal TI</div>
                        <div class="truncate text-[11px] font-medium text-[#8A94A6]">Universitas Surakarta</div>
                    </div>
                </div>

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
                        <span class="text-[11px] text-[#059669] font-bold" id="activeRoleText">Mahasiswa</span>
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
                    <h3 class="text-lg sm:text-xl font-extrabold text-[#161A30] tracking-tight" id="formHeading">
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
                        </div>

                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-[#8A94A6] text-[18px] pointer-events-none" id="identityIcon">school</span>
                            <input type="text" name="nim" id="identityInput" required
                                   placeholder="Contoh: 220101001 atau 220101045" 
                                   value="{{ old('nim', old('nidn', old('username', ''))) }}"
                                   class="w-full bg-[#F4F5F9] text-[#161A30] placeholder:text-[#8A94A6] pl-10 pr-4 py-2.5 rounded-xl text-xs outline-none focus:bg-white focus:ring-2 focus:ring-[#059669]/20 border border-transparent focus:border-[#059669]/30 transition-all font-mono">
                        </div>
                        <span id="identityHint" class="mt-1 block text-[11px] text-[#8A94A6]">Gunakan NIM yang terdaftar.</span>
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
                            <span>Ingat perangkat ini</span>
                        </label>
                        <a id="forgotPasswordLink" href="{{ route('password.request', ['role' => $role ?? 'mahasiswa']) }}" class="text-[11px] text-[#5B50E5] hover:underline font-semibold">
                            Lupa password?
                        </a>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit" id="submitBtn" class="w-full py-3 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-bold text-xs shadow-md shadow-[#059669]/25 flex items-center justify-center gap-2 transition-all mt-2">
                        <span class="material-symbols-outlined text-[18px]">login</span>
                        <span id="submitBtnText">Masuk sebagai Mahasiswa</span>
                    </button>
                </form>
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
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const formHeading = document.getElementById('formHeading');
        const formSubheading = document.getElementById('formSubheading');

        function setRole(role) {
            roleInput.value = role;
            if (forgotPasswordLink) {
                forgotPasswordLink.href = `{{ route('password.request') }}?role=${encodeURIComponent(role)}`;
            }

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
                activeRoleText.textContent = 'Mahasiswa';
                activeRoleText.className = 'text-[11px] text-[#059669] font-bold';
                formHeading.textContent = 'Masuk Menggunakan NIM';
                formSubheading.textContent = 'Masukkan NIM dan kata sandi akun Anda.';
                identityHint.textContent = 'Gunakan NIM yang terdaftar.';

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
                activeRoleText.textContent = 'Dosen';
                activeRoleText.className = 'text-[11px] text-[#5B50E5] font-bold';
                formHeading.textContent = 'Masuk Menggunakan NIDN';
                formSubheading.textContent = 'Masukkan NIDN dan kata sandi akun Anda.';
                identityHint.textContent = 'Gunakan NIDN yang terdaftar.';

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
                activeRoleText.textContent = 'Administrator';
                activeRoleText.className = 'text-[11px] text-[#EF4444] font-bold';
                formHeading.textContent = 'Masuk Panel Administrator';
                formSubheading.textContent = 'Masukkan username admin dan kata sandi akun Anda.';
                identityHint.textContent = 'Gunakan username administrator.';
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

        // Initialize based on server-provided role
        document.addEventListener('DOMContentLoaded', function() {
            const initialRole = "{{ $role ?? 'mahasiswa' }}";
            setRole(initialRole);
        });
    </script>
</body>
</html>
