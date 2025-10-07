@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-2 text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1.5 text-slate-400 select-none">&lt; Back</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-2 py-1.5 text-blue-700 hover:underline">&lt; Back</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 py-1.5 text-slate-500 select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded border border-blue-700 bg-blue-600 text-white font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-7 h-7 rounded border border-slate-300 hover:border-blue-600 hover:text-blue-700">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-2 py-1.5 text-blue-700 hover:underline">Next &gt;</a>
        @else
            <span class="px-2 py-1.5 text-slate-400 select-none">Next &gt;</span>
        @endif
    </nav>
@endif
