@extends('layouts.app')
@section('title', 'Catat Pengambilan - Gudang AFNALINK')
@section('page-title', 'Catat Pengambilan')
@section('page-subtitle', 'Teknisi ambil barang dari gudang')
@section('content')
<div class="max-w-xl">
    <div class="glass rounded-2xl p-6 lg:p-8">
        <div class="mb-6 rounded-xl bg-amber-500/10 border border-amber-500/20 px-4 py-3 flex gap-3">
            <span class="text-amber-400">⚠️</span>
            <div class="text-xs text-amber-200/80">Operator mencatat: siapa teknisi, barang apa, jumlah berapa. Stok akan otomatis berkurang. Validasi stok minus aktif.</div>
        </div>
        <form method="POST" action="{{ route('barang-keluar.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-zinc-300">Jabatan *</label>
                <select name="teknisi_jabatan" required onchange="window.location.href='{{ route('barang-keluar.create') }}?jabatan='+this.value" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                    @foreach($jabatans as $j)
                        <option value="{{ $j }}" class="bg-[#08080f]" {{ old('teknisi_jabatan', request('jabatan'))==$j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                <div class="text-xs text-zinc-500 mt-1">Pilih jabatan dulu, lalu pilih nama dari list data personil</div>
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Nama Personil *</label>
                @if($karyawans->count() > 0)
                    <select name="teknisi_nama" id="select-nama" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="" class="bg-[#08080f]">Pilih nama</option>
                        @foreach($karyawans as $k)
                            <option value="{{ $k->nama }}" class="bg-[#08080f]" {{ old('teknisi_nama')==$k->nama ? 'selected' : '' }}>{{ $k->nama }} — {{ $k->jabatan }}</option>
                        @endforeach
                    </select>
                    <div class="text-xs text-zinc-500 mt-1">List dari <a href="{{ route('pegawai.index') }}" class="text-cyan-400 hover:underline">Data Pegawai</a> ({{ $karyawans->count() }} orang). <span class="text-zinc-600">Atau</span> <a href="#" onclick="const w=document.getElementById('manual-nama-wrap'); w.classList.toggle('hidden'); const s=document.getElementById('select-nama'); const m=document.getElementById('manual-nama'); if(!w.classList.contains('hidden')){m.name='teknisi_nama'; m.required=true; s.removeAttribute('name'); s.required=false;}else{m.removeAttribute('name'); m.required=false; s.name='teknisi_nama'; s.required=true;} return false;" class="text-cyan-400 hover:underline">input manual</a></div>
                    <div id="manual-nama-wrap" class="hidden">
                        <input type="text" id="manual-nama" placeholder="Budi Santoso (manual jika tidak ada di list)" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
                    </div>
                @else
                    <input type="text" name="teknisi_nama" value="{{ old('teknisi_nama') }}" required placeholder="Budi Santoso" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
                    <div class="text-xs text-amber-400 mt-1">Belum ada data pegawai untuk jabatan ini. <a href="{{ route('pegawai.create') }}" class="text-cyan-400 hover:underline">+ Tambah pegawai</a> dulu di <a href="{{ route('pegawai.index') }}" class="text-cyan-400 hover:underline">Data Pegawai</a>.</div>
                @endif
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Barang *</label>
                <select name="barang_id" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="" class="bg-[#08080f]">Pilih barang (hanya stok >0)</option>
                    @foreach($barangs as $b)
                        <option value="{{ $b->id }}" class="bg-[#08080f]" {{ (request('barang_id')==$b->id || old('barang_id')==$b->id) ? 'selected' : '' }}>{{ $b->nama }} — sisa {{ $b->stok }} {{ $b->satuan }} ({{ $b->kategori }})</option>
                    @endforeach
                </select>
                @if($barangs->isEmpty())<div class="text-xs text-amber-400 mt-2">Semua stok habis! Tambah stok masuk dulu.</div>@endif
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Jumlah *</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" required min="1" placeholder="1" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
                <div class="text-xs text-zinc-500 mt-1">Sistem akan tolak jika melebihi sisa stok.</div>
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="SN-123456 (opsional, untuk router/ONT)" class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-medium text-zinc-300">Keperluan</label>
                <textarea name="keperluan" rows="3" placeholder="Instalasi pelanggan Jl. Mawar No.10, dll..." class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">{{ old('keperluan') }}</textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('barang-keluar.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-red-500 to-orange-600 text-white text-sm font-semibold">Catat Pengambilan</button>
            </div>
        </form>
    </div>
</div>
@endsection
