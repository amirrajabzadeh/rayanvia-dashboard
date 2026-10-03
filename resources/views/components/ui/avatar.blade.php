@props([
    'name' => '',
    'src' => null,
    'size' => 'md', // sm, md, lg
    'tone' => 'gray', // gray, brand, accent
])

@php
    $sizes = ['sm' => 'size-8 text-theme-xs', 'md' => 'size-10 text-theme-sm', 'lg' => 'size-14 text-lg'];
    $tones = [
        'gray' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300',
        'brand' => 'bg-brand-500 text-white',
        'accent' => 'bg-accent-100 text-accent-700 dark:bg-accent-500/20 dark:text-accent-400',
    ];

    $words = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = collect($words)->take(2)->map(fn ($word) => mb_substr($word, 0, 1))->implode('‌');
@endphp

<span {{ $attributes->class(['inline-flex shrink-0 items-center justify-center overflow-hidden rounded-md font-semibold', $sizes[$size] ?? $sizes['md'], $tones[$tone] ?? $tones['gray']]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="size-full object-cover">
    @else
        {{ $initials }}
    @endif
</span>
