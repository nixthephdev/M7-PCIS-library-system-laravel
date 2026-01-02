@extends('layout')

@section('content')
<style>
    /* Hero & Card Styles */
    .hero-card { background: linear-gradient(to right, #1e3a8a, #0B1120); color: white; }
    body.light-mode .hero-card { background: linear-gradient(to right, #ffffff, #eff6ff); border: 1px solid #e2e8f0; }
    body.light-mode .hero-title { color: #1e3a8a !important; }
    body.light-mode .hero-subtitle { color: #64748b !important; }
    body.light-mode .hero-badge { background: #eff6ff !important; color: #2563EB !important; border-color: #bfdbfe !important; }
    
    /* Stat Cards */
    body.light-mode .stat-value { color: #0F172A !important; }
    body.light-mode .stat-label { color: #64748B !important; }
    body.light-mode .glass-card:hover { background-color: #ffffff !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }

    /* Table Text */
    body.light-mode .text-white { color: #0F172A !important; }
    body.light-mode .text-slate-400 { color: #64748B !important; }
</style>

<div class="space-y-8">
    
    <!-- 1. HERO SECTION -->
    <div class="hero-card relative rounded-3xl p-8 overflow-hidden shadow-2xl border border-white/10 transition-colors duration-300">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#EF4444] rounded-full blur-[100px] opacity-20 -mr-10 -mt-10 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <span class="hero-badge px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-bold uppercase tracking-wider mb-3 inline-block text-blue-300">Admin Dashboard</span>
                <h1 class="hero-title text-3xl font-extrabold text-white mb-1">Welcome back, {{ Auth::user()->name }}.</h1>
                <p class="hero-subtitle text-slate-300">Here is your library's performance overview.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('circulation.index') }}" class="px-5 py-2.5 bg-white/10 text-white rounded-xl font-bold hover:bg-white/20 transition-all border border-white/10 backdrop-blur-sm">
                    <i class="fas fa-qrcode mr-2"></i> Circulation
                </a>
                <a href="{{ route('inventory.index') }}" class="px-5 py-2.5 bg-[#2563EB] text-white rounded-xl font-bold shadow-lg hover:bg-blue-600 transition-all transform hover:-translate-y-1">
                    <i class="fas fa-plus mr-2"></i> Add Books
                </a>
            </div>
        </div>
    </div>

    <!-- 2. STATS GRID (4 Columns) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Stock -->
        <div class="glass-card rounded-3xl p-6 hover:bg-white/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-[#2563EB]/10 flex items-center justify-center text-[#2563EB] text-xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors">
                    <i class="fas fa-book"></i>
                </div>
                <span class="stat-label text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Books</span>
            </div>
            <h2 class="stat-value text-4xl font-black text-white">{{ $totalBooks }}</h2>
        </div>

        <!-- Borrowed -->
        <div class="glass-card rounded-3xl p-6 hover:bg-white/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 text-xl group-hover:bg-orange-500 group-hover:text-white transition-colors">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <span class="stat-label text-[10px] font-bold text-slate-500 uppercase tracking-wider">Borrowed</span>
            </div>
            <h2 class="stat-value text-4xl font-black text-white">{{ $borrowedBooks }}</h2>
        </div>

        <!-- Overdue (Red) -->
        <div class="glass-card rounded-3xl p-6 hover:bg-white/5 transition-all group border-l-4 border-[#EF4444]">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-[#EF4444]/10 flex items-center justify-center text-[#EF4444] text-xl group-hover:bg-[#EF4444] group-hover:text-white transition-colors">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span class="stat-label text-[10px] font-bold text-slate-500 uppercase tracking-wider">Overdue</span>
            </div>
            <h2 class="stat-value text-4xl font-black text-white">{{ $overdueCount }}</h2>
        </div>

        <!-- Fines (Green) -->
        <div class="glass-card rounded-3xl p-6 hover:bg-white/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <i class="fas fa-coins"></i>
                </div>
                <span class="stat-label text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Fines</span>
            </div>
            <h2 class="stat-value text-4xl font-black text-white">${{ number_format($totalFines, 2) }}</h2>
        </div>
    </div>

    <!-- 3. SPLIT SECTION: Recent Activity & Overdue List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT: Recent Activity (Wide) -->
        <div class="lg:col-span-2 glass-card rounded-3xl overflow-hidden">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Recent Activity</h3>
                <a href="{{ route('circulation.index') }}" class="text-xs font-bold text-[#2563EB] hover:text-blue-400">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white/5 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Action</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentActivities as $activity)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
    <div class="flex items-center gap-3">
        <!-- AVATAR LOGIC -->
        @if($activity->user->avatar)
            <img src="{{ asset('storage/' . $activity->user->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-white/10 shadow-sm">
        @else
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-white">
                {{ substr($activity->user->name, 0, 1) }}
            </div>
        @endif
        
        <span class="text-sm font-bold text-white">{{ $activity->user->name }}</span>
    </div>
</td>
                            <td class="px-6 py-4">
                                @if($activity->returned_at)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 text-xs font-bold border border-emerald-500/20">
                                        <i class="fas fa-check"></i> Returned
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">
                                        <i class="fas fa-arrow-right"></i> Borrowed
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400 ml-2">{{ Str::limit($activity->bookCopy->book->title, 20) }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $activity->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT: Overdue Alert (Narrow) -->
        <div class="glass-card rounded-3xl p-6 border border-red-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-500 rounded-full blur-[60px] opacity-10 pointer-events-none"></div>
            
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-bell text-[#EF4444]"></i> Overdue Alerts
            </h3>

            @if($overdueList->count() > 0)
                <div class="space-y-4">
                    @foreach($overdueList as $overdue)
                    <div class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-white">{{ $overdue->user->name }}</p>
                            <p class="text-xs text-red-300 truncate w-40">{{ $overdue->bookCopy->book->title }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-red-400">Due</p>
                            <p class="text-xs text-white">{{ \Carbon\Carbon::parse($overdue->due_date)->format('M d') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center mx-auto mb-3 text-emerald-500">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-sm text-slate-400">No overdue books!</p>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection