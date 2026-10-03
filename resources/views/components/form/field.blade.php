@props([
    'name' => null,
    'label' => null,
    'for' => null,
    'required' => false,
    'hint' => null,
    'error' => null,
])

@php
    $message = $error ?? ($name ? $errors->first(str_replace(['[', ']'], ['.', ''], $name)) : null);
@endphp

<div {{ $attributes->class('w-full') }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif class="mb-1.5 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if ($required)
                <span class="text-error-500" title="{{ __('ui.labels.required') }}">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($hint && ! $message)
        <p class="mt-1.5 text-theme-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    {{-- محل نمایش خطا: هم برای خطای سروری و هم برای ۴۲۲ در فرم‌های AJAX --}}
    <p @if ($name) data-error-for="{{ $name }}" @endif
       @class(['mt-1.5 text-theme-xs text-error-600 dark:text-error-400', 'hidden' => ! $message])>{{ $message }}</p>
</div>
