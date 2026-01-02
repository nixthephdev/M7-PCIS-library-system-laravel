@extends('layout')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-white tracking-tight">Admin Profile</h2>
    <p class="text-slate-400">Manage your account settings and security.</p>
</div>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- LEFT: BASIC INFO -->
        <div class="glass-card rounded-3xl p-8 relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#2563EB] rounded-full blur-[60px] opacity-20 -mr-10 -mt-10 pointer-events-none"></div>

            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-id-card text-[#2563EB]"></i> Personal Details
            </h3>

            <!-- Avatar Upload Section -->
<div class="flex flex-col items-center mb-8">
    <div class="relative group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
        
        <!-- The Image -->
        <div class="w-28 h-28 rounded-full p-1 bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] shadow-2xl shadow-blue-900/50 overflow-hidden">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover border-4 border-[#0B1120]">
            @else
                <div class="w-full h-full rounded-full bg-[#0B1120] flex items-center justify-center text-white text-4xl font-bold border-4 border-[#0B1120]">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
        </div>

        <!-- Camera Overlay (Visible on Hover) -->
        <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <i class="fas fa-camera text-white text-2xl"></i>
        </div>

        <!-- Hidden Input -->
        <input type="file" name="avatar" id="avatarInput" class="hidden" onchange="form.submit()">
    </div>
    
    <p class="text-xs text-slate-400 mt-3 uppercase tracking-wider font-bold">Click to change photo</p>
</div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Full Name</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: SECURITY -->
        <div class="glass-card rounded-3xl p-8 relative overflow-hidden">
             <!-- Decorative Glow -->
             <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#EF4444] rounded-full blur-[60px] opacity-20 -ml-10 -mb-10 pointer-events-none"></div>

            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-shield-alt text-[#EF4444]"></i> Security
            </h3>
            
            <div class="p-4 rounded-xl bg-white/5 border border-white/5 mb-6">
                <p class="text-xs text-slate-300 leading-relaxed">
                    <i class="fas fa-info-circle text-[#EF4444] mr-1"></i>
                    Leave these fields empty if you do not want to change your password.
                </p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Current Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="password" name="current_password" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" placeholder="••••••••">
                    </div>
                    @error('current_password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">New Password</label>
                    <div class="relative">
                        <i class="fas fa-key absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="password" name="new_password" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" placeholder="Min. 8 characters">
                    </div>
                    @error('new_password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Confirm New Password</label>
                    <div class="relative">
                        <i class="fas fa-check-circle absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="password" name="new_password_confirmation" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" placeholder="Confirm password">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ACTION BAR -->
    <div class="mt-8 flex justify-end">
        <button type="submit" class="px-8 py-4 bg-[#2563EB] text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-600 hover:shadow-blue-600/40 transition-all transform hover:-translate-y-1 flex items-center gap-2">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>
@endsection