@extends('layouts.app')
@section('title', 'Tambah Barang Masuk - Gudang AFNALINK')
@section('page-title', 'Barang Masuk')
@section('page-subtitle', 'Tambah stok masuk')
@section('content')
<div class="max-w-xl">
    <div class="glass rounded-2xl p-6 lg:p-8">
        <form method="POST" action="{{ route('barang-masuk.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-zinc-300">Barang *</label>
                <select name="barang_id" id="barang-select" required onchange="toggleCustomBarang()" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="" class="bg-[#08080f]">Pilih barang dari list</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" class="bg-[#08080f]" {{ (request('barang_id')==$b->id || old('barang_id')==$b->id) ? 'selected' : '' }}>{{ $b->nama }} — stok: {{ $b->stok }} {{ $b->satuan }} ({{ $b->kategori }})</option>
                    @endforeach
                    <option value="custom" class="bg-[#08080f]" {{ old('barang_id')=='custom' ? 'selected' : '' }}>— Ketik Manual (barang baru diluar list) —</option>
                </select>
                <div id="custom-barang-wrap" class="hidden mt-3 p-4 rounded-xl bg-white/[0.04] border border-white/[0.06] space-y-3">
                    <div class="text-xs font-medium text-cyan-300">Barang Baru (custom)</div>
                    <input type="text" name="custom_nama" id="custom-nama" value="{{ old('custom_nama') }}" placeholder="Nama barang baru — mis. Kabel LAN 10m" class="w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-cyan-500/50 focus:outline-none">
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="custom_kategori" value="{{ old('custom_kategori', 'Lainnya') }}" placeholder="Kategori — mis. Kabel" class="rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
                        <input type="text" name="custom_satuan" value="{{ old('custom_satuan', 'pcs') }}" placeholder="Satuan — pcs/roll" class="rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
                    </div>
                    <div class="text-xs text-zinc-500">Akan dibuat sebagai barang baru dengan stok 0, lalu ditambah sesuai Jumlah di bawah.</div>
                </div>
                <div class="text-xs text-zinc-500 mt-1">Pilih dari list atau pilih <span class="text-cyan-400">Ketik Manual</span> untuk barang diluar list</div>
            </div>
            <script>
                function toggleCustomBarang(){
                    const sel = document.getElementById('barang-select');
                    const wrap = document.getElementById('custom-barang-wrap');
                    const input = document.getElementById('custom-nama');
                    if(sel.value === 'custom'){
                        wrap.classList.remove('hidden');
                        input.required = true;
                        sel.required = false;
                    } else {
                        wrap.classList.add('hidden');
                        input.required = false;
                        sel.required = true;
                    }
                }
                // init on load
                document.addEventListener('DOMContentLoaded', toggleCustomBarang);
            </script>
            <div>
                <label class="text-sm font-medium text-zinc-300">Jumlah *</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" required min="1" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Sumber</label>
                <input type="text" name="sumber" value="{{ old('sumber') }}" placeholder="Supplier A, Pembelian, dll" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Catatan</label>
                <textarea name="catatan" rows="3" placeholder="Keterangan tambahan..." class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">{{ old('catatan') }}</textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('barang-masuk.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-semibold">Simpan Masuk</button>
            </div>
        </form>
    </div>
</div>
@endsection
