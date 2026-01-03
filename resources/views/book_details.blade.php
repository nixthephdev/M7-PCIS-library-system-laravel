@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('inventory.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors group">
            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-[#2563EB] group-hover:text-white transition-all">
                <i class="fas fa-arrow-left"></i>
            </div>
            <span class="font-bold">Back to Inventory</span>
        </a>
    </div>

    <!-- BOOK INFO CARD -->
    <div class="glass-card rounded-3xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#2563EB] rounded-full blur-[100px] opacity-20 -mr-10 -mt-10 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row gap-8 items-start">
            <div class="w-32 h-40 rounded-2xl bg-gradient-to-br from-[#2563EB] to-[#1e3a8a] flex items-center justify-center shadow-2xl flex-shrink-0">
                <i class="fas fa-book text-5xl text-white/50"></i>
            </div>

            <div class="flex-1 w-full">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-4xl font-black text-white">{{ $book->title }}</h1>
                            <button onclick="openEditModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#2563EB] flex items-center justify-center text-white transition-all" title="Edit Details & Stock">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                        </div>
                        <p class="text-xl text-slate-300 font-medium mb-4">{{ $book->author }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-xl bg-white/10 border border-white/10 text-white font-mono font-bold">
                        ISBN: {{ $book->isbn }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Copies</p>
                        <p class="text-2xl font-bold text-white">{{ $book->copies->count() }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20">
                        <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1">Available</p>
                        <p class="text-2xl font-bold text-emerald-100">{{ $book->copies->where('status', 'available')->count() }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/20">
                        <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-1">Borrowed</p>
                        <p class="text-2xl font-bold text-blue-100">{{ $book->copies->where('status', 'borrowed')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COPIES LIST -->
    <h3 class="text-xl font-bold text-white mb-6 pl-2">Physical Copies & Status</h3>
    
    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-slate-400 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-8 py-5">Accession #</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-6 py-5">Location / Borrower</th>
                        <th class="px-6 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($book->copies as $copy)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-8 py-5">
                            <span class="font-mono text-white font-bold bg-white/10 px-3 py-1.5 rounded-lg border border-white/5">
                                {{ $copy->accession_number }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($copy->status == 'available')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Available
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Borrowed
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($copy->status == 'borrowed')
                                @php $activeTrans = $copy->borrowTransactions->whereNull('returned_at')->first(); @endphp
                                @if($activeTrans)
                                    <div class="flex items-center gap-3">
                                        @if($activeTrans->user->avatar)
                                            <img src="{{ asset('storage/' . $activeTrans->user->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-white/20">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($activeTrans->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ $activeTrans->user->name }}</p>
                                            <p class="text-xs text-slate-400">Due: {{ \Carbon\Carbon::parse($activeTrans->due_date)->format('M d') }}</p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <span class="text-slate-400 flex items-center gap-2"><i class="fas fa-archive"></i> On Shelf</span>
                            @endif
                        </td>
                        <!-- DELETE BUTTON -->
                        <td class="px-6 py-5 text-right">
                            <form action="{{ route('copy.delete', $copy->id) }}" method="POST" onsubmit="return confirm('Are you sure this copy is lost/damaged? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all" title="Remove Copy (Lost/Damaged)">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
            <h3 class="text-2xl font-bold text-white mb-6">Edit Book Details</h3>
            
            <form action="{{ route('inventory.update', $book->id) }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Book Title</label>
                    <input type="text" name="title" value="{{ $book->title }}" class="w-full glass-input rounded-xl px-4 py-3 font-medium" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Author</label>
                    <input type="text" name="author" value="{{ $book->author }}" class="w-full glass-input rounded-xl px-4 py-3 font-medium" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">ISBN</label>
                    <input type="text" name="isbn" value="{{ $book->isbn }}" class="w-full glass-input rounded-xl px-4 py-3 font-medium" required>
                </div>
                
                <!-- ADD COPIES FIELD -->
                <div class="pt-4 border-t border-white/10">
                    <label class="block text-xs font-bold text-[#2563EB] uppercase mb-2">Add More Copies</label>
                    <div class="flex gap-2">
                        <input type="number" name="add_copies" class="w-full glass-input rounded-xl px-4 py-3 font-medium" placeholder="Qty to add (e.g. 5)">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Leave empty if you don't want to add stock.</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-3 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-[#2563EB] text-white font-bold rounded-xl hover:bg-blue-600 transition-all shadow-lg shadow-blue-600/20">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal() { document.getElementById('editModal').classList.remove('hidden'); }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }
</script>
@endsection