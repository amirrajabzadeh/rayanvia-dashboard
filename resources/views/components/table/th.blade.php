@props([
    'label' => null,
    'field' => null,
    'sortable' => false,
])

@php
    $isSorted = $sortable && request('sort_by') === $field;
    $order = request('sort_order', 'asc');
@endphp

{{-- مرتب‌سازی: دکمه data-sort مقدار sort_by / sort_order را در #filterForm می‌نویسد و جدول را دوباره می‌گیرد --}}
<th scope="col" @if ($isSorted) aria-sort="{{ $order === 'asc' ? 'ascending' : 'descending' }}" @endif
    {{ $attributes->class('px-5 py-2.5 text-start text-theme-xs font-semibold whitespace-nowrap text-brand-800 dark:text-gray-300') }}>
    @if ($sortable)
        <button type="button" data-sort="{{ $field }}"
                class="group inline-flex items-center gap-1 transition-colors hover:text-brand-950 dark:hover:text-white">
            {{ $label ?? $slot }}
            <span class="{{ $isSorted ? 'text-brand-600 dark:text-brand-300' : 'text-gray-300 group-hover:text-gray-400 dark:text-gray-600' }}">
                @if ($isSorted)
                    <x-ui.icon :name="$order === 'asc' ? 'arrow-up' : 'arrow-down'" :size="3.5" :stroke="2.25" />
                @else
                    <x-ui.icon name="chevrons-up-down" :size="3.5" />
                @endif
            </span>
        </button>
    @else
        {{ $label ?? $slot }}
    @endif
</th>
