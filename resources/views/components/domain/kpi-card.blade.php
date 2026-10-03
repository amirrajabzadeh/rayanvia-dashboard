@props([
    'label',
    'value',
    'icon' => 'chart',
    'trend' => null, // درصد؛ مثبت = رشد
    'spark' => [], // نقاط نمودار کوچک
    'color' => '#034ea2',
    'hint' => null,
])

<div {{ $attributes->class('card-surface flex flex-col p-4') }}>
    <div class="flex items-center justify-between gap-2">
        <div class="flex min-w-0 items-center gap-2">
            <span class="flex size-8 shrink-0 items-center justify-center rounded-lg text-white" style="background: {{ $color }}">
                <x-ui.icon :name="$icon" :size="4" />
            </span>
            <p class="truncate text-theme-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</p>
        </div>

        @if ($trend !== null)
            <span @class([
                'inline-flex shrink-0 items-center gap-0.5 rounded-full px-2 py-0.5 text-theme-xs font-medium',
                'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400' => $trend >= 0,
                'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400' => $trend < 0,
            ])>
                <x-ui.icon :name="$trend >= 0 ? 'trending-up' : 'trending-down'" :size="3.5" />
                <span class="num">{{ \App\Support\Fa::digits(abs($trend)) }}٪</span>
            </span>
        @endif
    </div>

    <div class="mt-4 flex items-end justify-between gap-3">
        <div class="min-w-0">
            <p class="num truncate text-[26px] leading-8 font-bold tracking-tight text-gray-900 dark:text-white">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-theme-xs text-gray-400">{{ $hint }}</p>
            @endif
        </div>

        @if ($spark)
            <div dir="ltr" class="-mb-1 h-12 w-24 shrink-0" data-chart="{{ json_encode([
                'chart' => ['type' => 'area', 'height' => 48, 'sparkline' => ['enabled' => true]],
                'series' => [['name' => $label, 'data' => $spark]],
                'colors' => [$color],
                'stroke' => ['curve' => 'smooth', 'width' => 2],
                'fill' => ['type' => 'gradient', 'gradient' => ['opacityFrom' => 0.28, 'opacityTo' => 0]],
                'tooltip' => ['enabled' => false],
            ]) }}"></div>
        @endif
    </div>
</div>
