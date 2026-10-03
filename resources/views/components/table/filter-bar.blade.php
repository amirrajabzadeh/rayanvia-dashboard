@props([
    'route',
    'resetUrl' => null,
    'sortBy' => null,
    'sortOrder' => 'asc',
])

{{-- فرم فیلتر: app.js با دیدن #filterForm خودش وصل می‌شود. نام فیلدها = کلیدهای only() کنترلر --}}
<form id="filterForm" data-route="{{ $route }}" method="GET" action="{{ $route }}"
      {{ $attributes->class('card-surface p-4') }}>
    <input type="hidden" name="sort_by" value="{{ request('sort_by', $sortBy) }}">
    <input type="hidden" name="sort_order" value="{{ request('sort_order', $sortOrder) }}">

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        {{ $slot }}

        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
            <x-ui.button type="submit" icon="filter">{{ __('ui.actions.filter') }}</x-ui.button>
            <x-ui.button :href="$resetUrl ?? $route" variant="ghost">{{ __('ui.actions.clear_filter') }}</x-ui.button>
        </div>
    </div>
</form>
