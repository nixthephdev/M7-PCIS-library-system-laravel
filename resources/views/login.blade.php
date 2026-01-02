<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - M7 PCIS Library Management System</title>
    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Premium Background */
        .premium-bg {
            background-color: #0B1120;
            background-image: 
                radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.2) 0px, transparent 50%);
        }

        /* Glass Effect for the Form */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Input Styles */
        .glass-input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        
        /* Autofill fix for dark mode */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #0f1523 inset !important;
            -webkit-text-fill-color: white !important;
        }
    </style>
</head>
<body class="premium-bg h-screen w-full flex items-center justify-center relative overflow-hidden">

    <!-- ============================================== -->
    <!-- BACKGROUND EFFECTS -->
    <!-- ============================================== -->
    <!-- Red Glow (Top Left) -->
    <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-[#EF4444] rounded-full blur-[150px] opacity-20 -translate-x-1/3 -translate-y-1/3 animate-pulse"></div>
    <!-- Blue Glow (Bottom Right) -->
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-[#2563EB] rounded-full blur-[150px] opacity-20 translate-x-1/3 translate-y-1/3 animate-pulse" style="animation-delay: 2s"></div>
    <!-- Grid Overlay -->
    <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>


    <!-- ============================================== -->
    <!-- GLASS LOGIN CARD -->
    <!-- ============================================== -->
    <div class="w-full max-w-md p-8 md:p-10 rounded-3xl glass-card relative z-10 mx-4">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="inline-block relative mb-6">
                <div class="absolute inset-0 bg-white/20 blur-xl rounded-full"></div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="relative h-24 w-auto object-contain drop-shadow-2xl mx-auto">
            </div>
            
            <h1 class="text-4xl font-black text-white tracking-tight mb-2">
                <span class="text-[#EF4444] drop-shadow-lg">M</span><span class="text-[#2563EB] drop-shadow-lg">7</span> PCIS
            </h1>
            <p class="text-blue-200/70 text-sm font-medium tracking-wide uppercase">Library Management System</p>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 p-4 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span class="text-red-200 text-sm font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Email -->
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email" name="email" class="w-full pl-11 pr-4 py-3.5 rounded-xl glass-input outline-none placeholder-gray-600 font-medium" placeholder="admin@pcis.edu.ph" required>
                </div>
            </div>
            
            <!-- Password -->
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-500"></i>
                    </div>
                    <input type="password" name="password" class="w-full pl-11 pr-4 py-3.5 rounded-xl glass-input outline-none placeholder-gray-600 font-medium" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#2563EB] to-[#1D4ED8] text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group mt-4">
                <span>Sign In to Dashboard</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <!-- Back Link -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-500 hover:text-white transition-colors">
                &larr; Back to Landing Page
            </a>
        </div>
    </div>


    <!-- ============================================== -->
    <!-- FOOTER CREDIT (Pinned to Bottom) -->
    <!-- ============================================== -->
    <div class="absolute bottom-6 w-full text-center z-10">
        <p class="text-gray-500 text-sm mb-2">
            &copy; {{ date('Y') }} <span class="text-[#EF4444] font-bold">M</span><span class="text-[#2563EB] font-bold">7</span> <span class="text-white font-bold">PCIS</span>. All rights reserved.
        </p>
        <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] mb-1">
            System Architecture & Development by
        </p>
        <p class="text-sm text-gray-300 font-bold tracking-wide">
            Nikko Calumpiano
        </p>
    </div>

</body>
</html>