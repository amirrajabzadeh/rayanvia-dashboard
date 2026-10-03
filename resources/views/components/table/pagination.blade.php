@props([
    'paginator',
])

@if ($paginator->total() > 0)
    <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-theme-xs text-gray-500 dark:text-gray-400">
            {{ __('ui.table.showing', [
                'from' => number_format($paginator->firstItem()),
                'to' => number_format($paginator->lastItem()),
                'total' => number_format($paginator->total()),
            ]) }}
        </p>
        {{ $paginator->onEachSide(1)->links('pagination.rayan') }}
    </div>
@endif
