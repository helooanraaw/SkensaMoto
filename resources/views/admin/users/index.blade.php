<x-sidebar-layout>
    <x-slot name="title">Manajemen Akun</x-slot>

    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-blue-950">Manajemen Akun</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Atur wewenang pengguna (Admin, Guru, Mekanik, Pelanggan).</p>
        </div>
        
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
            <select name="role" class="border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-blue-950 bg-white focus:ring-red-500 focus:border-red-500 transition-colors" onchange="this.form.submit()">
                <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="mekanik" {{ request('role') == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pelanggan</option>
            </select>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-[24px] border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Pengguna</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Kontak</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Role Saat Ini</th>
                        <th class="py-4 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-right">Ubah Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-blue-950">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">Terdaftar: {{ $user->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-sm font-bold text-slate-700">{{ $user->email }}</p>
                                <p class="text-xs text-slate-500">{{ $user->nomor_telepon ?? 'Belum ada WA' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $roleColors = [
                                        'superadmin' => 'bg-red-100 text-red-700',
                                        'admin' => 'bg-purple-100 text-purple-700',
                                        'guru' => 'bg-emerald-100 text-emerald-700',
                                        'mekanik' => 'bg-blue-100 text-blue-700',
                                        'user' => 'bg-slate-100 text-slate-700',
                                    ];
                                    $roleLabels = [
                                        'superadmin' => 'Super Admin',
                                        'admin' => 'Admin',
                                        'guru' => 'Guru',
                                        'mekanik' => 'Mekanik',
                                        'user' => 'Pelanggan',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleColors[$user->role] }}">
                                    {{ $roleLabels[$user->role] }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.update_role', $user) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="text-sm border-slate-200 rounded-lg px-3 py-1.5 font-bold text-slate-600 bg-white focus:ring-red-500 focus:border-red-500" required>
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Pelanggan</option>
                                            <option value="mekanik" {{ $user->role == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            @if(auth()->user()->role == 'superadmin')
                                            <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                            @endif
                                        </select>
                                        <button type="submit" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Simpan Perubahan Role">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-bold text-slate-400 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <p class="font-bold text-blue-950">Tidak ada data pengguna.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
    </div>
</x-sidebar-layout>
