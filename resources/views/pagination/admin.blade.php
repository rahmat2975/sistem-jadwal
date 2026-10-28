@if ($paginator->hasPages())
    <nav class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between" aria-label="Navigasi halaman">
        <p class="text-xs text-[#8A94A6]">
            Menampilkan
            <span class="font-bold text-[#161A30]">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-bold text-[#161A30]">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-bold text-[#161A30]">{{ $paginator->total() }}</span>
            data
        </p>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-9 items-center gap-1 rounded-xl border border-[#E5E7EB] px-3 text-xs font-semibold text-[#C4C9D4] cursor-not-allowed">
                    <span aria-hidden="true">‹</span>
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-9 items-center gap-1 rounded-xl border border-[#E5E7EB] bg-white px-3 text-xs font-semibold text-[#464555] transition hover:border-[#5B50E5] hover:text-[#5B50E5]">
                    <span aria-hidden="true">‹</span>
                    Sebelumnya
                </a>
            @endif

            <div class="hidden items-center gap-1 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex h-9 min-w-9 items-center justify-center px-1 text-xs text-[#8A94A6]">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-[#5B50E5] px-2 text-xs font-bold text-white shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl px-2 text-xs font-semibold text-[#464555] transition hover:bg-[#EEF2FF] hover:text-[#5B50E5]">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <span class="inline-flex h-9 items-center rounded-xl bg-[#F4F5F9] px-3 text-xs font-bold text-[#5B50E5] sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-9 items-center gap-1 rounded-xl border border-[#E5E7EB] bg-white px-3 text-xs font-semibold text-[#464555] transition hover:border-[#5B50E5] hover:text-[#5B50E5]">
                    Berikutnya
                    <span aria-hidden="true">›</span>
                </a>
            @else
                <span class="inline-flex h-9 items-center gap-1 rounded-xl border border-[#E5E7EB] px-3 text-xs font-semibold text-[#C4C9D4] cursor-not-allowed">
                    Berikutnya
                    <span aria-hidden="true">›</span>
                </span>
            @endif
        </div>
    </nav>
@endif
