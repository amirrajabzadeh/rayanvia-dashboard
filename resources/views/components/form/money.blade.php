@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
    'words' => true, // نمایش مبلغ به حروف زیر فیلد
])

@php
    $id ??= 'f-'.str_replace(['[', ']'], '-', $name);
    $hasError = $error ?? $errors->has(str_replace(['[', ']'], ['.', ''], $name));
@endphp

{{-- نمایش با ارقام فارسی و جداکننده؛ مقدار ارسالی ریال، لاتین و بدون جداکننده. مرجع مبلغ، محاسبه سرور است. --}}
<x-form.field :name="$name" :label="$label" :for="$id" :required="$required" :hint="$hint" :error="$error">
    {{-- قاب چپ‌به‌راست: «ریال» و عدد هر دو از چپ؛ فقط رقم پذیرفته می‌شود؛ مبلغ به حروف زیر فیلد --}}
    <div x-data="moneyInput(@js(old($name, $value)))">
        <div class="form-shell" dir="ltr">
            <span class="form-addon pe-0">{{ __('ui.labels.currency') }}</span>
            <input type="hidden" name="{{ $name }}" :value="raw">
            <input id="{{ $id }}" type="text" inputmode="numeric" autocomplete="off"
                   :value="display" @input="onInput($event)" @keydown="onKeydown($event)"
                   @if ($placeholder) placeholder="{{ $placeholder }}" @endif
                   @disabled($disabled)
                   @if ($hasError) aria-invalid="true" @endif
                   {{ $attributes->class('form-bare tabular-nums text-start') }}>
        </div>
        @if ($words)
            <p x-show="words" x-cloak class="mt-1.5 text-theme-xs font-medium text-brand-600 dark:text-brand-300" x-text="words"></p>
        @endif
    </div>
</x-form.field>
