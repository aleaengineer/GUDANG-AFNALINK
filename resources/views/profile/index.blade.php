@extends('layouts.app')
@section('title', 'Profile - Gudang AFNALINK')
@section('page-title', 'Profile')
@section('page-subtitle', 'Kelola informasi akun')
@section('content')
<div class="max-w-3xl space-y-6">
    <!-- Header Card -->
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
            </div>
            <div>
                <div class="text-lg font-bold text-white">{{ auth()->user()->name }}</div>
                <div class="text-sm text-zinc-400">{{ auth()->user()->email }}</div>
                <div class="flex gap-2 mt-1">
                    <span class="text-xs px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300">{{ auth()->user()->jabatan }}</span>
                    <span class="text-xs px-2.5 py-1 rounded-full {{ auth()->user()->role=='admin' ? 'bg-amber-500/10 border-amber-500/20 text-amber-300' : 'bg-white/[0.06] border-white/[0.06] text-zinc-300' }}">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Profile -->
    <div class="glass rounded-2xl p-6 lg:p-8">
        <h3 class="text-base font-semibold text-white mb-1">Informasi Profile</h3>
        <p class="text-xs text-zinc-500 mb-6">Ubah nama, email dan nomor HP</p>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="text-sm font-medium text-zinc-300">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('name')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('email')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Nomor HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="08123456789" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('no_hp')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">Jabatan</label>
                    <input type="text" value="{{ $user->jabatan }}" disabled class="mt-2 w-full rounded-xl bg-white/[0.04] border border-white/[0.06] px-4 py-3 text-sm text-zinc-500">
                    <div class="text-xs text-zinc-600 mt-1">Hubungi admin untuk ubah jabatan</div>
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Role</label>
                    <input type="text" value="{{ ucfirst($user->role) }}" disabled class="mt-2 w-full rounded-xl bg-white/[0.04] border border-white/[0.06] px-4 py-3 text-sm text-zinc-500">
                </div>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-semibold">Simpan Perubahan</button>
        </form>
    </div>

    <!-- Update Password -->
    <div class="glass rounded-2xl p-6 lg:p-8">
        <h3 class="text-base font-semibold text-white mb-1">Ubah Password</h3>
        <p class="text-xs text-zinc-500 mb-6">Ganti password akun</p>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="text-sm font-medium text-zinc-300">Password Saat Ini *</label>
                <input type="password" name="current_password" required placeholder="••••••••" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('current_password')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Password Baru *</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('password')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Konfirmasi Password Baru *</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password baru" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-white/[0.06] border border-white/[0.08] text-white text-sm font-semibold hover:bg-white/[0.08]">Update Password</button>
        </form>
    </div>
</div>
@endsection
