@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'rows' => 4,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
])

@php
    $id ??= 'f-'.str_replace(['[', ']'], '-', $name);
    $hasError = $error ?? $errors->has(str_replace(['[', ']'], ['.', ''], $name));
@endphp

<x-form.field :name="$name" :label="$label" :for="$id" :required="$required" :hint="$hint" :error="$error">
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
              @if ($placeholder) placeholder="{{ $placeholder }}" @endif
              @required($required) @disabled($disabled)
              @if ($hasError) aria-invalid="true" @endif
              data-auto-dir
              {{ $attributes->class('form-control h-auto min-h-24 py-2.5 text-start') }}>{{ old(str_replace(['[', ']'], ['.', ''], $name), $value) }}</textarea>
</x-form.field>
