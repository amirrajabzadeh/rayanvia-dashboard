@props([
    'name',
    'label',
    'checked' => false,
    'disabled' => false,
])

<label {{ $attributes->only('class')->class('group flex cursor-pointer items-center gap-3 select-none') }}>
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) @disabled($disabled) class="peer sr-only">
    {{-- دستگیره با جابه‌جایی منطقی (start) حرکت می‌کند، پس در RTL و LTR بدون واریانت جهت درست کار می‌کند --}}
    <span class="relative h-5 w-9 shrink-0 rounded-full bg-gray-300 transition peer-checked:bg-brand-500 peer-focus-visible:ring-3 peer-focus-visible:ring-brand-500/25 peer-disabled:opacity-50 dark:bg-gray-700">
        <span class="absolute start-0.5 top-0.5 size-4 rounded-full bg-white shadow-theme-xs transition-[inset-inline-start] duration-200 group-has-checked:start-[1.125rem]"></span>
    </span>
    <span class="text-theme-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
</label>
