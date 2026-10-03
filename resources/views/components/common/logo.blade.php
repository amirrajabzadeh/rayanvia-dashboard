@props([
    'mark' => false, // true: فقط نشانه (fav.svg)؛ false: لوگوی کامل با نسخه تم تیره
])

@if ($mark)
    <img src="{{ asset('images/logo/fav.svg') }}" alt="{{ __('ui.brand') }}" {{ $attributes->class('shrink-0') }}>
@else
    <span {{ $attributes->class('inline-flex shrink-0') }}>
        <img src="{{ asset('images/logo/logo.svg') }}" alt="{{ __('ui.brand') }}" class="h-full w-auto dark:hidden">
        <img src="{{ asset('images/logo/logo-dark.svg') }}" alt="{{ __('ui.brand') }}" class="hidden h-full w-auto dark:block">
    </span>
@endif
