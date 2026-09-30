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
                <select name="teknisi_jabatan" id="select-jabatan" required class="mt-2 w-full rounded-xl bg-[#08080f] border border-white/[0.08] px-4 py-3 text-sm text-white focus:outline-none">
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
                            <option value="{{ $k->nama }}" data-jabatan="{{ $k->jabatan }}" class="bg-[#08080f]" {{ old('teknisi_nama')==$k->nama ? 'selected' : '' }}>{{ $k->nama }} — {{ $k->jabatan }}</option>
                        @endforeach
                    </select>
                    <div id="nama-tak-ada" class="hidden text-xs text-amber-400 mt-1">Belum ada pegawai aktif untuk jabatan ini. <a href="{{ route('pegawai.create') }}" class="text-cyan-400 hover:underline">+ Tambah pegawai</a> dulu, atau pakai input manual di bawah.</div>
                    <div class="text-xs text-zinc-500 mt-1">List dari <a href="{{ route('pegawai.index') }}" class="text-cyan-400 hover:underline">Data Pegawai</a> ({{ $karyawans->count() }} personil aktif). <span class="text-zinc-600">Atau</span> <a href="#" onclick="const w=document.getElementById('manual-nama-wrap'); w.classList.toggle('hidden'); const s=document.getElementById('select-nama'); const m=document.getElementById('manual-nama'); if(!w.classList.contains('hidden')){m.name='teknisi_nama'; m.required=true; s.removeAttribute('name'); s.required=false;}else{m.removeAttribute('name'); m.required=false; s.name='teknisi_nama'; s.required=true;} return false;" class="text-cyan-400 hover:underline">input manual</a></div>
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
                <div class="text-xs text-zinc-500 mb-2">Ketik nama/kategori/merk barang — saran muncul otomatis, pilih dengan klik atau tombol panah + Enter.</div>
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

{{-- daftar barang untuk autocomplete (sekali saja, dipakai semua baris) --}}
@php
    $barangAutocomplete = $barangs->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama, 'kategori' => $b->kategori, 'merk' => $b->merk, 'stok' => (int) $b->stok, 'satuan' => $b->satuan])->values();
@endphp
<script type="application/json" id="barang-data">@json($barangAutocomplete)</script>

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
    var barangData = [];
    try {
        barangData = JSON.parse(document.getElementById('barang-data').textContent);
    } catch (e) { barangData = []; }

    if (!list || !tpl || !btnTambah) { console.error('barang-keluar: elemen form tidak ditemukan'); return; }

    function escapeHtml(s) {
        return String(s === undefined || s === null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    // ===== Autocomplete: ketik nama barang -> saran muncul saat keyword mendekati =====
    function cariBarang(q) {
        q = q.trim().toLowerCase();
        if (!q) return [];
        var kata = q.split(/\s+/);
        return barangData.filter(function (b) {
            var hay = (b.nama + ' ' + b.kategori + ' ' + (b.merk || '')).toLowerCase();
            return kata.every(function (k) { return hay.indexOf(k) !== -1; });
        }).slice(0, 8);
    }

    function inputBarang(row) { return row.querySelector('[data-ac-input]'); }
    function daftarSaran(row) { return row.querySelector('[data-ac-list]'); }

    function renderSaran(row, hasil) {
        var box = daftarSaran(row);
        box.innerHTML = '';
        hasil.forEach(function (b, i) {
            var el = document.createElement('button');
            el.type = 'button';
            el.dataset.id = b.id;
            el.className = 'block w-full text-left px-4 py-2.5 hover:bg-white/[0.06]' + (i === 0 ? ' bg-white/[0.04]' : '');
            el.innerHTML = '<div class="text-sm font-medium text-white">' + escapeHtml(b.nama) + '</div>' +
                '<div class="text-xs text-zinc-500">' + escapeHtml(b.kategori) + (b.merk ? ' • ' + escapeHtml(b.merk) : '') +
                ' — sisa ' + b.stok + ' ' + escapeHtml(b.satuan) + '</div>';
            el.addEventListener('mousedown', function (e) { e.preventDefault(); pilihBarang(row, b); });
            box.appendChild(el);
        });
        tandaiAktif(row, hasil.length ? 0 : -1);
        box.classList.toggle('hidden', hasil.length === 0);
    }

    function tandaiAktif(row, idx) {
        var items = daftarSaran(row).children;
        for (var i = 0; i < items.length; i++) {
            items[i].classList.toggle('bg-cyan-500/10', i === idx);
            items[i].classList.toggle('text-cyan-300', i === idx);
        }
        if (idx >= 0 && items[idx]) items[idx].scrollIntoView({ block: 'nearest' });
        inputBarang(row).dataset.aktif = String(idx);
    }

    function resetPilihanBarang(row) {
        var hidden = row.querySelector('[data-barang-id]');
        if (hidden) hidden.value = '';
        delete row.dataset.stok;
        delete row.dataset.satuan;
        var stokEl = row.querySelector('[data-info-stok]');
        if (stokEl) stokEl.textContent = '-';
        var satEl = row.querySelector('[data-info-satuan]');
        if (satEl) satEl.textContent = '';
        var jumlah = row.querySelector('[data-jumlah]');
        if (jumlah) {
            jumlah.removeAttribute('max');
            jumlah.classList.remove('text-red-400');
        }
    }

    function syncJumlah(row) {
        var sisa = parseInt(row.dataset.stok || '', 10);
        var jumlah = row.querySelector('[data-jumlah]');
        if (isNaN(sisa)) { jumlah.removeAttribute('max'); return; }
        jumlah.max = sisa > 0 ? sisa : 1;
        jumlah.classList.toggle('text-red-400', parseInt(jumlah.value || 0, 10) > sisa);
    }

    function pilihBarang(row, b) {
        row.querySelector('[data-barang-id]').value = b.id;
        var inp = inputBarang(row);
        inp.value = b.nama;
        inp.dataset.picked = '1';
        row.dataset.stok = b.stok;
        row.dataset.satuan = b.satuan;
        row.querySelector('[data-info-stok]').textContent = b.stok;
        row.querySelector('[data-info-satuan]').textContent = b.satuan;
        syncJumlah(row);
        tutupSaran(row);
    }

    function tutupSaran(row) {
        daftarSaran(row).classList.add('hidden');
        var kosong = row.querySelector('[data-ac-empty]');
        if (kosong) kosong.classList.add('hidden');
        inputBarang(row).dataset.aktif = '-1';
    }

    list.addEventListener('input', function (e) {
        var inp = e.target.closest('[data-ac-input]');
        if (inp) {
            var row = inp.closest('[data-item]');
            inp.dataset.picked = '';
            resetPilihanBarang(row);
            var hasil = cariBarang(inp.value);
            renderSaran(row, hasil);
            var kosong = row.querySelector('[data-ac-empty]');
            kosong.classList.toggle('hidden', !(inp.value.trim() && hasil.length === 0));
            return;
        }
        if (e.target.matches('input[name$="[jumlah]"]')) {
            syncJumlah(e.target.closest('[data-item]'));
        }
    });

    list.addEventListener('keydown', function (e) {
        var inp = e.target.closest('[data-ac-input]');
        if (!inp) return;
        var row = inp.closest('[data-item]');
        var box = daftarSaran(row);
        var total = box.children.length;
        var aktif = parseInt(inp.dataset.aktif || '-1', 10);
        if (e.key === 'ArrowDown' && total) {
            e.preventDefault();
            tandaiAktif(row, (aktif + 1) % total);
        } else if (e.key === 'ArrowUp' && total) {
            e.preventDefault();
            tandaiAktif(row, (aktif - 1 + total) % total);
        } else if (e.key === 'Enter' && total && !box.classList.contains('hidden') && aktif >= 0) {
            e.preventDefault();
            var dipilih = box.children[aktif].dataset.id;
            pilihBarang(row, barangData.find(function (b) { return String(b.id) === String(dipilih); }));
        } else if (e.key === 'Escape') {
            tutupSaran(row);
        }
    });

    list.addEventListener('focusout', function (e) {
        var inp = e.target.closest('[data-ac-input]');
        if (!inp) return;
        var row = inp.closest('[data-item]');
        setTimeout(function () {
            if (document.activeElement !== inp) tutupSaran(row);
        }, 120);
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-item]')) {
            list.querySelectorAll('[data-item]').forEach(tutupSaran);
        }
    });

    // Kalau submit sementara teks belum dipasangkan ke barang, coba pasangkan
    // otomatis dari ketikan yang persis sama dengan satu nama barang.
    list.closest('form').addEventListener('submit', function (e) {
        list.querySelectorAll('[data-item]').forEach(function (row) {
            var hidden = row.querySelector('[data-barang-id]');
            var inp = inputBarang(row);
            var teks = inp.value.trim();
            if (hidden.value || !teks) return;
            var cocok = barangData.filter(function (b) { return b.nama.toLowerCase() === teks.toLowerCase(); });
            if (cocok.length === 1) { pilihBarang(row, cocok[0]); return; }
            if (!e.defaultPrevented) e.preventDefault();
            inp.focus();
            renderSaran(row, cariBarang(teks));
            row.querySelector('[data-ac-empty]').classList.remove('hidden');
        });
    });

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
        var inp = row.querySelector('[data-ac-input]');
        if (inp) inp.focus();
    }

    list.addEventListener('click', function (e) {
        var h = e.target.closest('[data-hapus-row]');
        if (!h) return;
        if (list.querySelectorAll('[data-item]').length <= 1) return;
        h.closest('[data-item]').remove();
        renumber();
    });

    btnTambah.addEventListener('click', tambahBaris);

    // Baris yang dipulihkan server (old()/deep link ?barang_id=): sinkronkan stok & max jumlah
    list.querySelectorAll('[data-item]').forEach(function (row) {
        var id = row.querySelector('[data-barang-id]').value;
        if (!id) return;
        var b = barangData.find(function (x) { return String(x.id) === String(id); });
        if (!b) return;
        row.dataset.stok = b.stok;
        row.dataset.satuan = b.satuan;
        var inp = inputBarang(row);
        inp.value = b.nama;
        inp.dataset.picked = '1';
        syncJumlah(row);
    });

    // Filter nama personil sesuai jabatan tanpa reload halaman,
    // supaya baris barang yang sudah diisi tidak hilang saat ganti jabatan.
    var selJabatan = document.getElementById('select-jabatan');
    var selNama = document.getElementById('select-nama');
    var hintNama = document.getElementById('nama-tak-ada');
    function filterNama() {
        if (!selJabatan || !selNama) return;
        var jab = selJabatan.value, ada = false;
        Array.prototype.forEach.call(selNama.options, function (opt) {
            if (!opt.value) return;
            var tampil = opt.getAttribute('data-jabatan') === jab;
            opt.hidden = !tampil;
            if (tampil) ada = true;
        });
        if (selNama.value && selNama.selectedOptions[0] && selNama.selectedOptions[0].hidden) selNama.value = '';
        if (hintNama) hintNama.classList.toggle('hidden', ada);
    }
    if (selJabatan && selNama) {
        selJabatan.addEventListener('change', filterNama);
        filterNama();
    }

    renumber();
})();
</script>
@endpush
@endsection
