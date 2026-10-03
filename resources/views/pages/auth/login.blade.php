@extends('layouts.guest')

@section('title', __('ui.auth.title'))

@section('content')
    <div class="relative isolate flex min-h-screen items-center justify-center overflow-hidden bg-brand-25 px-4 py-12 dark:bg-ink">
        {{-- پس‌زمینه: لکه‌های رنگی، حلقه‌های مداری و شبکه نقطه‌ای --}}
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="aurora-blob absolute -top-32 start-[-8rem] size-[26rem] rounded-full bg-brand-500/30 blur-3xl dark:bg-brand-500/40"
                 style="--drift-x: 60px; --drift-y: 40px; --drift-duration: 18s"></div>
            <div class="aurora-blob absolute -bottom-40 end-[-6rem] size-[28rem] rounded-full bg-accent-500/25 blur-3xl dark:bg-accent-500/20"
                 style="--drift-x: -50px; --drift-y: -40px; --drift-duration: 22s"></div>
            <div class="aurora-blob absolute start-1/2 top-1/3 size-80 -translate-x-1/2 rounded-full bg-purple-500/20 blur-3xl"
                 style="--drift-x: 30px; --drift-y: 50px; --drift-duration: 26s"></div>

            <svg class="absolute left-1/2 top-1/2 size-[130vmax] -translate-x-1/2 -translate-y-1/2 text-brand-500/15 dark:text-white/10" viewBox="0 0 800 800" fill="none">
                <g class="orbit-ring" style="transform-origin: 400px 400px; --orbit-duration: 90s">
                    <circle cx="400" cy="400" r="190" stroke="currentColor" stroke-dasharray="2 8" />
                    <circle cx="400" cy="210" r="5" fill="currentColor" />
                </g>
                <g class="orbit-ring" style="transform-origin: 400px 400px; --orbit-duration: 140s; animation-direction: reverse">
                    <circle cx="400" cy="400" r="290" stroke="currentColor" />
                    <circle cx="690" cy="400" r="6" fill="currentColor" />
                </g>
                <circle cx="400" cy="400" r="390" stroke="currentColor" stroke-dasharray="1 10" />
            </svg>

            <div class="absolute inset-0 text-brand-900/10 [background-image:radial-gradient(currentColor_1px,transparent_1px)] [background-size:26px_26px] [mask-image:radial-gradient(ellipse_at_center,black_20%,transparent_70%)] dark:text-white/15"></div>
        </div>

        <div class="w-full max-w-md">
            <div class="rounded-2xl border border-white/70 bg-white/75 p-8 shadow-[0_30px_80px_-30px_rgba(3,78,162,.45)] backdrop-blur-xl dark:border-white/10 dark:bg-gray-900/70 dark:shadow-none sm:p-10">
                <div class="flex flex-col items-center text-center">
                    <x-common.logo mark class="size-14 rounded-2xl shadow-lg shadow-brand-500/30" />
                    <h1 class="mt-5 text-title-sm font-bold text-gray-900 dark:text-white">{{ __('ui.auth.title') }}</h1>
                    <p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">{{ __('ui.auth.subtitle') }}</p>
                </div>

                @if ($demo)
                    <div class="mt-6 flex items-center gap-2 rounded-lg border border-accent-500/30 bg-accent-500/10 px-3 py-2 text-theme-xs text-gray-700 dark:text-gray-200">
                        <x-ui.icon name="info" :size="4" class="shrink-0 text-accent-600" />
                        {{ __('ui.auth.demo_notice') }}
                    </div>
                @endif

                <form id="createForm" data-route="{{ route('login.store') }}" method="POST" class="mt-6 space-y-5" novalidate>
                    @csrf
                    <x-form.input name="email" type="email" :label="__('ui.auth.email')" icon="user" autocomplete="username"
                                  :value="$demo['email'] ?? null" required :autofocus="! $demo" />
                    <x-form.input name="password" type="password" :label="__('ui.auth.password')" autocomplete="current-password"
                                  :value="$demo['password'] ?? null" required />

                    <x-form.checkbox name="remember" value="1" :label="__('ui.auth.remember')" />

                    <x-ui.button type="submit" full-width size="lg" :autofocus="(bool) $demo">{{ __('ui.auth.submit') }}</x-ui.button>
                </form>

                <ul class="mt-8 flex flex-wrap justify-center gap-2">
                    @foreach (__('ui.auth.hero_points') as $point)
                        <li class="rounded-full border border-brand-100 bg-brand-25 px-3 py-1 text-theme-xs text-brand-700 dark:border-white/10 dark:bg-white/5 dark:text-brand-200">{{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            <p class="mt-6 text-center text-theme-xs text-gray-500 dark:text-gray-400">© {{ __('ui.auth.copyright') }}</p>
        </div>
    </div>
@endsection
