@props([
    'variant' => 'info', // success, error, warning, info
    'title' => null,
    'dismissible' => false,
])

@php
    $tones = [
        'success' => ['box' => 'border-success-200 bg-success-50 dark:border-success-500/25 dark:bg-success-500/10', 'text' => 'text-success-700 dark:text-success-400', 'icon' => 'check-circle'],
        'error' => ['box' => 'border-error-200 bg-error-50 dark:border-error-500/25 dark:bg-error-500/10', 'text' => 'text-error-700 dark:text-error-400', 'icon' => 'x-circle'],
        'warning' => ['box' => 'border-warning-200 bg-warning-50 dark:border-warning-500/25 dark:bg-warning-500/10', 'text' => 'text-warning-700 dark:text-warning-400', 'icon' => 'alert-triangle'],
        'info' => ['box' => 'border-info-200 bg-info-50 dark:border-info-500/25 dark:bg-info-500/10', 'text' => 'text-info-700 dark:text-info-400', 'icon' => 'info'],
    ];
    $tone = $tones[$variant] ?? $tones['info'];
@endphp

<div x-data="{ show: true }" x-show="show" role="alert"
     {{ $attributes->class(['flex items-start gap-3 rounded-md border px-4 py-3', $tone['box']]) }}>
    <x-ui.icon :name="$tone['icon']" :size="5" class="mt-0.5 {{ $tone['text'] }}" />
    <div class="min-w-0 flex-1 text-theme-sm">
        @if ($title)
            <p class="font-semibold {{ $tone['text'] }}">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-0.5' : '' }} text-gray-700 dark:text-gray-300">{{ $slot }}</div>
    </div>
    @if ($dismissible)
        <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600" aria-label="{{ __('ui.actions.close') }}">
            <x-ui.icon name="x" :size="4" />
        </button>
    @endif
</div>
