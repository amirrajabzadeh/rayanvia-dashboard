@props([
    'name',
    'label',
    'value' => '1',
    'checked' => false,
    'disabled' => false,
    'hint' => null,
])

<label {{ $attributes->only('class')->class('flex cursor-pointer items-start gap-2.5 select-none') }}>
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}"
           @checked(old($name, $checked)) @disabled($disabled)
           class="peer sr-only">
    <span class="mt-0.5 flex size-[18px] shrink-0 items-center justify-center rounded border border-gray-300 bg-white text-white transition peer-checked:border-brand-500 peer-checked:bg-brand-500 peer-focus-visible:ring-3 peer-focus-visible:ring-brand-500/25 peer-disabled:opacity-50 dark:border-gray-600 dark:bg-gray-900 [&>svg]:opacity-0 peer-checked:[&>svg]:opacity-100">
        <x-ui.icon name="check" :size="3.5" :stroke="3" />
    </span>
    <span class="text-theme-sm text-gray-700 dark:text-gray-300">
        {{ $label }}
        @if ($hint)
            <span class="block text-theme-xs text-gray-500">{{ $hint }}</span>
        @endif
    </span>
</label>
