const DESKTOP_MIN = 1280;

export function registerStores(Alpine) {
    Alpine.store('theme', {
        theme: 'light',

        init() {
            let saved = null;
            try {
                saved = localStorage.getItem('theme');
            } catch (e) {
                //
            }
            const system = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            this.theme = saved || system;
            this.apply();
        },

        toggle() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            try {
                localStorage.setItem('theme', this.theme);
            } catch (e) {
                //
            }
            this.apply();
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: this.theme } }));
        },

        apply() {
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        },
    });

    Alpine.store('sidebar', {
        isExpanded: window.innerWidth >= DESKTOP_MIN,
        isMobileOpen: false,
        isHovered: false,

        init() {
            window.addEventListener('resize', () => {
                if (window.innerWidth < DESKTOP_MIN) {
                    this.isExpanded = false;
                } else {
                    this.isMobileOpen = false;
                }
            });
        },

        get isWide() {
            return this.isExpanded || this.isHovered || this.isMobileOpen;
        },

        toggleExpanded() {
            this.isExpanded = !this.isExpanded;
            this.isMobileOpen = false;
        },

        toggleMobileOpen() {
            this.isMobileOpen = !this.isMobileOpen;
        },

        setHovered(value) {
            if (window.innerWidth >= DESKTOP_MIN && !this.isExpanded) {
                this.isHovered = value;
            }
        },
    });
}
