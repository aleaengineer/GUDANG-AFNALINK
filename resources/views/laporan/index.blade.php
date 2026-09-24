@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/themes/dark.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/style.css">
<style>
    .flatpickr-calendar { background: #0d0d14; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.4); }
    .flatpickr-months, .flatpickr-weekdays { background: transparent; }
    .flatpickr-month, .flatpickr-weekday, .flatpickr-day { color: #e4e4e7; }
    .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover { background: rgba(255,255,255,0.06); border-color: transparent; }
    .flatpickr-day.selected, .flatpickr-day.selected:hover { background: linear-gradient(135deg, #22d3ee, #6366f1); border-color: transparent; }
    .flatpickr-day.today { border-color: rgba(34,211,238,0.3); }
    .numInputWrapper:hover, .flatpickr-current-month { color: #e4e4e7; }
    .flatpickr-prev-month:hover, .flatpickr-next-month:hover { color: #22d3ee; }
    .flatpickr-monthSelect-month { color: #e4e4e7; }
    .flatpickr-monthSelect-month:hover { background: rgba(255,255,255,0.06); }
    .flatpickr-monthSelect-month.selected { background: linear-gradient(135deg, #22d3ee, #6366f1) !important; }
    .light .flatpickr-calendar { background: #ffffff; border-color: #e2e8f0; }
    .light .flatpickr-month, .light .flatpickr-weekday, .light .flatpickr-day, .light .flatpickr-monthSelect-month { color: #1e293b; }
    /* Fix icon symmetry: flatpickr altInput should match original input */
    .flatpickr-input { width: 160px !important; border-radius: 12px !important; background: #08080f !important; border: 1px solid rgba(255,255,255,0.08) !important; padding: 10px 16px 10px 44px !important; font-size: 14px !important; color: #fff !important; text-align: center !important; }
    .flatpickr-input::placeholder { text-align: center !important; }
    .flatpickr-input::placeholder { color: #71717a !important; }
    .flatpickr-input:focus { border-color: rgba(34,211,238,0.5) !important; outline: none !important; }
    .light .flatpickr-input { background: #ffffff !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
</style>
@endpush

@extends('layouts.app')
@section('title', 'Laporan - Gudang AFNALINK')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Riwayat & audit log')
@section('content')
<div class="glass rounded-2xl p-5 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="relative">
            <label class="text-xs font-medium text-zinc-400">Per Bulan</label>
            <div class="relative mt-1">
                <input type="text" id="bulan-picker" name="bulan" value="{{ request('bulan') }}" placeholder="Pilih Bulan" readonly class="w-[160px] rounded-xl bg-[#08080f] border border-white/[0.08] pl-11 pr-4 py-2.5 text-sm text-white placeholder:text-zinc-500 text-center focus:border-cyan-500/50 focus:outline-none cursor-pointer">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="relative">
            <label class="text-xs font-medium text-zinc-400">Dari Tanggal</label>
            <div class="relative mt-1">
                <input type="text" id="from-picker" name="from" value="{{ request('from') }}" placeholder="Pilih Tanggal" readonly class="w-[160px] rounded-xl bg-[#08080f] border border-white/[0.08] pl-11 pr-4 py-2.5 text-sm text-white placeholder:text-zinc-500 text-center focus:border-cyan-500/50 focus:outline-none cursor-pointer">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="relative">
            <label class="text-xs font-medium text-zinc-400">Sampai Tanggal</label>
            <div class="relative mt-1">
                <input type="text" id="to-picker" name="to" value="{{ request('to') }}" placeholder="Pilih Tanggal" readonly class="w-[160px] rounded-xl bg-[#08080f] border border-white/[0.08] pl-11 pr-4 py-2.5 text-sm text-white placeholder:text-zinc-500 text-center focus:border-cyan-500/50 focus:outline-none cursor-pointer">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="flex gap-2 ml-auto self-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-white/[0.06] border border-white/[0.08] text-sm text-white hover:bg-white/[0.08] h-[42px]">Filter</button>
            <a href="{{ route('laporan.index') }}" class="px-6 py-2.5 rounded-xl glass text-sm text-white hover:bg-white/[0.06] h-[42px] flex items-center">Reset</a>
            <a href="{{ route('laporan.export', request()->only(['from','to','bulan'])) }}" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-medium h-[42px] flex items-center">📥 Export</a>
        </div>
    </form>
    @if(request('bulan'))
        <div class="mt-3 text-xs text-cyan-300">Filter aktif: Bulan {{ \Carbon\Carbon::parse(request('bulan'))->translatedFormat('F Y') }}</div>
    @endif
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="glass rounded-2xl p-5">
        <h3 class="font-semibold text-white mb-4">Ringkasan Periode</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 p-4">
                <div class="text-2xl font-bold text-emerald-400">{{ $masuks->count() }}</div>
                <div class="text-xs text-zinc-500">Barang Masuk</div>
                <div class="text-xs text-zinc-600">Total {{ $masuks->sum('jumlah') }} unit</div>
            </div>
            <div class="rounded-xl bg-red-500/10 border border-red-500/20 p-4">
                <div class="text-2xl font-bold text-red-400">{{ $keluars->count() }}</div>
                <div class="text-xs text-zinc-500">Pengambilan</div>
                <div class="text-xs text-zinc-600">Total {{ $keluars->sum('jumlah') }} unit</div>
            </div>
        </div>
    </div>
    <div class="glass rounded-2xl p-5">
        <h3 class="font-semibold text-white mb-4">Audit Log Terbaru</h3>
        <div class="space-y-2 max-h-[200px] overflow-auto">
            @forelse($logs->take(8) as $log)
            <div class="flex gap-3 text-xs border-b border-white/[0.04] pb-2">
                <span class="text-zinc-500 whitespace-nowrap">{{ $log->created_at->format('d/m H:i') }}</span>
                <span class="px-2 py-0.5 rounded-full bg-white/[0.06] text-zinc-300 text-[10px]">{{ $log->aksi }}</span>
                <span class="text-zinc-400 flex-1 truncate">{{ $log->deskripsi }}</span>
                <span class="text-zinc-500">{{ $log->user->name ?? '-' }}</span>
            </div>
            @empty
            <div class="text-center py-8 text-zinc-500 text-sm">Belum ada log</div>
            @endforelse
        </div>
    </div>
</div>

<div class="glass rounded-2xl overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
        <h3 class="font-semibold text-white">Riwayat Barang Keluar Masuk</h3>
        <span class="text-xs text-zinc-500">{{ $masuks->count() + $keluars->count() }} transaksi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="border-b border-white/[0.06] bg-white/[0.02]"><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">TANGGAL</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">BARANG</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">JUMLAH</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">TIPE</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">DIAMBIL OLEH / SUMBER</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">OPERATOR</th></tr></thead>
            <tbody class="divide-y divide-white/[0.04]">
                @php
                    $riwayat = collect()->merge($masuks->map(fn($m) => ['waktu'=>$m->created_at,'tipe'=>'MASUK','barang'=>$m->barang,'jumlah'=>$m->jumlah,'satuan'=>$m->barang->satuan ?? 'pcs','sumber'=>$m->sumber,'teknisi'=>null,'jabatan'=>null,'operator'=>$m->operator]))->merge($keluars->map(fn($k) => ['waktu'=>$k->created_at,'tipe'=>'KELUAR','barang'=>$k->barang,'jumlah'=>$k->jumlah,'satuan'=>$k->barang->satuan ?? 'pcs','sumber'=>null,'teknisi'=>$k->teknisi_nama,'jabatan'=>$k->teknisi_jabatan,'operator'=>$k->operator]))->sortByDesc('waktu');
                @endphp
                @forelse($riwayat as $r)
                <tr class="hover:bg-white/[0.03]">
                    <td class="px-5 py-3 text-sm text-zinc-400 whitespace-nowrap">{{ $r['waktu']->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3"><div class="text-sm font-medium text-white">{{ $r['barang']->nama ?? '-' }}</div><div class="text-xs text-zinc-500">{{ $r['barang']->kategori ?? '-' }}</div></td>
                    <td class="px-5 py-3"><span class="text-sm font-bold {{ $r['tipe']=='MASUK' ? 'text-emerald-400' : 'text-red-400' }}">{{ $r['tipe']=='MASUK' ? '+' : '-' }}{{ $r['jumlah'] }} {{ $r['satuan'] }}</span></td>
                    <td class="px-5 py-3"><span class="text-xs px-2.5 py-1 rounded-full border {{ $r['tipe']=='MASUK' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' : 'bg-red-500/10 border-red-500/20 text-red-300' }}">{{ $r['tipe'] }}</span></td>
                    <td class="px-5 py-3">
                        @if($r['tipe']=='KELUAR')
                            <div class="text-sm font-medium text-white">{{ $r['teknisi'] }}</div><div class="text-xs"><span class="px-1.5 py-0.5 rounded bg-white/[0.06] border border-white/[0.06] text-zinc-400 text-[11px]">{{ $r['jabatan'] }}</span></div>
                        @else
                            <div class="text-sm text-zinc-300">{{ $r['sumber'] ?? '-' }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-sm text-zinc-400">{{ $r['operator']->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-16 text-center text-zinc-500">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 border-b border-white/[0.06]">
        <h3 class="font-semibold text-white">Audit Log Lengkap</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="border-b border-white/[0.06] bg-white/[0.02]"><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">WAKTU</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">USER</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">AKSI</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">DESKRIPSI</th></tr></thead>
            <tbody class="divide-y divide-white/[0.04]">
                @forelse($logs as $log)
                <tr class="hover:bg-white/[0.03]">
                    <td class="px-5 py-3 text-sm text-zinc-400 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-5 py-3 text-sm text-white">{{ $log->user->name ?? '-' }}</td>
                    <td class="px-5 py-3"><span class="text-xs px-2 py-1 rounded-full bg-white/[0.06] border border-white/[0.06] text-zinc-300">{{ $log->aksi }}</span></td>
                    <td class="px-5 py-3 text-sm text-zinc-400">{{ $log->deskripsi }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-16 text-center text-zinc-500">Belum ada log</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())<div class="px-5 py-4 border-t border-white/[0.06]">{{ $logs->links() }}</div>@endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/index.js"></script>
<script>
    flatpickr("#bulan-picker", {
        plugins: [new monthSelectPlugin({shorthand: true, dateFormat: "Y-m", altFormat: "F Y"})],
        altInput: true,
        altFormat: "F Y",
        dateFormat: "Y-m",
        locale: "id",
        allowInput: false,
    });
    flatpickr("#from-picker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        locale: "id",
        allowInput: true,
        altInputClass: ""
    });
    flatpickr("#to-picker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        locale: "id",
        allowInput: true,
        altInputClass: ""
    });
</script>
@endpush
@endsection
