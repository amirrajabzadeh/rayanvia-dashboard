@props([
    'name',
    'label' => null,
    'options' => [], // ['value' => 'برچسب']
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'error' => null,
    'hint' => null,
])

{{-- انتخابگر جستجوپذیر مبتنی بر Alpine (جایگزین select2) --}}
<x-form.field :name="$name" :label="$label" :required="$required" :hint="$hint" :error="$error">
    <div x-data="combobox({ options: @js($options), value: @js((string) old($name, $value)) })"
         @click.outside="open = false" @keydown.escape="open = false" class="relative">
        <input type="hidden" name="{{ $name }}" :value="value">

        <button type="button" @click="open = !open; $nextTick(() => open && $refs.search.focus())"
                class="form-control flex items-center justify-between gap-2 text-start"
                :aria-expanded="open">
            <span class="truncate" :class="label ? '' : 'text-gray-400'"
                  x-text="label || @js($placeholder ?? __('ui.labels.select_placeholder'))"></span>
            <x-ui.icon name="chevron-down" :size="4" class="shrink-0 text-gray-400 transition-transform duration-200" ::class="open && 'rotate-180'" />
        </button>

        <div x-show="open" x-cloak x-transition.origin.top
             class="card-surface absolute z-99 mt-1 w-full overflow-hidden shadow-theme-lg">
            <div class="border-b border-gray-100 p-2 dark:border-gray-800">
                <input x-ref="search" x-model="query" type="text" data-auto-dir placeholder="{{ __('ui.labels.combobox_search') }}"
                       class="form-control h-8 text-theme-xs">
            </div>
            <ul class="custom-scrollbar max-h-56 overflow-y-auto p-1">
                <template x-for="[key, text] in filtered" :key="key">
                    <li>
                        <button type="button" @click="choose(key)"
                                class="flex w-full items-center justify-between rounded px-3 py-2 text-start text-theme-sm hover:bg-gray-50 dark:hover:bg-white/5"
                                :class="key == value ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-300' : 'text-gray-700 dark:text-gray-200'">
                            <span x-text="text"></span>
                            <x-ui.icon name="check" :size="4" x-show="key == value" />
                        </button>
                    </li>
                </template>
                <li x-show="filtered.length === 0" class="px-3 py-4 text-center text-theme-xs text-gray-400">{{ __('ui.labels.no_results') }}</li>
            </ul>
        </div>
    </div>
</x-form.field>
