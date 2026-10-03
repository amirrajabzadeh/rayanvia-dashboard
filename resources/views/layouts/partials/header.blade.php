@php($user = auth()->user())

<header class="sticky top-0 z-9999 flex h-[72px] items-center gap-3 border-shell-line bg-shell max-xl:border-b px-4 text-shell-text md:px-6">
    {{-- کلید سایدبار: دسکتاپ جمع/باز، موبایل کشویی --}}
    <button type="button" class="shell-btn max-xl:hidden"
            @click="$store.sidebar.toggleExpanded()" aria-label="{{ __('ui.labels.toggle_menu') }}">
        <x-ui.icon name="sidebar" :size="5" />
    </button>
    <button type="button" class="shell-btn xl:hidden"
            @click="$store.sidebar.toggleMobileOpen()" aria-label="{{ __('ui.labels.toggle_menu') }}">
        <x-ui.icon name="menu" :size="5" />
    </button>

    {{-- جستجوی سراسری --}}
    <label class="hidden h-9 max-w-md flex-1 items-center gap-2 rounded-md border border-shell-line bg-shell-deep px-3 text-shell-muted transition focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-500/20 md:flex">
        <x-ui.icon name="search" :size="4" class="shrink-0" />
        <input type="search" data-auto-dir placeholder="{{ __('ui.search.global_placeholder') }}"
               class="h-full min-w-0 flex-1 border-0 bg-transparent text-start text-theme-sm text-shell-strong outline-hidden placeholder:text-shell-muted"
               aria-label="{{ __('ui.actions.search') }}">
    </label>

    <div class="ms-auto flex items-center gap-1.5 sm:gap-2">
        <x-common.theme-toggle shell />

        {{-- اعلان‌ها --}}
        <x-common.dropdown align="end" width="w-80">
            <x-slot:trigger>
                <span class="shell-btn">
                    <x-ui.icon name="bell" :size="5" />
                    <span class="absolute end-2 top-2 size-2 rounded-full bg-accent-500 ring-2 ring-shell"></span>
                </span>
            </x-slot:trigger>
            <div class="border-b border-gray-100 px-4 py-3 text-theme-sm font-semibold text-gray-800 dark:border-gray-800 dark:text-gray-100">
                {{ __('ui.labels.notifications') }}
            </div>
            <x-ui.empty-state icon="bell" :title="__('ui.labels.no_notifications')" compact />
        </x-common.dropdown>

        {{-- منوی کاربر --}}
        <x-common.dropdown align="end" width="w-60">
            <x-slot:trigger>
                <span class="flex items-center gap-2.5 rounded-lg py-1.5 ps-1.5 pe-2.5 transition hover:bg-shell-hover" :class="open && 'bg-shell-hover'">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 dark:bg-brand-500/20 dark:text-brand-300">
                        <x-ui.icon name="user" :size="5" />
                    </span>
                    <span class="hidden text-start leading-tight sm:block">
                        <span class="block text-theme-sm font-medium text-shell-strong">{{ $user?->name ?? __('ui.labels.user_name') }}</span>
                        <span class="block text-theme-xs text-shell-muted">{{ __('ui.labels.user_role') }}</span>
                    </span>
                    <x-ui.icon name="chevron-down" :size="4" class="hidden shrink-0 text-shell-muted transition-transform duration-200 sm:block" ::class="open && 'rotate-180'" />
                </span>
            </x-slot:trigger>
            <div class="flex items-center gap-3 border-b border-gray-100 px-4 py-3.5 dark:border-gray-800">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 dark:bg-brand-500/20 dark:text-brand-300">
                    <x-ui.icon name="user" :size="5" />
                </span>
                <div class="min-w-0 leading-tight">
                    <div class="truncate text-theme-sm font-semibold text-gray-800 dark:text-gray-100">{{ $user?->name ?? __('ui.labels.user_name') }}</div>
                    <div class="truncate text-theme-xs text-gray-500">{{ __('ui.labels.user_role') }}</div>
                </div>
            </div>
            <div class="p-1.5">
                <a href="#" class="flex items-center gap-2 rounded-md px-3 py-2 text-theme-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5">
                    <x-ui.icon name="user" :size="4" class="text-gray-400" />
                    {{ __('ui.actions.profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-theme-sm text-error-600 hover:bg-error-50 dark:text-error-400 dark:hover:bg-error-500/10">
                        <x-ui.icon name="logout" :size="4" />
                        {{ __('ui.actions.logout') }}
                    </button>
                </form>
            </div>
        </x-common.dropdown>
    </div>
</header>
