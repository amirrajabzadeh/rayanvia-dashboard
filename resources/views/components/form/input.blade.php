@props([
    'name',
    'id' => null,
    'type' => 'text',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => null,
    'icon' => null,
    'iconPosition' => 'auto', // right، left یا auto (فیلد چپ‌به‌راست مثل موبایل → چپ، بقیه → راست)
    'iconEnd' => null,   // آیکون دوم در انتهای فیلد
    'prefix' => null,    // متن ابتدای فیلد، مثلاً «۰۹»
    'suffix' => null,    // متن انتهای فیلد، مثلاً «ریال»
    'dir' => null,       // اگر خالی باشد جهت از روی متن تایپ‌شده تعیین می‌شود
])

@php
    $id ??= 'f-'.str_replace(['[', ']'], '-', $name);
    $hasError = $error ?? $errors->has(str_replace(['[', ']'], ['.', ''], $name));
    $isPassword = $type === 'password';
    $direction = $dir ?? (in_array($type, ['email', 'tel', 'number', 'url', 'password'], true) ? 'ltr' : null);
    // فیلد چپ‌به‌راست (موبایل، IP، ایمیل، رمز) قاب چپ‌به‌راست دارد تا پیشوند/پسوند به ترتیب طبیعی بیایند
    $shellDir = ($direction === 'ltr' || $isPassword) ? 'ltr' : 'rtl';
    // در فیلد رمز، دکمه نمایش رمز سمت چپ است؛ آیکون خودکار به راست می‌رود تا کنار هم نیفتند
    $iconSide = $iconPosition === 'auto' ? ($isPassword ? 'right' : ($shellDir === 'ltr' ? 'left' : 'right')) : $iconPosition;
    $iconAtEnd = $icon && ($iconSide === ($shellDir === 'rtl' ? 'left' : 'right'));
    $startIcon = $iconAtEnd ? null : $icon;
    $endIcon = $iconAtEnd ? $icon : $iconEnd;
    $hasAddon = $icon || $iconEnd || $prefix || $suffix || $isPassword || isset($start) || isset($end);
@endphp

<x-form.field :name="$name" :label="$label" :for="$id" :required="$required" :hint="$hint" :error="$error">
    <div @class(['form-shell' => $hasAddon]) @if ($hasAddon && $shellDir === 'ltr') dir="ltr" @endif @if ($isPassword) x-data="{ visible: false }" @endif>
        {{-- دکمه نمایش رمز همیشه اولین عنصر از چپ است --}}
        @if ($isPassword)
            <button type="button" @click="visible = !visible" tabindex="-1"
                    class="flex w-10 shrink-0 items-center justify-center text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200"
                    :aria-label="visible ? @js(__('ui.labels.hide_password')) : @js(__('ui.labels.show_password'))">
                <x-ui.icon name="eye" :size="4" x-show="!visible" />
                <x-ui.icon name="eye-off" :size="4" x-show="visible" x-cloak />
            </button>
        @endif

        @if ($startIcon || $prefix || isset($start))
            <span class="form-addon pe-0">
                @isset($start){{ $start }}@endisset
                @if ($startIcon)<x-ui.icon :name="$startIcon" :size="4" class="text-gray-400" />@endif
                @if ($prefix)<span>{{ $prefix }}</span>@endif
            </span>
        @endif

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            @if ($isPassword) :type="visible ? 'text' : 'password'" @else type="{{ $type }}" @endif
            value="{{ old(str_replace(['[', ']'], ['.', ''], $name), $value) }}"
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            @if ($direction) dir="{{ $direction }}" @else data-auto-dir @endif
            @required($required) @disabled($disabled) @readonly($readonly)
            @if ($hasError) aria-invalid="true" @endif
            {{ $attributes->class([$hasAddon ? 'form-bare' : 'form-control', 'text-start']) }}>

        @if ($suffix || $endIcon || isset($end))
            <span class="form-addon ps-0">
                @if ($suffix)<span>{{ $suffix }}</span>@endif
                @if ($endIcon)<x-ui.icon :name="$endIcon" :size="4" class="text-gray-400" />@endif
                @isset($end){{ $end }}@endisset
            </span>
        @endif
    </div>
</x-form.field>
