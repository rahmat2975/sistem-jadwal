<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SI Jadwal TI - Universitas Surakarta') }}</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F5F9] font-sans text-[#161A30] antialiased selection:bg-[#5B50E5] selection:text-white">

    <!-- MOBILE SIDEBAR BACKDROP OVERLAY -->
    <div id="sidebarBackdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

    <div class="flex min-h-screen">
        <!-- SIDEBAR (Off-canvas on mobile/tablet, sticky on desktop) -->
        <aside id="mainSidebar" 
               class="fixed lg:sticky top-0 left-0 z-50 h-screen w-[270px] flex-shrink-0 bg-white border-r border-[#E5E7EB] flex flex-col justify-between p-5 overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none">
            <div>
                <!-- BRAND LOGO & MOBILE CLOSE BUTTON -->
                <div class="flex items-center justify-between mb-6 px-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#5B50E5] to-[#818CF8] flex items-center justify-center text-white shadow-md shadow-[#5B50E5]/25 flex-shrink-0">
                            <span class="material-symbols-outlined text-[24px]">school</span>
                        </div>
                        <div>
                            <h1 class="text-base font-extrabold tracking-tight text-[#161A30] leading-none">SI Jadwal TI</h1>
                            <p class="text-xs text-[#8A94A6] font-medium mt-1">Universitas Surakarta</p>
                        </div>
                    </a>
                    
                    <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl text-[#8A94A6] hover:bg-[#F4F5F9] hover:text-[#161A30] transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- NAVIGATION GROUPS -->
                <nav class="space-y-5">
                    <!-- OVERVIEW -->
                    <div>
                        <div class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider px-3 mb-2">Overview</div>
                        <div class="space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('jadwal.hari-ini') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('jadwal.hari-ini') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="calendar" class="w-4 h-4 {{ request()->routeIs('jadwal.hari-ini') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Jadwal Hari Ini</span>
                            </a>
                            <a href="{{ route('jadwal.semua') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('jadwal.semua') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="calendar-days" class="w-4 h-4 {{ request()->routeIs('jadwal.semua') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Semua Jadwal</span>
                            </a>
                        </div>
                    </div>

                    <!-- AKADEMIK -->
                    <div>
                        <div class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider px-3 mb-2">Akademik</div>
                        <div class="space-y-1">
                            <a href="{{ route('mata-kuliah.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('mata-kuliah.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="book-open" class="w-4 h-4 {{ request()->routeIs('mata-kuliah.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Mata Kuliah</span>
                            </a>
                            <a href="{{ route('pengajar.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pengajar.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="graduation-cap" class="w-4 h-4 {{ request()->routeIs('pengajar.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                            <span>Dosen</span>
                            </a>
                            <a href="{{ route('ruang.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('ruang.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="door-open" class="w-4 h-4 {{ request()->routeIs('ruang.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                            <span>Ruang</span>
                            </a>
                        </div>
                    </div>

                    <!-- ADMIN MANAGEMENT (CRUD) -->
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <div>
                        <div class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider px-3 mb-2 flex items-center justify-between">
                            <span>Admin</span>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.jadwal.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="calendar-check" class="w-4 h-4 {{ request()->routeIs('admin.jadwal.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Kelola Jadwal</span>
                            </a>
                            <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.courses.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="library" class="w-4 h-4 {{ request()->routeIs('admin.courses.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Kelola Mata Kuliah</span>
                            </a>
                            <a href="{{ route('admin.lecturers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.lecturers.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('admin.lecturers.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Kelola Pengajar</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#5B50E5] text-white shadow-md shadow-[#5B50E5]/30' : 'text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]' }}">
                                <i data-lucide="key-round" class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-[#8A94A6]' }}"></i>
                                <span>Kelola Akun Login</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- PENGATURAN & AUTH -->
                    <div>
                        <div class="text-xs font-bold text-[#8A94A6] uppercase tracking-wider px-3 mb-2">Akun & Sistem</div>
                        <div class="space-y-1">
                            @auth
                                <a href="{{ route('pengaturan.akun') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all text-[#464555] hover:bg-[#F4F2FF] hover:text-[#5B50E5]">
                                    <i data-lucide="settings" class="w-4 h-4 text-[#8A94A6]"></i>
                                    <span>Pengaturan Akun</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#EF4444] hover:bg-[#FEF2F2] transition-all">
                                        <i data-lucide="log-out" class="w-4 h-4 text-[#EF4444]"></i>
                                <span>Keluar</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all bg-[#EEF2FF] text-[#5B50E5] hover:bg-[#5B50E5] hover:text-white">
                                    <i data-lucide="log-in" class="w-4 h-4"></i>
                                    <span>Masuk / Login</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </nav>
            </div>

            <!-- FOOTER BADGE IN SIDEBAR -->
            <div class="pt-4 border-t border-[#F1F3F7]">
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#F4F5F9] border border-[#E5E7EB]/60">
                    <div class="w-8 h-8 rounded-full bg-[#5B50E5]/10 text-[#5B50E5] flex items-center justify-center flex-shrink-0">
                        <i data-lucide="info" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-[#161A30]">Portal UNSA TI</div>
                        <div class="text-xs text-[#8A94A6]">v2.4 Academic Edition</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- TOP BAR / HEADER -->
            <header class="h-[72px] sm:h-[76px] bg-white border-b border-[#E5E7EB] px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30">
                <!-- LEFT: MOBILE HAMBURGER BUTTON & SEARCH -->
                <div class="flex items-center gap-3 sm:gap-4 flex-1 max-w-xl">
                    <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl text-[#161A30] hover:bg-[#F4F5F9] border border-[#E5E7EB] transition-colors flex-shrink-0" aria-label="Buka Menu">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>

                    <div class="relative w-full hidden sm:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#8A94A6]">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" placeholder="Cari jadwal, dosen, atau ruang..." 
                               class="w-full pl-10 pr-4 py-2 bg-[#F4F5F9] border border-transparent rounded-full text-xs placeholder:text-[#8A94A6] focus:bg-white focus:border-[#5B50E5]/30 focus:ring-2 focus:ring-[#5B50E5]/20 focus:outline-none transition-all">
                    </div>
                    
                    <!-- SEMESTER BADGE -->
                    <div class="hidden xl:flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#ECFDF5] border border-[#A7F3D0] text-[#059669] text-xs font-semibold whitespace-nowrap">
                        <span class="w-2 h-2 rounded-full bg-[#059669] animate-pulse"></span>
                        <span>T.A. {{ $activeYear->tahun ?? '2026/2027' }} {{ ucfirst($activeYear->semester ?? 'Gasal') }}</span>
                    </div>
                </div>

                <!-- RIGHT HEADER CONTROLS & PROFILE -->
                <div class="flex items-center gap-2.5 sm:gap-4">
                    <!-- ACTION ICONS (DESKTOP) -->
                    <a href="{{ route('jadwal.semua') }}" title="Lihat Semua Jadwal" class="hidden sm:flex w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[#E5E7EB] items-center justify-center text-[#464555] hover:bg-[#F4F5F9] transition-all">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </a>
                    
                    <a href="{{ route('mata-kuliah.index') }}" title="Katalog Mata Kuliah" class="hidden sm:flex w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[#E5E7EB] items-center justify-center text-[#464555] hover:bg-[#F4F5F9] transition-all">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                    </a>

                    <!-- DIVIDER -->
                    <div class="h-8 w-px bg-[#E5E7EB] hidden sm:block"></div>

                    <!-- USER AVATAR & INFO / LOGIN BUTTON -->
                    @auth
                        @php
                            $user = auth()->user();
                            $lecturer = $user->lecturer;
                            $displayName = $lecturer?->nama ?? $user->name;
                            if ($user->isAdmin()) {
                                $roleName = 'Admin';
                                $roleBg = 'bg-[#FEF2F2] text-[#EF4444]';
                                $subLabel = 'Username';
                                $subInfo = $user->username;
                                $avatar = 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80';
                            } elseif ($user->isMahasiswa()) {
                                $roleName = 'Mahasiswa';
                                $roleBg = 'bg-[#ECFDF5] text-[#059669]';
                                $subLabel = 'NIM';
                                $subInfo = $user->username;
                                $avatar = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80';
                            } else {
                                $roleName = 'Dosen TI';
                                $roleBg = 'bg-[#EEF2FF] text-[#5B50E5]';
                                $subLabel = 'NIDN';
                                $subInfo = $lecturer?->nidn ?? $user->username;
                                $avatar = $lecturer?->foto ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80';
                            }
                        @endphp
                        <div class="flex items-center gap-2 sm:gap-3 pl-1">
                            <img src="{{ $avatar }}" 
                                 alt="User Avatar" 
                                 class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover ring-2 ring-[#5B50E5]/20 flex-shrink-0">
                            <div class="text-left hidden md:block">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-[#161A30] truncate max-w-[140px]">{{ $displayName }}</span>
                                    <span class="px-2 py-0.5 rounded text-xs font-bold {{ $roleBg }}">{{ $roleName }}</span>
                                </div>
                                <div class="text-xs text-[#8A94A6]">{{ $subLabel }}: {{ $subInfo }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline-block m-0">
                                @csrf
                                <button type="submit" title="Keluar / Logout" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl border border-[#E5E7EB] bg-white text-[#EF4444] hover:bg-[#FEF2F2] flex items-center justify-center transition-all cursor-pointer">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Public / Guest View -->
                        <div class="flex items-center gap-2 sm:gap-3">
                            <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl bg-[#5B50E5] text-white text-xs font-bold hover:bg-[#4F46E5] shadow-sm transition-all whitespace-nowrap">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                <span>Masuk / Login</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- FLASH NOTIFICATIONS -->
            @if(session('success'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-5 p-4 rounded-2xl bg-[#ECFDF5] border border-[#A7F3D0] text-[#059669] text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#059669]"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-[#059669] hover:opacity-75">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-5 p-4 rounded-2xl bg-[#FEF2F2] border border-[#FECACA] text-[#EF4444] text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-[#EF4444]"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-[#EF4444] hover:opacity-75">✕</button>
                </div>
            @endif

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-[#F4F5F9] overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Responsive mobile sidebar toggle
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
