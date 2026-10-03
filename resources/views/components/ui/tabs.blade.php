@props([
    'tabs' => [], // ['key' => 'برچسب']
    'active' => null,
])

@php($first = $active ?? array_key_first($tabs))

{{-- پنل‌ها داخل اسلات با x-show="tab === 'key'" نوشته می‌شوند --}}
<div x-data="{ tab: @js($first) }" {{ $attributes }}>
    {{-- خط زیر تب‌ها با سایه داخلی است، نه border و -mb-px؛ آن یک پیکسل بیرون‌زده اسکرول عمودی می‌ساخت --}}
    <div class="no-scrollbar flex gap-1 overflow-x-auto overflow-y-hidden shadow-[inset_0_-1px_0_var(--color-gray-200)] dark:shadow-[inset_0_-1px_0_var(--color-gray-800)]" role="tablist">
        @foreach ($tabs as $key => $label)
            <button type="button" role="tab" @click="tab = @js($key)"
                    :aria-selected="tab === @js($key)"
                    :class="tab === @js($key)
                        ? 'border-brand-500 text-brand-600 dark:text-brand-300'
                        : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                    class="shrink-0 whitespace-nowrap border-b-2 px-4 py-2.5 text-theme-sm font-medium transition-colors">
                {{ $label }}
            </button>
        @endforeach
    </div>
    <div class="pt-4">
        {{ $slot }}
    </div>
</div>
