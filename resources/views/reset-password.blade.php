<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - M7 PCIS</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0B1120; color: white; font-family: sans-serif; }
        .glass-card { background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .glass-input { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 8px; width: 100%; }
    </style>
</head>
<body class="h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 rounded-2xl glass-card">
        <h2 class="text-2xl font-bold mb-6 text-center">Set New Password</h2>
        
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label class="text-xs text-gray-400 uppercase font-bold">Email Address</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" class="glass-input mt-1" required>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase font-bold">New Password</label>
                <input type="password" name="password" class="glass-input mt-1" required>
            </div>
            <div>
                <label class="text-xs text-gray-400 uppercase font-bold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="glass-input mt-1" required>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 rounded-xl font-bold hover:bg-blue-500 transition">Update Password</button>
        </form>
    </div>
</body>
</html>