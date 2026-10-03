@props([
    'name',               // پیشوند نام فیلدها: {name}_lat و {name}_lng
    'label' => null,
    'lat' => null,
    'lng' => null,
    'zoom' => null,
    'height' => 360,      // ارتفاع نقشه به پیکسل
    'readonly' => false,
    'search' => null,     // جستجوی مکان؛ null = فقط وقتی نقشه قابل ویرایش است
    'required' => false,
    'error' => null,
    'hint' => null,
])

@php
    $latName = $name.'_lat';
    $lngName = $name.'_lng';
    $config = [
        'lat' => old($latName, $lat),
        'lng' => old($lngName, $lng),
        'center' => config('ui.map.center'),
        'zoom' => $zoom ?? config('ui.map.zoom'),
        'tileUrl' => config('ui.map.tile_url'),
        'attribution' => config('ui.map.attribution'),
        'satellite' => config('ui.map.satellite_tile_url') ? [
            'url' => config('ui.map.satellite_tile_url'),
            'attribution' => config('ui.map.satellite_attribution'),
        ] : null,
        'neshan' => config('ui.map.neshan_key') ? [
            'key' => config('ui.map.neshan_key'),
            'type' => config('ui.map.neshan_type'),
            'poi' => (bool) config('ui.map.neshan_poi'),
            'traffic' => (bool) config('ui.map.neshan_traffic'),
        ] : null,
        'readonly' => (bool) $readonly,
    ];
    $searchEnabled = ($search ?? ! $readonly) && \Illuminate\Support\Facades\Route::has('map.search');
    $config['searchUrl'] = $searchEnabled ? route('map.search') : null;
    $hasError = $error ?? ($errors->has($latName) || $errors->has($lngName));
    $message = $error ?? ($errors->first($latName) ?: $errors->first($lngName));
@endphp

{{--
    موبایل: نقشه بدون قاب کارت، کنترل‌ها شناور روی نقشه و دکمه تمام‌صفحه.
    از md به بالا: کارت با سربرگ (عنوان، مختصات، موقعیت من) و راهنما در پایین.
--}}
<div {{ $attributes->class('w-full') }}>
    <div x-data="mapPicker(@js($config))"
         @keydown.escape.window="fullscreen && toggleFullscreen()"
         :class="fullscreen ? 'fixed inset-0 z-100000 flex flex-col bg-canvas' : 'relative'"
         @class(['md:card-surface md:overflow-hidden', 'md:border-error-400' => $hasError])>

        {{-- سربرگ کارت (md+) --}}
        <div class="hidden items-center gap-3 border-b border-gray-200 px-4 py-3 md:flex dark:border-gray-800">
            <span class="flex size-8 items-center justify-center rounded-md bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300">
                <x-ui.icon name="map-pin" :size="4" />
            </span>
            <div class="min-w-0 flex-1">
                @if ($label)
                    <div class="text-theme-sm font-semibold text-gray-800 dark:text-gray-100">
                        {{ $label }}
                        @if ($required)<span class="text-error-500" title="{{ __('ui.labels.required') }}">*</span>@endif
                    </div>
                @endif
                <div class="text-theme-xs text-gray-500 dark:text-gray-400">
                    <span x-show="coords" class="ident" x-text="coords"></span>
                    <span x-show="!coords">{{ __('ui.labels.map_no_point') }}</span>
                </div>
            </div>
            <div x-show="hasSatellite" x-cloak class="inline-flex rounded-md border border-gray-200 p-0.5 text-theme-xs dark:border-gray-800" role="group">
                <button type="button" @click="setLayer('map')" :aria-pressed="layer === 'map'"
                        :class="layer === 'map' ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'"
                        class="rounded px-2.5 py-1 font-medium transition-colors">{{ __('ui.labels.map_layer_map') }}</button>
                <button type="button" @click="setLayer('satellite')" :aria-pressed="layer === 'satellite'"
                        :class="layer === 'satellite' ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'"
                        class="rounded px-2.5 py-1 font-medium transition-colors">{{ __('ui.labels.map_layer_satellite') }}</button>
            </div>
            @unless ($readonly)
                <x-ui.button type="button" variant="outline" size="sm" icon="crosshair" x-on:click="locate()" ::disabled="locating">
                    {{ __('ui.labels.map_locate') }}
                </x-ui.button>
            @endunless
        </div>

        {{-- برچسب بالای نقشه در موبایل --}}
        @if ($label)
            <div class="mb-1.5 text-theme-sm font-medium text-gray-700 md:hidden dark:text-gray-300" x-show="!fullscreen">
                {{ $label }}
                @if ($required)<span class="text-error-500">*</span>@endif
            </div>
        @endif

        {{-- جستجوی مکان: نتیجه‌ها از سرور ما می‌آید (کلید سرویس هرگز به مرورگر نمی‌رسد) --}}
        @if ($searchEnabled)
            <div class="relative mb-2 md:mb-0 md:border-b md:border-gray-200 md:px-4 md:py-3 md:dark:border-gray-800" @click.outside="searchOpen = false">
                <div class="form-shell">
                    <span class="form-addon pe-0"><x-ui.icon name="search" :size="4" class="text-gray-400" /></span>
                    <input type="search" x-model="query" data-auto-dir autocomplete="off" role="combobox"
                           :aria-expanded="searchOpen" aria-controls="{{ $latName }}-results"
                           placeholder="{{ __('ui.labels.map_search_placeholder') }}"
                           @input="onSearchInput()" @keydown="onSearchKeydown($event)" @focus="searched && (searchOpen = true)"
                           class="form-bare text-start [&::-webkit-search-cancel-button]:hidden">
                    <button type="button" x-show="query" x-cloak @click="clearSearch()"
                            class="flex w-9 shrink-0 items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                            aria-label="{{ __('ui.actions.clear') }}">
                        <x-ui.icon name="x" :size="4" />
                    </button>
                    <span x-show="searching" x-cloak class="flex w-9 shrink-0 items-center justify-center">
                        <span class="size-4 animate-spin rounded-full border-2 border-gray-300 border-t-brand-500"></span>
                    </span>
                </div>

                <ul x-show="searchOpen && (searching || searched)" x-cloak id="{{ $latName }}-results" role="listbox"
                    class="custom-scrollbar absolute inset-x-0 top-full z-1100 mt-1 max-h-72 overflow-y-auto rounded-md border border-gray-200 bg-white py-1 shadow-theme-lg md:inset-x-4 dark:border-gray-700 dark:bg-gray-900">
                    <li x-show="searching && !results.length" class="px-3 py-2.5 text-theme-xs text-gray-500">{{ __('ui.labels.map_searching') }}</li>
                    <li x-show="searchError" class="px-3 py-2.5 text-theme-xs leading-6 text-error-600 dark:text-error-400" x-text="searchMessage || @js(__('ui.labels.map_search_failed_unavailable'))"></li>
                    <li x-show="!searching && searched && !searchError && !results.length" class="px-3 py-2.5 text-theme-xs text-gray-500">{{ __('ui.labels.map_search_empty') }}</li>
                    <template x-for="(place, index) in results" :key="index">
                        <li role="option" :aria-selected="active === index" @click="choose(place)" @mouseenter="active = index"
                            :class="active === index ? 'bg-brand-50 dark:bg-brand-500/15' : ''"
                            class="flex cursor-pointer items-start gap-2.5 px-3 py-2">
                            <x-ui.icon name="map-pin" :size="4" class="mt-0.5 shrink-0 text-brand-500" />
                            <span class="min-w-0">
                                <span class="block truncate text-theme-sm font-medium text-gray-800 dark:text-gray-100" dir="auto" x-text="place.title"></span>
                                <span class="block truncate text-theme-xs text-gray-500 dark:text-gray-400" dir="auto" x-text="place.address"></span>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>
        @endif

        <div class="relative" :class="fullscreen && 'flex-1'">
            <div x-ref="map" dir="ltr"
                 :style="fullscreen ? 'height: 100%' : 'height: {{ (int) $height }}px'"
                 @class([
                     'z-0 w-full bg-gray-100 dark:bg-gray-800',
                     'rounded-md border md:rounded-none md:border-0',
                     'border-error-400' => $hasError,
                     'border-gray-300 dark:border-gray-700' => ! $hasError,
                 ])
                 :class="fullscreen && '!rounded-none !border-0'"></div>

            {{-- کنترل‌های شناور موبایل --}}
            {{-- زوم بالا-چپ (Leaflet)؛ دکمه‌های شناور بالا-راست؛ مختصات پایین-راست --}}
            <span x-show="coords" x-cloak class="ident pointer-events-none absolute right-2 bottom-2 z-1000 rounded-md bg-white/95 px-2 py-1 text-[11px] text-gray-700 shadow-theme-sm md:hidden dark:bg-gray-900/95 dark:text-gray-200" x-text="coords"></span>
            <div class="pointer-events-none absolute top-2 right-2 z-1000 md:hidden">
                <div class="pointer-events-auto flex gap-1.5">
                    <button type="button" x-show="hasSatellite" x-cloak @click="setLayer(layer === 'map' ? 'satellite' : 'map')"
                            class="flex size-9 items-center justify-center rounded-md bg-white text-gray-700 shadow-theme-md dark:bg-gray-900 dark:text-gray-200"
                            :class="layer === 'satellite' && 'text-brand-600 ring-2 ring-brand-500 dark:text-brand-300'"
                            :aria-label="layer === 'map' ? @js(__('ui.labels.map_layer_satellite')) : @js(__('ui.labels.map_layer_map'))">
                        <x-ui.icon name="layers" :size="5" />
                    </button>
                    @unless ($readonly)
                        <button type="button" @click="locate()" :disabled="locating"
                                class="flex size-9 items-center justify-center rounded-md bg-white text-gray-700 shadow-theme-md dark:bg-gray-900 dark:text-gray-200"
                                aria-label="{{ __('ui.labels.map_locate') }}">
                            <x-ui.icon name="crosshair" :size="5" ::class="locating && 'animate-pulse text-brand-500'" />
                        </button>
                    @endunless
                    <button type="button" @click="toggleFullscreen()"
                            class="flex size-9 items-center justify-center rounded-md bg-white text-gray-700 shadow-theme-md dark:bg-gray-900 dark:text-gray-200"
                            :aria-label="fullscreen ? @js(__('ui.labels.map_exit_fullscreen')) : @js(__('ui.labels.map_fullscreen'))">
                        <x-ui.icon name="maximize" :size="5" x-show="!fullscreen" />
                        <x-ui.icon name="minimize" :size="5" x-show="fullscreen" x-cloak />
                    </button>
                </div>
            </div>
        </div>

        @unless ($readonly)
            <p class="hidden border-t border-gray-200 px-4 py-2 text-theme-xs text-gray-500 md:block dark:border-gray-800 dark:text-gray-400">
                {{ $hint ?? __('ui.labels.map_pick_hint') }}
            </p>
        @endunless

        <input type="hidden" name="{{ $latName }}" :value="lat ?? ''">
        <input type="hidden" name="{{ $lngName }}" :value="lng ?? ''">
    </div>

    @if ($hint || ! $readonly)
        <p class="mt-1.5 text-theme-xs text-gray-500 md:hidden dark:text-gray-400">{{ $hint ?? __('ui.labels.map_pick_hint') }}</p>
    @endif
    <p data-error-for="{{ $latName }}" @class(['mt-1.5 text-theme-xs text-error-600 dark:text-error-400', 'hidden' => ! $message])>{{ $message }}</p>
</div>
