@extends('layouts.app')
@section('title', 'Tambah Pegawai - Gudang AFNALINK')
@section('page-title', 'Tambah Pegawai')
@section('page-subtitle', 'Daftar nama & jabatan')
@section('content')
<div class="max-w-xl">
    <div class="glass rounded-2xl p-6 lg:p-8">
        <form method="POST" action="{{ route('karyawan.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-zinc-300">Nama Lengkap *</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Budi Santoso" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('nama')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Jabatan *</label>
                <select name="jabatan" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                    @foreach($jabatans as $j)
                        <option value="{{ $j }}" class="bg-[#08080f]" {{ old('jabatan')==$j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-zinc-500 mt-1">Pilihan: Teknisi, NOC, Marketing, Kasir, CEO, CFO, CMO</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Status *</label>
                    <select name="status" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="aktif" class="bg-[#08080f]" {{ old('status','aktif')=='aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" class="bg-[#08080f]" {{ old('status')=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="budi@afnalink.my.id" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat lengkap..." class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">{{ old('alamat') }}</textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('karyawan.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-semibold">Simpan Pegawai</button>
            </div>
        </form>
    </div>
</div>
@endsection
