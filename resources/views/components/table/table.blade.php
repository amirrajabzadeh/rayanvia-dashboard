@props([
    'empty' => false,
    'emptyTitle' => null,
    'emptyText' => null,
    'cardsBelow' => '2xl', // زیر این نقطه شکست ردیف‌ها کارت می‌شوند: lg، xl، 2xl یا null برای همیشه جدول
])

@php
    $responsiveClass = match ($cardsBelow) {
        'lg' => 'rtable rtable-lg',
        'xl' => 'rtable rtable-xl',
        '2xl' => 'rtable rtable-2xl',
        default => '',
    };
    // حداقل عرض فقط در حالت جدول؛ در حالت کارت باید صفر باشد
    $tableMinWidth = match ($cardsBelow) {
        'lg' => 'lg:min-w-[720px]',
        'xl' => 'xl:min-w-[720px]',
        '2xl' => '2xl:min-w-[720px]',
        default => 'min-w-[720px]',
    };
@endphp

{{--
    ظرف جدول: header اسلات ردیف عنوان‌هاست، slot ردیف‌ها، footer برای صفحه‌بندی.
    حالت کارت با CSS (components.css → rtable) و برچسب خانه‌ها از th (table.js)؛ ردیف‌ها دوبار نوشته نمی‌شوند.
--}}
<div {{ $attributes->class(['card-surface overflow-hidden', $responsiveClass]) }}>
    <div class="custom-scrollbar overflow-x-auto">
        <table class="w-full {{ $tableMinWidth }} text-theme-sm">
            @isset($header)
                <thead class="border-b border-brand-200/70 bg-brand-100/60 dark:border-gray-800 dark:bg-brand-500/10">
                    <tr>{{ $header }}</tr>
                </thead>
            @endisset
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if ($empty)
        <x-ui.empty-state :title="$emptyTitle" :text="$emptyText" />
    @endif

    @isset($footer)
        <div class="border-t border-gray-100 px-5 py-3 dark:border-gray-800">{{ $footer }}</div>
    @endisset
</div>
