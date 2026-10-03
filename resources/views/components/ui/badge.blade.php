@props([
    'color' => 'gray', // brand, accent, success, error, warning, info, gray, teal, cyan, indigo, purple, pink, orange, lime, brown
    'variant' => 'light', // light, solid
    'size' => 'md', // sm, md
    'dot' => false,
    'icon' => null,
])

@php
    $palette = [
        'light' => [
            'brand' => 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-300',
            'success' => 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400',
            'error' => 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-400',
            'warning' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400',
            'info' => 'bg-info-50 text-info-700 dark:bg-info-500/15 dark:text-info-400',
            'accent' => 'bg-accent-50 text-accent-700 dark:bg-accent-500/15 dark:text-accent-400',
            'gray' => 'bg-gray-100 text-gray-700 dark:bg-white/8 dark:text-gray-300',
            'teal' => 'bg-teal-50 text-teal-700 dark:bg-teal-500/15 dark:text-teal-400',
            'cyan' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-400',
            'indigo' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400',
            'purple' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400',
            'pink' => 'bg-pink-50 text-pink-700 dark:bg-pink-500/15 dark:text-pink-400',
            'orange' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',
            'lime' => 'bg-lime-50 text-lime-700 dark:bg-lime-500/15 dark:text-lime-400',
            'brown' => 'bg-brown-50 text-brown-700 dark:bg-brown-500/15 dark:text-brown-400',
        ],
        'solid' => [
            'brand' => 'bg-brand-500 text-white',
            'success' => 'bg-success-600 text-white',
            'error' => 'bg-error-500 text-white',
            'warning' => 'bg-warning-500 text-white',
            'info' => 'bg-info-500 text-white',
            'accent' => 'bg-accent-500 text-gray-950',
            'gray' => 'bg-gray-600 text-white dark:bg-gray-500',
            'teal' => 'bg-teal-500 text-white',
            'cyan' => 'bg-cyan-500 text-white',
            'indigo' => 'bg-indigo-500 text-white',
            'purple' => 'bg-purple-500 text-white',
            'pink' => 'bg-pink-500 text-white',
            'orange' => 'bg-orange-500 text-white',
            'lime' => 'bg-lime-500 text-white',
            'brown' => 'bg-brown-500 text-white',
        ],
    ];

    $dots = [
        'brand' => 'bg-brand-500', 'success' => 'bg-success-500', 'error' => 'bg-error-500',
        'warning' => 'bg-warning-500', 'info' => 'bg-info-500', 'accent' => 'bg-accent-500', 'gray' => 'bg-gray-400',
        'teal' => 'bg-teal-500',
        'cyan' => 'bg-cyan-500',
        'indigo' => 'bg-indigo-500',
        'purple' => 'bg-purple-500',
        'pink' => 'bg-pink-500',
        'orange' => 'bg-orange-500',
        'lime' => 'bg-lime-500',
        'brown' => 'bg-brown-500',
    ];

    $sizes = ['sm' => 'px-1.5 py-0.5 text-[11px]', 'md' => 'px-2 py-0.5 text-theme-xs'];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 rounded-md font-medium whitespace-nowrap',
    $sizes[$size] ?? $sizes['md'],
    $palette[$variant][$color] ?? $palette['light']['gray'],
]) }}>
    @if ($dot)
        <span class="size-1.5 rounded-full {{ $dots[$color] ?? $dots['gray'] }}"></span>
    @endif
    @if ($icon)
        <x-ui.icon :name="$icon" :size="3.5" />
    @endif
    {{ $slot }}
</span>
