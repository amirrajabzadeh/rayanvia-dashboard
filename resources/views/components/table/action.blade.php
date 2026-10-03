@props([
    'icon',
    'tooltip' => null,
    'href' => null,
    'tone' => 'default', // default, danger
])

@php
    $classes = 'flex size-8 items-center justify-center rounded-md transition-colors '.match ($tone) {
        'danger' => 'text-gray-400 hover:bg-error-50 hover:text-error-600 dark:hover:bg-error-500/10',
        default => 'text-gray-400 hover:bg-gray-100 hover:text-brand-600 dark:hover:bg-white/8 dark:hover:text-brand-300',
    };
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @else type="button" @endif
    @if ($tooltip) data-tooltip="{{ $tooltip }}" aria-label="{{ $tooltip }}" @endif
    {{ $attributes->class($classes) }}>
    <x-ui.icon :name="$icon" :size="4" />
</{{ $tag }}>
