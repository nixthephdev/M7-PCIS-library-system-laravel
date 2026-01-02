@extends('layout')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
    
    <!-- BORROW CARD -->
    <div class="glass-card rounded-3xl overflow-hidden group">
        <div class="bg-[#2563EB]/20 border-b border-[#2563EB]/20 p-6 flex justify-between items-center backdrop-blur-md">
            <div>
                <h3 class="text-xl font-bold text-white">Borrow Book</h3>
                <p class="text-blue-200 text-sm">Stock Out Registration</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-[#2563EB]/20 flex items-center justify-center text-blue-300">
                <i class="fas fa-upload"></i>
            </div>
        </div>
        <div class="p-8">
            <form action="{{ route('circulation.borrow') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <!-- UPDATED LABEL AND INPUT NAME -->
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Student ID / Card Scan</label>
                    <div class="relative mt-2">
                        <i class="fas fa-id-card absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="text" name="student_id" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium font-mono" placeholder="Scan ID Card (e.g. IB0001)..." required autofocus>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Book Barcode</label>
                    <div class="relative mt-2">
                        <i class="fas fa-barcode absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="text" name="accession_number" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" placeholder="Scan Accession #..." required>
                    </div>
                </div>
                <button class="w-full py-4 bg-[#2563EB] text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-600 hover:shadow-blue-600/40 transition-all transform hover:-translate-y-1">
                    Confirm Borrow
                </button>
            </form>
        </div>
    </div>

    <!-- RETURN CARD -->
    <div class="glass-card rounded-3xl overflow-hidden group">
        <div class="bg-[#10B981]/20 border-b border-[#10B981]/20 p-6 flex justify-between items-center backdrop-blur-md">
            <div>
                <h3 class="text-xl font-bold text-white">Return Book</h3>
                <p class="text-emerald-200 text-sm">Stock In / Check In</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-[#10B981]/20 flex items-center justify-center text-emerald-300">
                <i class="fas fa-download"></i>
            </div>
        </div>
        <div class="p-8">
            <form action="{{ route('circulation.return') }}" method="POST" class="space-y-5">
                @csrf
                <div class="opacity-0 h-0 overflow-hidden">
                    <input type="text" class="py-3"> 
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Book Barcode</label>
                    <div class="relative mt-2">
                        <i class="fas fa-barcode absolute left-4 top-3.5 text-slate-500"></i>
                        <input type="text" name="accession_number" class="w-full pl-10 pr-4 py-3 glass-input rounded-xl font-medium" placeholder="Scan Accession #..." required>
                    </div>
                </div>
                <div class="pt-[26px]"> 
                    <button class="w-full py-4 bg-[#10B981] text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 hover:bg-emerald-600 hover:shadow-emerald-600/40 transition-all transform hover:-translate-y-1">
                        Confirm Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ACTIVE LIST -->
<div class="glass-card rounded-3xl overflow-hidden">
    <div class="p-8 border-b border-white/5">
        <h3 class="text-xl font-bold text-white">Currently Borrowed Books</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white/5 text-slate-400 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-8 py-4">Student</th>
                    <th class="px-6 py-4">Book Details</th>
                    <th class="px-6 py-4">Borrowed On</th>
                    <th class="px-6 py-4">Due Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($activeTransactions as $trans)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-8 py-5">
                        <div class="font-bold text-white text-lg">{{ $trans->user->name }}</div>
                        <!-- UPDATED: Show Student ID -->
                        <div class="text-xs text-slate-400 font-mono mt-1">ID: {{ $trans->user->student_id ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-slate-300">{{ $trans->bookCopy->book->title }}</div>
                        <span class="inline-block mt-1 text-xs bg-white/10 text-slate-400 px-2 py-1 rounded font-mono border border-white/10">{{ $trans->bookCopy->accession_number }}</span>
                    </td>
                    <td class="px-6 py-5 text-sm font-medium text-slate-400">
                        {{ \Carbon\Carbon::parse($trans->borrowed_at)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-5">
                        @php 
                            $due = \Carbon\Carbon::parse($trans->due_date);
                            $isOverdue = now()->gt($due);
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $isOverdue ? 'bg-red-500/20 text-red-400 border border-red-500/20' : 'bg-blue-500/20 text-blue-400 border border-blue-500/20' }}">
                                {{ $due->format('M d, Y') }}
                            </span>
                            @if($isOverdue)
                                <i class="fas fa-exclamation-circle text-red-500 animate-pulse" title="Overdue"></i>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection