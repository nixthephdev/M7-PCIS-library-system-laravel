<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M7 PCIS Library System</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.3s, color 0.3s; }
        
        /* =========================================
           DEFAULT THEME (DARK / PREMIUM)
           ========================================= */
        :root {
            --bg-color: #0B1120;
            --text-main: #ffffff;
            --glass-bg: rgba(11, 17, 32, 0.8);
            --glass-border: rgba(255, 255, 255, 0.05);
            --card-bg: rgba(255, 255, 255, 0.03);
            --input-bg: rgba(0, 0, 0, 0.3);
            --input-text: #ffffff;
            --sidebar-bg: rgba(11, 17, 32, 0.8);
        }

        /* =========================================
           LIGHT THEME OVERRIDE
           ========================================= */
        body.light-mode {
            --bg-color: #F8FAFC; /* Slate 50 */
            --text-main: #0F172A; /* Slate 900 */
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(0, 0, 0, 0.1);
            --card-bg: #ffffff;
            --input-bg: #F1F5F9;
            --input-text: #0F172A;
            --sidebar-bg: #ffffff;
        }

        /* Apply Variables */
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
        }

        .premium-bg-layer {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background-image: 
                radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(37, 99, 235, 0.15) 0px, transparent 50%);
            pointer-events: none;
        }

        /* Glass Components */
        .glass-sidebar {
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            transition: background 0.3s;
        }
        
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            transition: background 0.3s;
        }

        .glass-header {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
        }

        .glass-input {
            background: var(--input-bg);
            border: 1px solid var(--glass-border);
            color: var(--input-text);
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            background: var(--input-bg);
            border-color: #2563EB;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
            outline: none;
        }

        /* =========================================
           === CRITICAL CONTRAST FIXES ===
           ========================================= */
        body.light-mode .text-white { color: #0F172A !important; }
        body.light-mode .text-slate-300, body.light-mode .text-slate-400 { color: #475569 !important; }
        body.light-mode .text-blue-200 { color: #1e40af !important; } 
        body.light-mode .text-emerald-200 { color: #065f46 !important; } 
        body.light-mode .text-red-200 { color: #991b1b !important; } 
        body.light-mode .text-blue-300 { color: #2563EB !important; }
        body.light-mode .text-emerald-300 { color: #10B981 !important; }
        body.light-mode .text-blue-100 { color: #172554 !important; } 
        body.light-mode .text-emerald-100 { color: #064e3b !important; } 
        body.light-mode .bg-\[\#2563EB\]\/20 { background-color: #dbeafe !important; }
        body.light-mode .bg-\[\#10B981\]\/20 { background-color: #d1fae5 !important; }
        body.light-mode .bg-emerald-500\/10 { background-color: #d1fae5 !important; border-color: #6ee7b7 !important; }
        body.light-mode .bg-blue-500\/10 { background-color: #dbeafe !important; border-color: #93c5fd !important; }
        body.light-mode button.bg-white { background-color: #0F172A !important; color: white !important; }
        body.light-mode .bg-white\/5 { background-color: #f1f5f9 !important; }
        body.light-mode .bg-white\/10 { background-color: #e2e8f0 !important; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
    </style>
</head>
<body class="antialiased selection:bg-blue-500 selection:text-white relative">

    <div class="premium-bg-layer"></div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-72 glass-sidebar flex flex-col z-50 hidden md:flex">
            <!-- Brand -->
            <div class="h-24 flex items-center px-8 border-b border-white/5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain drop-shadow-lg">
                    <div>
                        <h1 class="text-xl font-black tracking-tight text-white">
                            <span class="text-[#EF4444]">M</span><span class="text-[#2563EB]">7</span> PCIS
                        </h1>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Library Management System</p>
                    </div>
                </a>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-4 py-8 space-y-2">
                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Main Menu</p>

                <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-xl text-slate-400 hover:text-[#2563EB] hover:bg-blue-500/10 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'active bg-blue-500/10 text-blue-600 border-l-4 border-blue-600' : '' }}">
                    <i class="fas fa-layer-group text-lg w-6 text-center"></i> 
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('inventory.index') }}" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-xl text-slate-400 hover:text-[#2563EB] hover:bg-blue-500/10 transition-all duration-300 {{ request()->routeIs('inventory.*') ? 'active bg-blue-500/10 text-blue-600 border-l-4 border-blue-600' : '' }}">
                    <i class="fas fa-book text-lg w-6 text-center"></i> 
                    <span class="font-medium">Inventory</span>
                </a>

                <a href="{{ route('circulation.index') }}" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-xl text-slate-400 hover:text-[#2563EB] hover:bg-blue-500/10 transition-all duration-300 {{ request()->routeIs('circulation.*') ? 'active bg-blue-500/10 text-blue-600 border-l-4 border-blue-600' : '' }}">
                    <i class="fas fa-exchange-alt text-lg w-6 text-center"></i> 
                    <span class="font-medium">Circulation</span>
                </a>

                <a href="{{ route('users.index') }}" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-xl text-slate-400 hover:text-[#2563EB] hover:bg-blue-500/10 transition-all duration-300 {{ request()->routeIs('users.*') ? 'active bg-blue-500/10 text-blue-600 border-l-4 border-blue-600' : '' }}">
                    <i class="fas fa-users text-lg w-6 text-center"></i> 
                    <span class="font-medium">Members</span>
                </a>
            </nav>

            <!-- User Profile (Premium Card) -->
            <div class="p-4 border-t border-white/5 bg-black/5">
                <div class="flex items-center gap-2">
                    
                    <!-- PROFILE BUTTON (Clickable Card) -->
                    <a href="{{ route('profile') }}" class="flex-1 flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/5 transition-all group border border-transparent hover:border-white/5">
                        <!-- Avatar Logic -->
                        <div class="relative">
                            @if(Auth::user()->avatar)
                                <!-- Show Uploaded Image -->
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full object-cover border-2 border-[#2563EB] shadow-lg shadow-blue-900/20 group-hover:scale-105 transition-transform">
                            @else
                                <!-- Show Default Initials -->
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] flex items-center justify-center text-white font-bold shadow-lg shadow-blue-900/20 group-hover:scale-105 transition-transform">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                            <!-- Online Dot -->
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-[#0B1120] rounded-full"></div>
                        </div>
                        
                        <!-- Text Info -->
                        <div class="overflow-hidden">
                            <p class="text-sm font-bold text-white truncate group-hover:text-[#2563EB] transition-colors">
                                {{ Str::limit(Auth::user()->name ?? 'Admin', 12) }}
                            </p>
                            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wide group-hover:text-white transition-colors">
                                <i class="fas fa-cog text-xs"></i> <span>Settings</span>
                            </div>
                        </div>
                    </a>
                    
                    <!-- LOGOUT BUTTON (Distinct) -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-red-500/10 text-slate-400 hover:text-red-500 flex items-center justify-center transition-all border border-transparent hover:border-red-500/20 shadow-sm" title="Logout">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>

                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto relative">
            <!-- Mobile Header -->
            <header class="h-20 glass-header flex items-center justify-between px-6 md:hidden sticky top-0 z-40">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-white">M7 PCIS</span>
                </div>
                <button class="text-white text-xl"><i class="fas fa-bars"></i></button>
            </header>

            <div class="p-6 md:p-10 max-w-7xl mx-auto">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <span class="text-emerald-100 font-medium text-white">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-8 bg-red-500/10 border border-red-500/20 p-4 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                        <span class="text-red-100 font-medium text-white">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- THEME TOGGLE BUTTON -->
        <button id="theme-toggle" class="fixed bottom-6 right-6 w-14 h-14 bg-[#2563EB] text-white rounded-full shadow-2xl hover:scale-110 transition-transform z-50 flex items-center justify-center text-xl border-4 border-white/10">
            <i class="fas fa-sun" id="theme-icon"></i>
        </button>

    </div>

    <!-- THEME LOGIC SCRIPT -->
    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const body = document.body;

        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'light') {
            body.classList.add('light-mode');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }

        toggleBtn.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            
            if (body.classList.contains('light-mode')) {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    </script>
</body>
</html>