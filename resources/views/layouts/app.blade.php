<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="h-full">
<head>
    @include('layouts.partials.head')
</head>
<body x-data>
    <x-common.preloader />

    <div class="min-h-screen">
        @include('layouts.partials.backdrop')
        @include('layouts.partials.sidebar')

        <div class="flex min-h-screen flex-col bg-shell transition-[margin] duration-300 ease-in-out"
             :class="$store.sidebar.isExpanded ? 'xl:ms-[280px]' : 'xl:ms-[76px]'">
            @include('layouts.partials.header')

            {{-- بوم محتوا: کمی متمایز از قاب (سایدبار + هدر) --}}
            <div class="flex-1 bg-canvas xl:rounded-ss-2xl xl:border-s xl:border-t xl:border-shell-line">
                <main class="mx-auto w-full max-w-(--breakpoint-2xl) p-4 md:p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
