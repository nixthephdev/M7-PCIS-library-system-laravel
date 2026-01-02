@extends('layout')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-white tracking-tight">Book Inventory</h2>
        <p class="text-slate-400">Manage your library catalog and stock.</p>
    </div>
    <button onclick="document.getElementById('addBookForm').classList.toggle('hidden')" class="bg-white text-[#0B1120] px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-50 transition-all flex items-center gap-2">
        <i class="fas fa-plus text-[#2563EB]"></i> Add New Book
    </button>
</div>

<!-- HIDDEN FORM (Dark Mode) -->
<div id="addBookForm" class="hidden mb-8 glass-card rounded-3xl p-8 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-1 h-full bg-[#2563EB]"></div>
    <h3 class="text-xl font-bold text-white mb-6">Stock In Registration</h3>
    <form action="{{ route('inventory.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @csrf
        <div class="md:col-span-1">
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">ISBN</label>
            <input type="text" name="isbn" class="w-full glass-input rounded-xl px-4 py-3 font-medium" placeholder="Scan ISBN..." required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Book Title</label>
            <input type="text" name="title" class="w-full glass-input rounded-xl px-4 py-3 font-medium" placeholder="Enter title..." required>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Author</label>
            <input type="text" name="author" class="w-full glass-input rounded-xl px-4 py-3 font-medium">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Quantity</label>
            <input type="number" name="quantity" value="1" class="w-full glass-input rounded-xl px-4 py-3 font-medium" required>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-[#2563EB] text-white py-3 rounded-xl font-bold shadow-lg hover:bg-blue-600 transition-all">Save to Inventory</button>
        </div>
    </form>
</div>

<!-- DARK TABLE -->
<div class="glass-card rounded-3xl overflow-hidden">
    <div class="p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="relative w-full md:w-96">
            <i class="fas fa-search absolute left-4 top-3.5 text-slate-500"></i>
            <form action="{{ route('inventory.index') }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-12 pr-4 py-3 glass-input rounded-xl font-medium" placeholder="Search by title, ISBN...">
            </form>
        </div>
        <div class="text-sm font-bold text-slate-500">
            Showing <span class="text-white">{{ $books->count() }}</span> titles
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 text-slate-400 text-xs uppercase tracking-wider">
                    <th class="px-8 py-5 font-bold">Book Details</th>
                    <th class="px-6 py-5 font-bold">ISBN</th>
                    <th class="px-6 py-5 font-bold text-center">Availability</th> <!-- Renamed Header -->
                    <th class="px-6 py-5 font-bold text-center">Status</th>
                    <th class="px-6 py-5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($books as $book)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-[#2563EB] font-bold">
                                {{ substr($book->title, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-white text-lg">{{ $book->title }}</div>
                                <div class="text-sm text-slate-400 font-medium">{{ $book->author }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 font-mono text-sm text-slate-400">{{ $book->isbn }}</td>
                    
                    <!-- UPDATED STOCK COLUMN -->
                    <td class="px-6 py-5 text-center">
                        @php 
                            $total = $book->copies->count();
                            $available = $book->copies->where('status', 'available')->count();
                            $borrowed = $book->copies->where('status', 'borrowed')->count();
                        @endphp
                        
                        <div class="flex flex-col items-center">
                            <!-- Shows "4 / 5" -->
                            <div class="text-lg font-bold text-white">
                                {{ $available }} <span class="text-slate-500 text-sm">/ {{ $total }}</span>
                            </div>
                            <!-- Small label -->
                            <span class="text-[10px] uppercase font-bold text-slate-500">Available</span>
                        </div>
                    </td>

                    <td class="px-6 py-5 text-center">
                        @if($available > 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> In Stock
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Out of Stock
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-right">
                        <a href="{{ route('inventory.show', $book->id) }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-[#2563EB] transition-all">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection