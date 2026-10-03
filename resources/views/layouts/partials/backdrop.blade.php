<div x-show="$store.sidebar.isMobileOpen" x-cloak
     @click="$store.sidebar.isMobileOpen = false"
     x-transition.opacity
     class="fixed inset-0 z-99998 bg-gray-950/60 backdrop-blur-[2px] xl:hidden"></div>
