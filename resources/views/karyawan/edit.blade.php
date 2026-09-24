@extends('layouts.app')
@section('title', 'Edit Pegawai - Gudang AFNALINK')
@section('page-title', 'Edit Pegawai')
@section('page-subtitle', $karyawan->nama)
@section('content')
<div class="max-w-xl">
    <div class="glass rounded-2xl p-6 lg:p-8">
        <form method="POST" action="{{ route('karyawan.update', $karyawan) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="text-sm font-medium text-zinc-300">Nama Lengkap *</label>
                <input type="text" name="nama" value="{{ old('nama', $karyawan->nama) }}" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Jabatan *</label>
                <select name="jabatan" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                    @foreach($jabatans as $j)
                        <option value="{{ $j }}" class="bg-[#08080f]" {{ old('jabatan', $karyawan->jabatan)==$j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $karyawan->no_hp) }}" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Status *</label>
                    <select name="status" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="aktif" class="bg-[#08080f]" {{ old('status', $karyawan->status)=='aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" class="bg-[#08080f]" {{ old('status', $karyawan->status)=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Email</label>
                <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Alamat</label>
                <textarea name="alamat" rows="2" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">{{ old('alamat', $karyawan->alamat) }}</textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('karyawan.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-semibold">Update Pegawai</button>
            </div>
        </form>
    </div>
</div>
@endsection
