@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Info --}}
        <div class="text-sm text-zinc-500 order-2 sm:order-1">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-semibold text-zinc-300">{{ $paginator->firstItem() }}</span>
                –
                <span class="font-semibold text-zinc-300">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            dari
            <span class="font-semibold text-zinc-300">{{ $paginator->total() }}</span>
            data
        </div>

        {{-- Mobile: Prev/Next only --}}
        <div class="flex items-center gap-3 sm:hidden order-1 sm:order-2 w-full justify-between">
            @if ($paginator->onFirstPage())
                <span class="flex-1 flex items-center justify-center gap-2 px-5 py-3 text-[15px] font-medium rounded-xl bg-white/[0.03] border border-white/[0.06] text-zinc-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex-1 flex items-center justify-center gap-2 px-5 py-3 text-[15px] font-medium rounded-xl glass text-zinc-300 hover:text-white hover:bg-white/[0.06] border border-white/[0.06] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex-1 flex items-center justify-center gap-2 px-5 py-3 text-[15px] font-medium rounded-xl bg-gradient-to-r from-cyan-500/20 to-indigo-600/20 border border-cyan-500/20 text-cyan-300 hover:from-cyan-500/30 hover:to-indigo-600/30 transition">
                    Selanjutnya
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="flex-1 flex items-center justify-center gap-2 px-5 py-3 text-[15px] font-medium rounded-xl bg-white/[0.03] border border-white/[0.06] text-zinc-600 cursor-not-allowed">
                    Selanjutnya
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>

        {{-- Desktop: Full pagination --}}
        <div class="hidden sm:flex items-center gap-2 order-2">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="w-11 h-11 flex items-center justify-center rounded-full bg-white/[0.03] border border-white/[0.06] text-zinc-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="w-11 h-11 flex items-center justify-center rounded-full glass border border-white/[0.06] text-zinc-400 hover:text-white hover:bg-white/[0.06] transition" aria-label="Previous">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="w-11 h-11 flex items-center justify-center rounded-full bg-white/[0.03] border border-white/[0.06] text-zinc-500 text-[15px]">…</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-11 h-11 flex items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-indigo-600 text-white text-[15px] font-bold shadow-lg shadow-cyan-500/20 border border-cyan-500/20">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-11 h-11 flex items-center justify-center rounded-full glass border border-white/[0.06] text-zinc-400 hover:text-white hover:bg-white/[0.08] hover:border-white/[0.10] text-[15px] font-medium transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="w-11 h-11 flex items-center justify-center rounded-full glass border border-white/[0.06] text-zinc-400 hover:text-white hover:bg-white/[0.06] transition" aria-label="Next">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="w-11 h-11 flex items-center justify-center rounded-full bg-white/[0.03] border border-white/[0.06] text-zinc-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
