@props([
    'title',
    'desc' => null,
    'collapsible' => false,
    'startOpen' => false,
])

<section
    @if ($collapsible)
        x-data="{ open: @js($startOpen) }"
        x-init="$watch('open', value => value && $nextTick(() => document.dispatchEvent(new CustomEvent('card-content:shown'))))"
    @endif
    {{ $attributes->class('card-surface min-w-0') }}>

    <header @class([
        'flex items-center justify-between gap-3 px-5 py-4',
        'cursor-pointer select-none' => $collapsible,
    ]) @if ($collapsible) @click="open = !open" @endif>
        <div class="min-w-0">
            <h3 class="text-[15px] font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h3>
            @if ($desc)
                <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">{{ $desc }}</p>
            @endif
        </div>

        @isset($headerActions)
            <div class="flex shrink-0 items-center gap-2" @click.stop>{{ $headerActions }}</div>
        @endisset

        @if ($collapsible)
            <span class="flex shrink-0 items-center gap-1 rounded-md px-2 py-1 text-theme-xs font-medium text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                <span x-text="open ? @js(__('ui.actions.hide_details')) : @js(__('ui.actions.show_details'))"></span>
                <x-ui.icon name="chevron-down" :size="4" class="transition-transform" ::class="open && 'rotate-180'" />
            </span>
        @endif
    </header>

    {{-- خلاصه: همیشه دیده می‌شود، حتی وقتی جزئیات بسته است --}}
    @isset($summary)
        <div class="border-t border-gray-100 px-5 py-3 dark:border-gray-800">{{ $summary }}</div>
    @endisset

    <div class="border-t border-gray-100 p-5 dark:border-gray-800"
         @if ($collapsible) x-show="open" x-cloak x-collapse @endif>
        {{ $slot }}
    </div>
</section>
