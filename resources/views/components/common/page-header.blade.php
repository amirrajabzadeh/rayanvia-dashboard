@props([
    'title',
    'desc' => null,
    'breadcrumbs' => [], // [['label' => '...', 'url' => '...'], ...] (آخری بدون url = صفحه جاری)
])

<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div class="min-w-0">
        @if ($breadcrumbs)
            <nav aria-label="breadcrumb" class="mb-1.5">
                <ol class="flex flex-wrap items-center gap-1 text-theme-xs text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="hover:text-brand-600">{{ __('ui.menu.dashboard') }}</a>
                    </li>
                    @foreach ($breadcrumbs as $crumb)
                        <li class="flex items-center gap-1">
                            <x-ui.icon name="chevron-left" :size="3.5" class="text-gray-300 dark:text-gray-600" />
                            @if (! empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}" class="hover:text-brand-600">{{ $crumb['label'] }}</a>
                            @else
                                <span class="text-gray-700 dark:text-gray-200">{{ $crumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
        @if ($desc)
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">{{ $desc }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
