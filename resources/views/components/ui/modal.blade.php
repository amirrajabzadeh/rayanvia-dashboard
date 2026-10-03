@props([
    'name',
    'title' => null,
    'size' => 'md', // sm, md, lg, xl
    'open' => false,
])

@php
    $widths = ['sm' => 'max-w-sm', 'md' => 'max-w-lg', 'lg' => 'max-w-2xl', 'xl' => 'max-w-4xl'];
@endphp

{{-- باز کردن: $dispatch('open-modal', 'name') یا window.dispatchEvent(new CustomEvent('open-modal', { detail: 'name' })) --}}
<div x-data="{ show: @js($open) }"
     x-on:open-modal.window="if ($event.detail === @js($name)) show = true"
     x-on:close-modal.window="if ($event.detail === @js($name)) show = false"
     x-on:keydown.escape.window="show = false"
     x-effect="document.body.style.overflow = show ? 'hidden' : ''"
     x-show="show" x-cloak
     class="fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-4"
     role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-950/50 backdrop-blur-[3px]" @click="show = false"
         x-show="show" x-transition.opacity></div>

    <div class="card-surface relative w-full {{ $widths[$size] ?? $widths['md'] }} shadow-theme-xl"
         x-show="show" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="translate-y-2 scale-[.98] opacity-0"
         x-transition:enter-end="translate-y-0 scale-100 opacity-100">
        <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h3>
            <button type="button" @click="show = false"
                    class="flex size-8 items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/5"
                    aria-label="{{ __('ui.actions.close') }}">
                <x-ui.icon name="x" :size="4" />
            </button>
        </div>

        <div class="px-5 py-4">{{ $slot }}</div>

        @isset($footer)
            <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-5 py-3 dark:border-gray-800">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
