@props([
    'shell' => false, // true روی قاب تیره (هدر)
])

<button type="button" @click="$store.theme.toggle()"
        {{ $attributes->class($shell
            ? 'shell-btn'
            : 'relative flex size-9 items-center justify-center rounded-md text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/8 dark:hover:text-white') }}
        aria-label="{{ __('ui.labels.toggle_theme') }}">
    <x-ui.icon name="moon" :size="5" class="dark:hidden" />
    <x-ui.icon name="sun" :size="5" class="hidden dark:block" />
</button>
