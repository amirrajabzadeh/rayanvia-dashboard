@props([
    'icon' => 'inbox',
    'title' => null,
    'text' => null,
    'compact' => false,
])

<div {{ $attributes->class(['flex flex-col items-center justify-center text-center', $compact ? 'px-4 py-8' : 'px-6 py-14']) }}>
    <span class="mb-3 flex size-12 items-center justify-center rounded-lg bg-gray-100 text-gray-400 dark:bg-white/5">
        <x-ui.icon :name="$icon" :size="6" />
    </span>
    <p class="text-theme-sm font-medium text-gray-700 dark:text-gray-200">{{ $title ?? __('ui.table.empty') }}</p>
    @if ($text)
        <p class="mt-1 max-w-sm text-theme-xs text-gray-500 dark:text-gray-400">{{ $text }}</p>
    @endif
    @if (isset($action))
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
