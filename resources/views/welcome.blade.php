<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M7 PCIS Library Portal</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .premium-bg {
            background-color: #0B1120;
            background-image: 
                radial-gradient(at 0% 0%, rgba(220, 38, 38, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="premium-bg min-h-screen text-white flex flex-col overflow-hidden relative">

    <!-- NAVBAR -->
    <nav class="absolute top-0 w-full z-50 px-6 py-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            
            <!-- BRANDING -->
            <div class="flex items-center gap-4 select-none">
                <!-- LOGO FIX: Removed bg-white and p-1 to remove the white box -->
                <img src="{{ asset('images/logo.png') }}" class="h-14 w-auto object-contain drop-shadow-2xl">
                
                <div class="font-extrabold text-3xl tracking-tight leading-none">
                    <span class="text-[#EF4444] drop-shadow-[0_0_15px_rgba(239,68,68,0.6)]">M</span><span class="text-[#2563EB] drop-shadow-[0_0_15px_rgba(37,99,235,0.6)]">7</span>
                    <span class="text-white ml-2">PCIS</span>
                </div>
            </div>

            <!-- Login Button -->
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white/10 border border-white/20 backdrop-blur-md rounded-full font-bold hover:bg-white hover:text-black transition-all duration-300">
                        Dashboard <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group px-8 py-3 bg-white text-[#0B1120] rounded-full font-bold shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)] hover:scale-105 transition-all duration-300">
                        <i class="fas fa-lock mr-2 text-[#EF4444] group-hover:text-[#2563EB] transition-colors"></i> 
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <main class="flex-1 flex items-center justify-center relative px-6">
        
        <!-- Background Effects -->
        <div class="absolute top-1/4 left-10 w-72 h-72 bg-[#EF4444] rounded-full blur-[120px] opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-10 w-72 h-72 bg-[#2563EB] rounded-full blur-[120px] opacity-20 animate-pulse" style="animation-delay: 1s;"></div>

        <div class="relative z-10 text-center max-w-5xl mx-auto">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-8 animate-float">
                <span class="w-2 h-2 rounded-full bg-[#EF4444] animate-pulse"></span>
                <span class="text-sm font-semibold tracking-widest uppercase text-gray-300">Official Library Portal</span>
                <span class="w-2 h-2 rounded-full bg-[#2563EB] animate-pulse"></span>
            </div>

            <!-- Headline -->
            <h1 class="text-6xl md:text-8xl font-black mb-8 leading-tight tracking-tight">
                Welcome to <br>
                <span class="inline-block relative">
                    <span class="text-[#EF4444]">M</span><span class="text-[#2563EB]">7</span>
                    <span class="text-white ml-2">PCIS</span>
                </span>
            </h1>

            <p class="text-xl md:text-2xl text-gray-400 mb-12 max-w-3xl mx-auto font-light leading-relaxed">
                Empowering the leaders of tomorrow with a world-class digital and physical collection of knowledge.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                @auth
                    <a href="{{ route('inventory.index') }}" class="w-full md:w-auto px-10 py-5 bg-gradient-to-r from-[#EF4444] to-[#B91C1C] rounded-2xl font-bold text-lg shadow-lg hover:shadow-red-500/50 hover:-translate-y-1 transition-all">
                        Browse Inventory
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full md:w-auto px-10 py-5 bg-gradient-to-r from-[#2563EB] to-[#1D4ED8] rounded-2xl font-bold text-lg shadow-lg hover:shadow-blue-500/50 hover:-translate-y-1 transition-all">
                        Access Portal
                    </a>
                @endauth
            </div>

        </div>
    </main>

    <!-- FOOTER WITH CREDIT -->
    <footer class="relative z-10 py-8 text-center border-t border-white/5 bg-[#0B1120]/80 backdrop-blur-sm">
        <p class="text-gray-500 text-sm mb-2">
            &copy; {{ date('Y') }} <span class="text-[#EF4444] font-bold">M</span><span class="text-[#2563EB] font-bold">7</span> <span class="text-white font-bold">PCIS</span>. All rights reserved.
        </p>
        
        <!-- YOUR CREDIT HERE -->
        <p class="text-xs text-gray-600 font-medium uppercase tracking-wider">
            System architecture and Development by <span class="text-gray-400">Nikko Calumpiano</span>
        </p>
    </footer>

</body>
</html>