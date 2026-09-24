@extends('layouts.app')
@section('title', 'Kelola Users - Gudang AFNALINK')
@section('page-title', 'Kelola Users')
@section('page-subtitle', 'Hanya admin • Kelola jabatan & role')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="text-sm text-zinc-500">{{ $users->total() }} users</div>
    <a href="{{ route('users.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-sm font-medium">+ Tambah User</a>
</div>
<div class="glass rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead><tr class="border-b border-white/[0.06] bg-white/[0.02]"><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">USER</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">EMAIL</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">JABATAN</th><th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400">ROLE</th><th class="text-right px-5 py-3 text-xs font-semibold text-zinc-400">AKSI</th></tr></thead>
            <tbody class="divide-y divide-white/[0.04]">
                @foreach($users as $u)
                <tr class="hover:bg-white/[0.03]">
                    <td class="px-5 py-4"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($u->name,0,1)) }}{{ strtoupper(substr(explode(' ',$u->name)[1]??'',0,1)) }}</div><div class="text-sm font-medium text-white">{{ $u->name }}</div></div></td>
                    <td class="px-5 py-4 text-sm text-zinc-400">{{ $u->email }}</td>
                    <td class="px-5 py-4"><span class="text-xs px-2.5 py-1 rounded-full bg-white/[0.06] border border-white/[0.06] text-zinc-300">{{ $u->jabatan }}</span></td>
                    <td class="px-5 py-4"><span class="text-xs px-2.5 py-1 rounded-full {{ $u->role=='admin' ? 'bg-amber-500/10 border-amber-500/20 text-amber-300' : 'bg-cyan-500/10 border-cyan-500/20 text-cyan-300' }}">{{ ucfirst($u->role) }}</span></td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('users.edit', $u) }}" class="p-2 rounded-lg hover:bg-white/[0.06] text-zinc-400 hover:text-white">✏️</a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus {{ $u->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-zinc-400 hover:text-red-400">🗑️</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="px-5 py-4 border-t border-white/[0.06]">{{ $users->links() }}</div>@endif
</div>
@endsection
