<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/svg+xml" href="{{ asset('images/logo/fav.svg') }}">
<meta name="theme-color" content="#034ea2">

<title>{{ $title ?? trim($__env->yieldContent('title', __('ui.app_title'))) }} | {{ __('ui.app_title') }}</title>

{{-- ضد-FOUC: تم پیش از رندر اعمال می‌شود (تنها نسخه، بین هر دو layout مشترک) --}}
<script>
    (function () {
        try {
            var saved = localStorage.getItem('theme');
            var dark = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', dark);
        } catch (e) {}
    })();
    window.rayan = { lang: @json(__('ui.js')) };
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('head')
