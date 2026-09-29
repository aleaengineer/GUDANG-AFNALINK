@extends('layouts.app')
@section('title', 'Catat Pengambilan - Gudang AFNALINK')
@section('page-title', 'Catat Pengambilan')
@section('page-subtitle', 'Teknisi ambil barang dari gudang')
@section('content')
<div class="max-w-3xl">
    <div class="glass rounded-2xl p-5 sm:p-6 lg:p-8">
        <div class="mb-6 rounded-xl bg-amber-500/10 border border-amber-500/20 px-4 py-3 flex gap-3">
            <span class="text-amber-400">⚠️</span>
            <div class="text-xs text-amber-200/80">@if($isTeknisi)Catat barang yang kamu ambil. Bisa lebih dari 1 barang dalam 1 kali pencatatan. Stok otomatis berkurang dan sistem menolak jika melebihi sisa stok.@else Catat siapa teknisi, barang apa, berapa. Bisa lebih dari 1 barang dalam 1 kali pencatatan. Stok otomatis berkurang.@endif</div>
        </div>
        <form method="POST" action="{{ route('barang-keluar.store') }}" class="space-y-5">
            @csrf
            @if($isTeknisi)
            <div class="rounded-xl bg-cyan-500/10 border border-cyan-500/20 px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-sm font-bold text-cyan-300 shrink-0">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-cyan-300/80">{{ auth()->user()->jabatan }} • {{ auth()->user()->email }}</div>
                    </div>
                </div>
                <input type="hidden" name="teknisi_nama" value="{{ auth()->user()->name }}">
                <input type="hidden" name="teknisi_jabatan" value="{{ auth()->user()->jabatan }}">
                <div class="text-[11px] text-cyan-200/60 mt-2">Nama & jabatan diambil otomatis dari akun kamu dan tidak bisa diubah.</div>
            </div>
            @else
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
            @endif

            {{-- ===== DAFTAR BARANG (multi) ===== --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-zinc-300">Barang yang diambil *</label>
                    <span class="text-xs text-zinc-500"><span id="jumlah-item">1</span> jenis barang</span>
                </div>
                <div id="item-list" class="space-y-3">
                    @php
                        $oldItems = old('items');
                        $rows = (is_array($oldItems) && count($oldItems))
                            ? $oldItems
                            : [['barang_id' => request('barang_id'), 'jumlah' => 1, 'serial_number' => '', 'keperluan' => '']];
                    @endphp
                    @foreach($rows as $i => $row)
                        @include('barang-keluar._item-row', ['idx' => $i, 'row' => $row])
                    @endforeach
                </div>
                <button type="button" id="tambah-item"
                        class="mt-3 w-full py-3 rounded-xl border border-dashed border-cyan-500/40 bg-cyan-500/[0.06] text-cyan-300 text-sm font-medium hover:bg-cyan-500/10">
                    + Tambah Barang Lain
                </button>
                <div class="text-xs text-zinc-500 mt-2">Semua barang di bawah dicatat dalam <span class="text-zinc-300">1 kali pencatatan</span>.</div>
                @if($barangs->isEmpty())<div class="text-xs text-amber-400 mt-2">Semua stok habis! Tambah stok masuk dulu.</div>@endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('barang-keluar.index') }}" class="flex-1 py-3 rounded-xl glass text-center text-sm text-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-red-500 to-orange-600 text-white text-sm font-semibold">Catat Pengambilan</button>
            </div>
        </form>
    </div>
</div>

<template id="tpl-item">
    @include('barang-keluar._item-row', ['idx' => '__INDEX__', 'row' => ['barang_id' => '', 'jumlah' => 1, 'serial_number' => '', 'keperluan' => '']])
</template>

@push('scripts')
<script>
(function () {
    var list = document.getElementById('item-list');
    var tpl = document.getElementById('tpl-item');
    var btnTambah = document.getElementById('tambah-item');
    var counter = document.getElementById('jumlah-item');
    var maks = 30;

    if (!list || !tpl || !btnTambah) { console.error('barang-keluar: elemen form tidak ditemukan'); return; }

    function infoSisa(select) {
        var row = select.closest('[data-item]');
        if (!row) return;
        var opt = select.options[select.selectedIndex];
        var stokEl = row.querySelector('[data-info-stok]');
        var satEl = row.querySelector('[data-info-satuan]');
        var jumlah = row.querySelector('[data-jumlah]');
        if (opt && opt.value && opt.dataset.stok !== undefined) {
            stokEl.textContent = opt.dataset.stok;
            satEl.textContent = opt.dataset.satuan || '';
            var sisa = parseInt(opt.dataset.stok, 10);
            jumlah.max = sisa > 0 ? sisa : 1;
            jumlah.classList.toggle('text-red-400', parseInt(jumlah.value || 0, 10) > sisa);
        } else {
            stokEl.textContent = '-';
            satEl.textContent = '';
            jumlah.removeAttribute('max');
        }
    }

    function renumber() {
        var rows = list.querySelectorAll('[data-item]');
        rows.forEach(function (row, i) {
            row.querySelectorAll('input, select').forEach(function (el) {
                el.name = el.name.replace(/items\[[^\]]*\]/, 'items[' + i + ']');
            });
            var no = row.querySelector('[data-no]');
            if (no) no.textContent = 'BARANG ' + (i + 1);
            var hapus = row.querySelector('[data-hapus-row]');
            if (hapus) hapus.style.visibility = rows.length === 1 ? 'hidden' : 'visible';
        });
        if (counter) counter.textContent = rows.length;
        btnTambah.disabled = rows.length >= maks;
        btnTambah.classList.toggle('opacity-40', rows.length >= maks);
    }

    function tambahBaris() {
        if (list.querySelectorAll('[data-item]').length >= maks) return;
        var html = tpl.innerHTML.replace(/__INDEX__/g, String(list.querySelectorAll('[data-item]').length + 1));
        var wrap = document.createElement('div');
        wrap.innerHTML = html;
        var row = wrap.firstElementChild;
        if (!row) { console.error('barang-keluar: template baris tidak valid'); return; }
        list.appendChild(row);
        renumber();
        var sel = row.querySelector('select[name$="[barang_id]"]');
        if (sel) sel.focus();
    }

    list.addEventListener('change', function (e) {
        if (e.target.matches('select[name$="[barang_id]"]')) infoSisa(e.target);
    });
    list.addEventListener('input', function (e) {
        if (e.target.matches('input[name$="[jumlah]"]')) {
            var sel = e.target.closest('[data-item]').querySelector('select[name$="[barang_id]"]');
            if (sel) infoSisa(sel);
        }
    });
    list.addEventListener('click', function (e) {
        var h = e.target.closest('[data-hapus-row]');
        if (!h) return;
        if (list.querySelectorAll('[data-item]').length <= 1) return;
        h.closest('[data-item]').remove();
        renumber();
    });

    btnTambah.addEventListener('click', tambahBaris);

    list.querySelectorAll('[data-item]').forEach(function (row) {
        var sel = row.querySelector('select[name$="[barang_id]"]');
        if (sel) infoSisa(sel);
    });
    renumber();
})();
</script>
@endpush
@endsection
