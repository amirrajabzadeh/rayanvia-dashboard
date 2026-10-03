@props([
    'href' => null,
    'type' => 'button', // button, submit, reset
    'variant' => 'primary', // primary, secondary, outline, ghost, danger, success, warning, accent
    'size' => 'md', // xs, sm, md, lg
    'icon' => null,
    'iconPosition' => 'start', // start, end
    'iconOnly' => false,
    'fullWidth' => false,
    'disabled' => false,
    'loading' => false,
])

@php
    $base = 'relative inline-flex items-center justify-center font-medium whitespace-nowrap transition duration-150 focus:outline-hidden focus-visible:ring-3 disabled:cursor-not-allowed disabled:opacity-50 aria-disabled:pointer-events-none aria-disabled:opacity-50 data-[loading=true]:pointer-events-none data-[loading=true]:opacity-70';

    $sizes = [
        'xs' => ['box' => 'h-7 gap-1 rounded-md text-theme-xs', 'pad' => 'px-2.5', 'square' => 'size-7', 'icon' => 3.5],
        'sm' => ['box' => 'h-8 gap-1.5 rounded-md text-theme-xs', 'pad' => 'px-3', 'square' => 'size-8', 'icon' => 4],
        'md' => ['box' => 'h-10 gap-2 rounded-md text-theme-sm', 'pad' => 'px-4', 'square' => 'size-10', 'icon' => 4],
        'lg' => ['box' => 'h-11 gap-2 rounded-md text-base', 'pad' => 'px-5', 'square' => 'size-11', 'icon' => 5],
    ];

    $variants = [
        'primary' => 'bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 focus-visible:ring-brand-500/30',
        'secondary' => 'bg-gray-100 text-gray-800 hover:bg-gray-200 focus-visible:ring-gray-400/30 dark:bg-white/8 dark:text-gray-100 dark:hover:bg-white/12',
        'outline' => 'border border-gray-300 bg-white text-gray-700 shadow-theme-xs hover:bg-gray-50 focus-visible:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-white/5',
        'ghost' => 'text-gray-600 hover:bg-gray-100 focus-visible:ring-gray-400/30 dark:text-gray-300 dark:hover:bg-white/8',
        'danger' => 'bg-error-500 text-white shadow-theme-xs hover:bg-error-600 focus-visible:ring-error-500/30',
        'success' => 'bg-success-600 text-white shadow-theme-xs hover:bg-success-700 focus-visible:ring-success-500/30',
        'warning' => 'bg-warning-500 text-white shadow-theme-xs hover:bg-warning-600 focus-visible:ring-warning-500/30',
        'accent' => 'bg-accent-500 text-gray-950 shadow-theme-xs hover:bg-accent-400 focus-visible:ring-accent-500/30',
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $classes = implode(' ', [
        $base,
        $sizeConfig['box'],
        $iconOnly ? $sizeConfig['square'] : $sizeConfig['pad'],
        $variants[$variant] ?? $variants['primary'],
        $fullWidth ? 'w-full' : '',
    ]);

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    @if ($disabled) @if ($href) aria-disabled="true" @else disabled @endif @endif
    @if ($loading) data-loading="true" @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    @if ($loading)
        <svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/>
            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
        </svg>
    @elseif ($icon && $iconPosition === 'start')
        <x-ui.icon :name="$icon" :size="$sizeConfig['icon']" />
    @endif

    @unless ($iconOnly)
        {{ $slot }}
    @endunless

    @if ($icon && $iconPosition === 'end' && ! $loading)
        <x-ui.icon :name="$icon" :size="$sizeConfig['icon']" />
    @endif
</{{ $tag }}>
