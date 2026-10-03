@props([
    'type' => 'gray', // خروجی color() مربوط به Enum وضعیت
])

<x-ui.badge :color="$type" dot {{ $attributes }}>{{ $slot }}</x-ui.badge>
