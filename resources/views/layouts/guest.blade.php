<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="h-full">
<head>
    @include('layouts.partials.head')
</head>
<body x-data class="min-h-screen bg-white dark:bg-gray-950">
    <x-common.preloader />

    <div class="fixed end-4 top-4 z-99">
        <x-common.theme-toggle />
    </div>

    @yield('content')

    @stack('scripts')
</body>
</html>
