@extends('layout')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- ========================================== -->
    <!-- LEFT COLUMN: REGISTER FORM -->
    <!-- ========================================== -->
    <div class="lg:col-span-1">
        <div class="glass-card rounded-3xl p-6 sticky top-6">
            <div class="flex items-center gap-3 mb-6 border-b border-white/5 pb-4">
                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-[#EF4444]">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 class="text-lg font-bold text-white">New Student</h3>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- AVATAR UPLOAD -->
                <div class="flex flex-col items-center mb-4">
                    <div onclick="document.getElementById('newAvatarInput').click()" class="w-full cursor-pointer group">
                        <div class="flex items-center gap-3 p-3 rounded-xl glass-input hover:bg-white/5 transition-colors border border-dashed border-slate-500 hover:border-[#2563EB] relative overflow-hidden">
                            <div id="defaultIcon" class="flex items-center gap-3 w-full">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-slate-400 group-hover:text-white transition-colors">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">Upload Photo</p>
                                    <p class="text-[10px] text-slate-400">Click to browse...</p>
                                </div>
                            </div>
                            <div id="previewContainer" class="hidden items-center gap-3 w-full">
                                <img id="avatarPreview" class="w-10 h-10 rounded-full object-cover border-2 border-[#2563EB]">
                                <div>
                                    <p class="text-xs font-bold text-[#2563EB]">Image Selected</p>
                                    <p class="text-[10px] text-slate-400">Click to change</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="file" name="avatar" id="newAvatarInput" class="hidden" accept="image/*" onchange="previewNewAvatar(event)">
                    @error('avatar') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- STUDENT ID -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Student ID</label>
                    <input type="text" name="student_id" class="w-full glass-input rounded-xl px-4 py-3 font-mono" placeholder="e.g. IB0001" required>
                    @error('student_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Full Name</label>
                    <input type="text" name="name" class="w-full glass-input rounded-xl px-4 py-3" placeholder="e.g. Juan Dela Cruz" required>
                    @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Email Address</label>
                    <input type="email" name="email" class="w-full glass-input rounded-xl px-4 py-3" placeholder="e.g. student@pcis.edu.ph" required>
                    @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full py-3 bg-white text-[#0B1120] font-bold rounded-xl hover:bg-slate-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Register Student
                </button>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- RIGHT COLUMN: ADMINS & STUDENTS -->
    <!-- ========================================== -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- 1. ADMINS & LIBRARIANS SECTION -->
        <div>
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-user-shield text-[#2563EB]"></i> Admin Members & Librarians
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($admins as $admin)
                <div class="glass-card p-4 rounded-2xl flex items-center gap-4 border border-white/5 relative overflow-hidden group">
                    <!-- Glow Effect -->
                    <div class="absolute top-0 right-0 w-16 h-16 bg-[#2563EB] rounded-full blur-[40px] opacity-10 group-hover:opacity-20 transition-opacity"></div>
                    
                    <!-- Avatar -->
                    @if($admin->avatar)
                        <img src="{{ asset('storage/' . $admin->avatar) }}" class="w-12 h-12 rounded-full object-cover border-2 border-[#2563EB]">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#2563EB] to-[#1D4ED8] flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                    @endif

                    <!-- Info -->
                    <div>
                        <h4 class="text-white font-bold">{{ $admin->name }}</h4>
                        <p class="text-xs text-blue-300 uppercase tracking-wider font-bold">
                            {{ $admin->role === 'admin' ? 'Administrator' : 'Librarian' }}
                        </p>
                        <p class="text-[10px] text-slate-400">{{ $admin->email }}</p>
                    </div>

                    <!-- Admin Badge -->
                    <div class="ml-auto">
                        <i class="fas fa-shield-alt text-white/20 text-2xl"></i>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 2. REGISTERED STUDENTS TABLE -->
        <div class="glass-card rounded-3xl overflow-hidden">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-users text-emerald-400"></i> Registered Students
                </h3>
                <span class="bg-white/10 text-slate-300 px-3 py-1 rounded-full text-xs font-bold">{{ $students->count() }} Total</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white/5 text-slate-400 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Student Profile</th>
                            <th class="px-6 py-4">Student ID</th>
                            <th class="px-6 py-4">Joined Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($students as $student)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($student->avatar)
                                        <img src="{{ asset('storage/' . $student->avatar) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white/10 shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-white/10 text-slate-300 flex items-center justify-center font-bold text-sm group-hover:bg-[#EF4444] group-hover:text-white transition-colors">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-white">{{ $student->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm bg-white/5 px-2 py-1 rounded text-slate-300 font-bold border border-white/5">
                                    {{ $student->student_id ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $student->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditUserModal('{{ $student->id }}', '{{ $student->name }}', '{{ $student->email }}', '{{ $student->student_id }}')" class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all" title="Edit">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <form action="{{ route('users.delete', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all" title="Delete">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- EDIT USER MODAL (Same as before) -->
<div id="editUserModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeEditUserModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
            <h3 class="text-2xl font-bold text-white mb-6">Edit Member</h3>
            <form id="editUserForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Update Photo</label>
                    <input type="file" name="avatar" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#2563EB] file:text-white hover:file:bg-blue-600 glass-input rounded-xl">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remove_avatar" id="removeAvatar" class="w-4 h-4 rounded bg-white/10 border-white/20 text-[#EF4444] focus:ring-0">
                    <label for="removeAvatar" class="text-sm text-slate-300">Remove current profile picture</label>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Student ID</label>
                    <input type="text" name="student_id" id="editStudentId" class="w-full glass-input rounded-xl px-4 py-3 font-medium font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Full Name</label>
                    <input type="text" name="name" id="editName" class="w-full glass-input rounded-xl px-4 py-3 font-medium" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Email Address</label>
                    <input type="email" name="email" id="editEmail" class="w-full glass-input rounded-xl px-4 py-3 font-medium" required>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditUserModal()" class="flex-1 py-3 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-[#2563EB] text-white font-bold rounded-xl hover:bg-blue-600 transition-all shadow-lg shadow-blue-600/20">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewNewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('defaultIcon').classList.add('hidden');
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('previewContainer').classList.add('flex');
            document.getElementById('avatarPreview').src = URL.createObjectURL(file);
        }
    }
    function openEditUserModal(id, name, email, studentId) {
        document.getElementById('editUserModal').classList.remove('hidden');
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editStudentId').value = studentId;
        document.getElementById('removeAvatar').checked = false;
        let form = document.getElementById('editUserForm');
        form.action = "/users/update/" + id; 
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
@endsection