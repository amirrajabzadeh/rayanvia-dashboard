@props([
    'title',
    'time' => null,
    'tone' => 'brand', // brand, success, warning, error, gray
])

@php
    $dots = [
        'brand' => 'bg-brand-500', 'success' => 'bg-success-500', 'warning' => 'bg-warning-500',
        'error' => 'bg-error-500', 'gray' => 'bg-gray-400',
    ];
@endphp

<li class="relative">
    <span class="absolute -start-6 top-1 size-[15px] rounded-full border-[3px] border-white {{ $dots[$tone] ?? $dots['brand'] }} dark:border-gray-900"></span>
    <div class="flex items-start justify-between gap-3">
        <p class="text-theme-sm font-medium text-gray-800 dark:text-gray-100">{{ $title }}</p>
        @if ($time)
            <span class="num shrink-0 text-theme-xs text-gray-400">{{ $time }}</span>
        @endif
    </div>
    @if (trim($slot))
        <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">{{ $slot }}</p>
    @endif
</li>
