@props([
    'text',
    'placement' => 'top',
])

<span data-tooltip="{{ $text }}" data-tooltip-placement="{{ $placement }}" {{ $attributes->class('inline-flex') }}>
    {{ $slot }}
</span>
