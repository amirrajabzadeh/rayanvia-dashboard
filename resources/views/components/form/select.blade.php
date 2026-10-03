@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [], // ['value' => 'برچسب'] — مثلاً Enum::options()
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
])

@php
    $id ??= 'f-'.str_replace(['[', ']'], '-', $name);
    $selected = (string) old(str_replace(['[', ']'], ['.', ''], $name), $value);
    $hasError = $error ?? $errors->has(str_replace(['[', ']'], ['.', ''], $name));
    // آرایه جفت‌ها، نه شیء: کلید عددی در شیء جاوااسکریپت ترتیب را به‌هم می‌ریزد
    $items = collect($options)->map(fn ($text, $key) => ['value' => (string) $key, 'label' => (string) $text])->values()->all();
    $emptyLabel = $placeholder ?? __('ui.labels.select_placeholder');
@endphp

{{-- انتخابگر ساده با ظاهر select2 و بدون جستجو؛ مقدار در input مخفی ارسال می‌شود و رویداد change برای فیلتر AJAX پخش می‌شود --}}
<x-form.field :name="$name" :label="$label" :for="$id" :required="$required" :hint="$hint" :error="$error">
    <div x-data="select({ items: @js($items), value: @js($selected), allowEmpty: @js($placeholder !== null) })"
         @click.outside="close()" @keydown.escape="close()" class="relative">
        <input type="hidden" x-ref="input" name="{{ $name }}" data-select :value="value" @disabled($disabled)>

        <button type="button" id="{{ $id }}" role="combobox" aria-haspopup="listbox"
                :aria-expanded="open" aria-controls="{{ $id }}-list" @disabled($disabled)
                @click="toggle()" @keydown="onKeydown($event)"
                @if ($hasError) aria-invalid="true" @endif
                class="form-control flex items-center justify-between gap-2 text-start">
            <span class="truncate" :class="selectedLabel === null && 'text-gray-400'"
                  x-text="selectedLabel ?? @js($emptyLabel)"></span>
            <x-ui.icon name="chevron-down" :size="4" class="shrink-0 text-gray-400 transition-transform duration-200"
                       ::class="open && 'rotate-180'" />
        </button>

        <ul x-show="open" x-cloak x-transition.opacity.origin.top.duration.100ms id="{{ $id }}-list" role="listbox"
            class="custom-scrollbar absolute z-99 mt-1 max-h-60 w-full overflow-y-auto rounded-md border border-gray-300 bg-white py-1 shadow-theme-lg dark:border-gray-700 dark:bg-gray-900">
            @if ($placeholder !== null)
                <li role="option" @click="choose('')" @mouseenter="active = -1"
                    :class="active === -1 ? 'bg-brand-500 text-white' : 'text-gray-400'"
                    class="cursor-pointer px-3 py-2 text-theme-sm">{{ $placeholder }}</li>
            @endif
            <template x-for="(item, index) in items" :key="item.value">
                <li role="option" :aria-selected="item.value === value" :data-index="index"
                    @click="choose(item.value)" @mouseenter="active = index"
                    :class="active === index
                        ? 'bg-brand-500 text-white'
                        : (item.value === value ? 'bg-gray-100 text-gray-900 dark:bg-white/8 dark:text-white' : 'text-gray-700 dark:text-gray-200')"
                    class="cursor-pointer px-3 py-2 text-theme-sm">
                    <span x-text="item.label"></span>
                </li>
            </template>
            <li x-show="items.length === 0" class="px-3 py-3 text-center text-theme-xs text-gray-400">{{ __('ui.labels.no_results') }}</li>
        </ul>
    </div>
</x-form.field>
