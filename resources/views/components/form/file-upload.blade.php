@props([
    'name',
    'label' => null,
    'accept' => null,
    'multiple' => false,
    'required' => false,
    'error' => null,
    'hint' => null,
])

{{-- آپلود در storage/app/private با محدودیت mime و حجم سمت سرور (FormRequest). اینجا فقط انتخابگر است. --}}
<x-form.field :name="$name" :label="$label" :required="$required" :hint="$hint" :error="$error">
    <div x-data="fileUpload({ multiple: @js($multiple) })" class="space-y-2">
        <label @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="drop($event)"
               :class="dragging ? 'border-brand-400 bg-brand-25 dark:bg-brand-500/10' : 'border-gray-300 hover:border-brand-300 dark:border-gray-700'"
               class="flex cursor-pointer flex-col items-center justify-center rounded-md border border-dashed px-4 py-6 text-center transition">
            <x-ui.icon name="upload-cloud" :size="8" class="mb-2 text-gray-400" />
            <span class="text-theme-sm text-gray-600 dark:text-gray-300">{{ __('ui.labels.file_drop') }}</span>
            <span class="mt-0.5 text-theme-xs text-gray-400">{{ __('ui.labels.file_hint') }}</span>

            <input x-ref="input" type="file" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                   @if ($accept) accept="{{ $accept }}" @endif @if ($multiple) multiple @endif
                   @change="pick($event.target.files)" class="sr-only">
        </label>

        <ul x-show="files.length" x-cloak class="space-y-1.5">
            <template x-for="(file, index) in files" :key="file.name + file.size + index">
                <li class="flex items-center gap-3 rounded-md border border-gray-200 bg-gray-50 py-1.5 ps-3 pe-1.5 dark:border-gray-800 dark:bg-white/5">
                    <x-ui.icon name="file" :size="4" class="shrink-0 text-brand-500" />
                    <span class="min-w-0 flex-1 truncate text-theme-xs text-gray-700 dark:text-gray-200" dir="auto" x-text="file.name"></span>
                    <span class="num shrink-0 text-theme-xs text-gray-400" x-text="sizeLabel(file)"></span>
                    <button type="button" @click="remove(index)"
                            class="flex size-7 shrink-0 items-center justify-center rounded text-gray-400 transition hover:bg-error-50 hover:text-error-600 dark:hover:bg-error-500/10 dark:hover:text-error-400"
                            aria-label="{{ __('ui.labels.remove_file') }}" title="{{ __('ui.labels.remove_file') }}">
                        <x-ui.icon name="x" :size="4" />
                    </button>
                </li>
            </template>
        </ul>

        @if ($multiple)
            <button type="button" x-show="files.length > 1" x-cloak @click="clear()"
                    class="text-theme-xs font-medium text-error-600 hover:underline dark:text-error-400">{{ __('ui.labels.clear_files') }}</button>
        @endif
    </div>
</x-form.field>
