@php
    use App\Support\Menu;

    $menuGroups = Menu::for(auth()->user());
@endphp

<aside id="sidebar"
       class="fixed inset-y-0 start-0 z-99999 flex flex-col border-e border-shell-line bg-shell text-shell-text transition-[width,transform] duration-300 ease-in-out"
       :class="{
           'w-[280px]': $store.sidebar.isWide,
           'w-[76px]': !$store.sidebar.isWide,
           'translate-x-0': $store.sidebar.isMobileOpen,
           'max-xl:ltr:-translate-x-full max-xl:rtl:translate-x-full': !$store.sidebar.isMobileOpen
       }"
       @mouseenter="$store.sidebar.setHovered(true)"
       @mouseleave="$store.sidebar.setHovered(false)">

    {{-- برند --}}
    <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}"
       class="flex h-[72px] shrink-0 items-center gap-3 border-b border-shell-line px-[19px]">
        <x-common.logo class="h-9" x-show="$store.sidebar.isWide" x-cloak />
        <x-common.logo mark class="size-[38px]" x-show="!$store.sidebar.isWide" />
    </a>

    {{-- منو --}}
    <nav class="custom-scrollbar flex-1 space-y-5 overflow-y-auto px-3 py-4" aria-label="main">
        @foreach ($menuGroups as $group)
            <div>
                <div class="mb-2 px-3 text-[11px] font-semibold tracking-wide text-shell-muted"
                     x-show="$store.sidebar.isWide" x-cloak>{{ $group['title'] }}</div>
                <div class="mx-3 mb-2 h-px bg-shell-line" x-show="!$store.sidebar.isWide"></div>

                <ul class="space-y-0.5">
                    @foreach ($group['items'] as $item)
                        <li x-data="{ open: {{ $item['active'] && $item['children'] ? 'true' : 'false' }} }">
                            @if ($item['children'])
                                <button type="button" @click="open = !open"
                                        class="sidebar-item {{ $item['active'] ? 'sidebar-item-active' : '' }}">
                                    <x-ui.icon :name="$item['icon']" :size="5" />
                                    <span x-show="$store.sidebar.isWide" x-cloak class="flex-1 truncate text-start">{{ $item['label'] }}</span>
                                    <x-ui.icon name="chevron-down" :size="4" x-show="$store.sidebar.isWide" x-cloak
                                               class="transition-transform" ::class="open && 'rotate-180'" />
                                </button>
                                <div x-show="open && $store.sidebar.isWide" x-collapse x-cloak>
                                    <ul class="mt-1 space-y-0.5 ps-5">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a href="{{ $child['url'] }}"
                                                   class="sidebar-subitem {{ $child['active'] ? 'sidebar-subitem-active' : '' }}">
                                                    <span class="size-1.5 rounded-full {{ $child['active'] ? 'bg-brand-500' : 'bg-shell-line' }}"></span>
                                                    {{ $child['label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ $item['url'] }}" title="{{ $item['label'] }}"
                                   class="sidebar-item {{ $item['active'] ? 'sidebar-item-active' : '' }}">
                                    <x-ui.icon :name="$item['icon']" :size="5" />
                                    <span x-show="$store.sidebar.isWide" x-cloak class="flex-1 truncate">{{ $item['label'] }}</span>
                                    @if ($item['badge'])
                                        <span x-show="$store.sidebar.isWide" x-cloak
                                              class="rounded-full bg-accent-500 px-1.5 text-[11px] font-bold text-gray-950">{{ $item['badge'] }}</span>
                                    @endif
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    {{-- پایین سایدبار --}}
    <div class="shrink-0 border-t border-shell-line p-3">
        <div class="flex items-center gap-3 rounded-md px-2 py-1.5">
            <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 dark:bg-brand-500/20 dark:text-brand-300">
                <x-ui.icon name="user" :size="5" />
            </span>
            <div x-show="$store.sidebar.isWide" x-cloak class="min-w-0 leading-tight">
                <div class="truncate text-theme-sm font-medium text-shell-strong">{{ auth()->user()?->name ?? __('ui.labels.user_name') }}</div>
                <div class="truncate text-theme-xs text-shell-muted">{{ __('ui.labels.version', ['version' => \App\Support\Fa::digits(config('ui.version'))]) }}</div>
            </div>
        </div>
    </div>
</aside>
