@if ($paginator->hasPages())
    @php
        $item = 'inline-flex h-8 min-w-8 items-center justify-center rounded-md px-2 text-theme-xs font-medium transition-colors';
    @endphp
    <nav role="navigation" class="flex items-center gap-1" aria-label="pagination">
        @if ($paginator->onFirstPage())
            <span class="{{ $item }} cursor-not-allowed text-gray-300 dark:text-gray-600"><x-ui.icon name="chevron-right" :size="4" /></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('ui.table.prev') }}"
               class="{{ $item }} text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/8"><x-ui.icon name="chevron-right" :size="4" /></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="{{ $item }} text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="{{ $item }} num bg-brand-500 text-white shadow-theme-xs">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="{{ $item }} num text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/8">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('ui.table.next') }}"
               class="{{ $item }} text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/8"><x-ui.icon name="chevron-left" :size="4" /></a>
        @else
            <span class="{{ $item }} cursor-not-allowed text-gray-300 dark:text-gray-600"><x-ui.icon name="chevron-left" :size="4" /></span>
        @endif
    </nav>
@endif
