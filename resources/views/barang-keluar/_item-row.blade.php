@php
    // $idx = indeks angka, atau string '__INDEX__' saat dipakai di dalam <template>
    $sel = old('items.'.$idx.'.barang_id', $row['barang_id'] ?? null);
@endphp
<div class="item-row glass rounded-xl p-4" data-item>
    <div class="flex items-center justify-between gap-2 mb-3">
        <span class="text-[11px] font-semibold text-zinc-400 tracking-wider" data-no>BARANG</span>
        <button type="button" data-hapus-row title="Hapus barang ini dari daftar"
                class="shrink-0 p-2 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116 20H8a2 2 0 01-1.998-1.858L5.13 7H3m2-1a1 1 0 011-1h16a1 1 0 011 1m-3 4V5a1 1 0 00-1-1h-4a1 1 0 00-1 1v2"/></svg>
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
        <div class="sm:col-span-7">
            <label class="text-sm font-medium text-zinc-300">Barang *</label>
            <select name="items[{{ $idx }}][barang_id]" required
                    class="mt-2 w-full min-w-0 rounded-xl bg-[#08080f] border border-white/[0.08] px-3 py-3 text-sm text-white focus:outline-none">
                <option value="" class="bg-[#08080f]">Pilih barang...</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->id }}" class="bg-[#08080f]" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}" {{ (string)$sel === (string)$b->id ? 'selected' : '' }}>{{ $b->nama }} — {{ $b->kategori }} (sisa {{ $b->stok }} {{ $b->satuan }})</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-5">
            <label class="text-sm font-medium text-zinc-300">Jumlah *</label>
            <input type="number" name="items[{{ $idx }}][jumlah]" value="{{ old('items.'.$idx.'.jumlah', $row['jumlah'] ?? 1) }}" required min="1" placeholder="1"
                   class="mt-2 w-full min-w-0 rounded-xl bg-[#08080f] border border-white/[0.08] px-3 py-3 text-sm text-white focus:outline-none" data-jumlah>
        </div>
        <div class="sm:col-span-12">
            <label class="text-sm font-medium text-zinc-300">Serial Number</label>
            <input type="text" name="items[{{ $idx }}][serial_number]" value="{{ old('items.'.$idx.'.serial_number', $row['serial_number'] ?? '') }}" placeholder="opsional, untuk router/ONT"
                   class="mt-2 w-full min-w-0 rounded-xl bg-[#08080f] border border-white/[0.08] px-3 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
        </div>
        <div class="sm:col-span-12">
            <label class="text-sm font-medium text-zinc-300">Keperluan</label>
            <input type="text" name="items[{{ $idx }}][keperluan]" value="{{ old('items.'.$idx.'.keperluan', $row['keperluan'] ?? '') }}" placeholder="Instalasi pelanggan Jl. Mawar No.10, dll..."
                   class="mt-2 w-full min-w-0 rounded-xl bg-[#08080f] border border-white/[0.08] px-3 py-3 text-sm text-white placeholder:text-zinc-600 focus:outline-none">
        </div>
        <div class="sm:col-span-12 -mt-1">
            <div class="text-xs text-zinc-500" data-info>Sisa stok: <span data-info-stok>-</span> <span data-info-satuan></span></div>
        </div>
    </div>
</div>
