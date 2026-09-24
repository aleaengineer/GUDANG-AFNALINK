@extends('layouts.app')
@section('title', 'Tambah Barang - Gudang AFNALINK')
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Tambah inventory baru')
@section('content')
<div class="max-w-2xl">
    <div class="glass rounded-2xl p-6 lg:p-8">
        <form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-zinc-300">Nama Barang *</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Router TP-Link TL-WR840N" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                @error('nama')<div class="text-xs text-red-400 mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">Kategori *</label>
                    <input type="text" name="kategori" list="kategori-list" value="{{ old('kategori') }}" required placeholder="Ketik manual atau pilih — mis. Router" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                    <datalist id="kategori-list">
                        @foreach($kategoris as $k)
                            <option value="{{ $k }}"></option>
                        @endforeach
                    </datalist>
                    <div class="text-[11px] text-zinc-500 mt-1">Bisa ketik kategori baru, otomatis tersimpan</div>
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Merk</label>
                    <input type="text" name="merk" value="{{ old('merk') }}" placeholder="TP-Link" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-300">Stok Awal *</label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" required min="0" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-300">Satuan *</label>
                    <select name="satuan" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="pcs" class="bg-[#08080f]">pcs</option>
                        <option value="unit" class="bg-[#08080f]">unit</option>
                        <option value="roll" class="bg-[#08080f]">roll</option>
                        <option value="meter" class="bg-[#08080f]">meter</option>
                        <option value="box" class="bg-[#08080f]">box</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="SN-123456 (opsional)" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Spesifikasi</label>
                <textarea name="spesifikasi" rows="3" placeholder="Deskripsi, spesifikasi teknis..." class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">{{ old('spesifikasi') }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Foto Barang</label>
                <input type="file" name="foto" accept="image/*" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-cyan-500/20 file:text-cyan-300">
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('barang.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-semibold">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection
