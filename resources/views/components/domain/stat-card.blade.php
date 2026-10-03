@props([
    'label',
    'value',
    'icon' => 'chart',
    'tone' => 'brand', // brand, accent, success, warning, error, info
    'trend' => null, // عدد درصد؛ مثبت = رشد
    'hint' => null,
    'href' => null,
])

@php
    $tones = [
        'brand' => 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300',
        'accent' => 'bg-accent-50 text-accent-600 dark:bg-accent-500/15 dark:text-accent-400',
        'success' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
        'warning' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
        'error' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
        'info' => 'bg-info-50 text-info-600 dark:bg-info-500/15 dark:text-info-400',
    ];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif
    {{ $attributes->class(['card-surface block p-4', 'transition hover:border-brand-300 hover:shadow-theme-sm dark:hover:border-brand-500/40' => $href]) }}>
    <div class="flex items-center gap-2">
        <span class="flex size-7 shrink-0 items-center justify-center rounded-md {{ $tones[$tone] ?? $tones['brand'] }}">
            <x-ui.icon :name="$icon" :size="4" />
        </span>
        <p class="truncate text-theme-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</p>
    </div>
    <p class="num mt-3 truncate text-[28px] leading-9 font-bold tracking-tight text-gray-900 dark:text-white">{{ $value }}</p>

    @if ($trend !== null || $hint)
        <div class="mt-3 flex items-center gap-2 text-theme-xs">
            @if ($trend !== null)
                <span @class([
                    'inline-flex items-center gap-0.5 font-medium',
                    'text-success-600 dark:text-success-400' => $trend >= 0,
                    'text-error-600 dark:text-error-400' => $trend < 0,
                ])>
                    <x-ui.icon :name="$trend >= 0 ? 'trending-up' : 'trending-down'" :size="3.5" />
                    <span class="num">{{ abs($trend) }}٪</span>
                </span>
            @endif
            @if ($hint)
                <span class="text-gray-400">{{ $hint }}</span>
            @endif
        </div>
    @endif
</{{ $tag }}>
