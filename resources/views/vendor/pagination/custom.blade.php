@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-slate-200">

        {{-- Showing Results Text --}}
        <p class="text-sm text-slate-500">
            Showing
            <span class="font-medium text-slate-900">{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</span>
            of
            <span class="font-medium text-slate-900">{{ $paginator->total() }}</span> products
        </p>

        <div class="flex items-center gap-2">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 opacity-50 cursor-not-allowed">
                    <i class="ti ti-chevron-left text-lg"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors">
                    <i class="ti ti-chevron-left text-lg"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)

                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="text-slate-400 px-1">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            {{-- Active Page --}}
                            <span class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-lg font-semibold shadow-sm transition-colors cursor-default">
                                {{ $page }}
                            </span>
                        @else
                            {{-- Inactive Page --}}
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors font-medium">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors">
                    <i class="ti ti-chevron-right text-lg"></i>
                </a>
            @else
                <span class="w-10 h-10 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 opacity-50 cursor-not-allowed">
                    <i class="ti ti-chevron-right text-lg"></i>
                </span>
            @endif

        </div>
    </div>
@endif
