@props([
    'name',
    'label',
    'value',
    'checked' => false,
    'disabled' => false,
])

<label {{ $attributes->only('class')->class('flex cursor-pointer items-center gap-2.5 select-none') }}>
    <input type="radio" name="{{ $name }}" value="{{ $value }}"
           @checked((string) old($name, $checked ? $value : null) === (string) $value) @disabled($disabled)
           class="peer sr-only">
    <span class="flex size-[18px] shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white transition peer-checked:border-brand-500 peer-focus-visible:ring-3 peer-focus-visible:ring-brand-500/25 peer-disabled:opacity-50 dark:border-gray-600 dark:bg-gray-900 peer-checked:[&>span]:scale-100">
        <span class="size-2 scale-0 rounded-full bg-brand-500 transition"></span>
    </span>
    <span class="text-theme-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
</label>
