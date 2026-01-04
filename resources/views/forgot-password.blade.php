<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - M7 PCIS</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: sans-serif; }
        .premium-bg { background-color: #0B1120; background-image: radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.2) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.2) 0px, transparent 50%); }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        .glass-input { background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
    </style>
</head>
<body class="premium-bg h-screen w-full flex items-center justify-center">
    <div class="w-full max-w-md p-8 rounded-3xl glass-card">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-white mb-2">Reset Password</h1>
            <p class="text-blue-200/70 text-sm">Enter your email to receive a reset link.</p>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-xl text-emerald-400 text-sm font-bold">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3.5 rounded-xl glass-input outline-none focus:border-blue-600 transition-all" placeholder="admin@pcis.edu.ph" required>
            </div>
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white font-bold rounded-xl shadow-lg hover:-translate-y-1 transition-all">
                Send Reset Link
            </button>
        </form>
        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-500 hover:text-white">&larr; Back to Login</a>
        </div>
    </div>
</body>
</html>