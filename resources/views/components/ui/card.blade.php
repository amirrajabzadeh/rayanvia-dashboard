@props([
    'padding' => true,
])

<div {{ $attributes->class(['card-surface', 'p-5' => $padding]) }}>
    {{ $slot }}
</div>
