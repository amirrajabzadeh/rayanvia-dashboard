@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'mask' => 24,          // پیش‌فرض طول پیشوند؛ با withMask=false فیلد ماسک حذف می‌شود
    'withMask' => true,
    'maskName' => null,    // پیش‌فرض: {name}_mask
    'placeholder' => '192.168.1.1',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
])

@php
    $id ??= 'f-'.str_replace(['[', ']'], '-', $name);
    $maskName ??= $name.'_mask';
    $hasError = $error ?? ($errors->has($name) || $errors->has($maskName));
@endphp

{{--
    آدرس IP با پیشوند قابل تغییر (CIDR). قاب چپ‌به‌راست: IP از چپ شروع می‌شود و «/ماسک» بلافاصله بعد از آن.
    فقط رقم و نقطه پذیرفته می‌شود؛ با تایپ «/» مکان‌نما به فیلد ماسک می‌رود. اعتبارسنجی نهایی در FormRequest.
--}}
<x-form.field :name="$name" :label="$label" :for="$id" :required="$required" :hint="$hint" :error="$error">
    <div x-data="ipInput({ mask: @js(old($maskName, $mask)) })" @click.self="$refs.ip.focus()"
         class="form-shell cursor-text" dir="ltr">
        <span class="form-addon pe-0"><x-ui.icon name="globe" :size="4" class="text-gray-400" /></span>
        <input x-ref="ip" id="{{ $id }}" name="{{ $name }}" type="text" inputmode="decimal" autocomplete="off" spellcheck="false"
               value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}"
               @keydown="onIpKeydown($event)" @input="onIpInput($event)"
               @required($required) @disabled($disabled)
               @if ($hasError) aria-invalid="true" @endif
               {{ $attributes->class(['form-bare ident text-start', 'min-w-[9ch] flex-none field-sizing-content pe-1' => $withMask]) }}>

        @if ($withMask)
            <span class="flex items-center text-gray-400 select-none">/</span>
            <input x-ref="mask" name="{{ $maskName }}" type="text" inputmode="numeric" autocomplete="off" maxlength="2"
                   x-model="mask" @keydown="onMaskKeydown($event)" @input="onMaskInput($event)" @blur="clampMask()"
                   @disabled($disabled) aria-label="{{ __('ui.labels.ip_mask') }}"
                   class="form-bare ident w-9 flex-none ps-1 pe-2 text-start">
            <span class="flex-1" @click="$refs.ip.focus()"></span>
        @endif
    </div>
</x-form.field>
