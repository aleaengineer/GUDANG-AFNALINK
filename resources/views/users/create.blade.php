@extends('layouts.app')
@section('title', 'Tambah User - Gudang AFNALINK')
@section('page-title', 'Tambah User')
@section('page-subtitle', 'Buat akun baru')
@section('content')
<div class="max-w-xl">
    <div class="glass rounded-2xl p-6 lg:p-8">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-zinc-300">Nama *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">Password *</label>
                    <input type="password" name="password" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">Jabatan *</label>
                    <select name="jabatan" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                        @foreach($jabatans as $j)
                            <option value="{{ $j }}" class="bg-[#08080f]" {{ old('jabatan')==$j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Role *</label>
                    <select name="role" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="operator" class="bg-[#08080f]" {{ old('role')=='operator' ? 'selected' : '' }}>Operator</option>
                        <option value="admin" class="bg-[#08080f]" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('users.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-semibold">Simpan User</button>
            </div>
        </form>
    </div>
</div>
@endsection
