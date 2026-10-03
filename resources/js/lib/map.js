/**
 * انتخابگر موقعیت روی نقشه.
 * با کلید نشان (config/ui.php → map.neshan_key) نقشه نشان، وگرنه Leaflet با کاشی OpenStreetMap.
 * SDK نشان خودش نسخه‌ای از Leaflet 1.9.4 است، پس بقیه کد برای هر دو یکسان است.
 * کتابخانه فقط وقتی بارگذاری می‌شود که نقشه‌ای در صفحه باشد.
 * شیء نقشه و نشانگر در closure نگه داشته می‌شوند، نه در state واکنشی Alpine
 * (Proxy کردن اشیای Leaflet باعث خطا می‌شود).
 */

const loaders = {};

function loadLibrary(useNeshan) {
    const name = useNeshan ? 'neshan' : 'leaflet';
    loaders[name] ??= (
        useNeshan
            ? Promise.all([import('@neshan-maps-platform/leaflet'), import('@neshan-maps-platform/leaflet/dist/leaflet.css')])
            : Promise.all([import('leaflet'), import('leaflet/dist/leaflet.css')])
    ).then(([module]) => module.default ?? module);

    return loaders[name];
}

const round = (value) => Math.round(value * 1e6) / 1e6;

export function registerMap(Alpine) {
    Alpine.data('mapPicker', ({ lat = null, lng = null, center, zoom = 13, tileUrl, attribution = '', satellite = null, searchUrl = null, neshan = null, readonly = false }) => {
        let L = null;
        let map = null;
        let marker = null;
        let baseLayer = null;
        let satelliteLayer = null;
        let searchTimer = null;
        let searchController = null;

        return {
            lat: lat === null ? null : Number(lat),
            lng: lng === null ? null : Number(lng),
            fullscreen: false,
            locating: false,
            layer: 'map', // map | satellite
            hasSatellite: Boolean(satellite?.url),
            canSearch: Boolean(searchUrl),
            query: '',
            results: [],
            searching: false,
            searched: false,
            searchError: false,
            searchMessage: '',
            searchOpen: false,
            active: -1,
            ready: false,

            get coords() {
                return this.lat === null ? '' : `${this.lat.toFixed(6)}, ${this.lng.toFixed(6)}`;
            },

            async init() {
                const useNeshan = Boolean(neshan?.key);
                L = await loadLibrary(useNeshan);

                const start = this.lat === null ? center : [this.lat, this.lng];
                const options = { zoomControl: false, attributionControl: true, center: start, zoom };

                if (useNeshan) {
                    map = new L.Map(this.$refs.map, { ...options, key: neshan.key, maptype: neshan.type, poi: neshan.poi, traffic: neshan.traffic });
                } else {
                    map = L.map(this.$refs.map, options);
                    baseLayer = L.tileLayer(tileUrl, { maxZoom: 19, attribution }).addTo(map);
                }
                if (this.hasSatellite) {
                    satelliteLayer = L.tileLayer(satellite.url, { maxZoom: 19, attribution: satellite.attribution ?? '' });
                }
                L.control.zoom({ position: 'topleft' }).addTo(map);
                map.attributionControl?.setPosition('bottomleft').setPrefix(false);

                if (this.lat !== null) {
                    this.setPoint({ lat: this.lat, lng: this.lng });
                }
                if (!readonly) {
                    map.on('click', (event) => this.setPoint(event.latlng));
                }

                // نقشه داخل کارت جمع‌شده یا تب پنهان اندازه صفر می‌گیرد
                document.addEventListener('card-content:shown', () => map.invalidateSize());
                this.ready = true;
            },

            setPoint({ lat: newLat, lng: newLng }) {
                this.lat = round(newLat);
                this.lng = round(newLng);

                if (marker) {
                    marker.setLatLng([this.lat, this.lng]);
                    return;
                }

                marker = L.marker([this.lat, this.lng], {
                    draggable: !readonly,
                    keyboard: false,
                    icon: L.divIcon({ className: 'map-pin', html: '<span></span>', iconSize: [28, 36], iconAnchor: [14, 34] }),
                }).addTo(map);
                marker.on('dragend', () => this.setPoint(marker.getLatLng()));
            },

            locate() {
                if (!navigator.geolocation || readonly) {
                    return;
                }
                this.locating = true;
                navigator.geolocation.getCurrentPosition(
                    ({ coords }) => {
                        this.locating = false;
                        this.setPoint({ lat: coords.latitude, lng: coords.longitude });
                        map.setView([this.lat, this.lng], 17);
                    },
                    () => {
                        this.locating = false;
                    },
                    { enableHighAccuracy: true, timeout: 10000 },
                );
            },

            /** جستجو با تاخیر؛ درخواست قبلی لغو می‌شود. فراخوانی به سرور ما می‌رود، نه مستقیم به سرویس‌دهنده. */
            onSearchInput() {
                clearTimeout(searchTimer);
                this.active = -1;
                if (this.query.trim().length < 2) {
                    searchController?.abort();
                    this.results = [];
                    this.searched = false;
                    this.searchError = false;
                    this.searching = false;
                    return;
                }
                searchTimer = setTimeout(() => this.runSearch(), 450);
            },

            async runSearch() {
                clearTimeout(searchTimer);
                const term = this.query.trim();
                if (term.length < 2) {
                    return;
                }
                searchController?.abort();
                searchController = new AbortController();
                this.searching = true;
                this.searchError = false;
                this.searchOpen = true;

                try {
                    const center = map.getCenter();
                    const { data } = await window.axios.get(searchUrl, {
                        params: { q: term, lat: center.lat, lng: center.lng },
                        signal: searchController.signal,
                        silentErrors: true,
                    });
                    this.results = data.items ?? [];
                    this.searched = true;
                } catch (error) {
                    if (error.code !== 'ERR_CANCELED') {
                        this.results = [];
                        this.searchError = true;
                        this.searchMessage = error.response?.data?.message ?? '';
                        this.searched = true;
                    }
                } finally {
                    this.searching = false;
                }
            },

            onSearchKeydown(event) {
                if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    const step = event.key === 'ArrowDown' ? 1 : -1;
                    this.active = (this.active + step + this.results.length) % Math.max(this.results.length, 1);
                } else if (event.key === 'Enter') {
                    event.preventDefault();
                    this.results[this.active] ? this.choose(this.results[this.active]) : this.runSearch();
                } else if (event.key === 'Escape') {
                    this.searchOpen = false;
                }
            },

            choose(place) {
                this.searchOpen = false;
                this.query = place.title;
                if (!readonly) {
                    this.setPoint({ lat: place.lat, lng: place.lng });
                }
                map.setView([place.lat, place.lng], 17);
            },

            clearSearch() {
                this.query = '';
                this.results = [];
                this.searched = false;
                this.searchError = false;
                this.searchOpen = false;
            },

            /** تعویض نقشه و ماهواره؛ لایه ماهواره روی لایه پایه می‌نشیند و با برگشت برداشته می‌شود */
            setLayer(name) {
                if (!satelliteLayer || name === this.layer) {
                    return;
                }
                this.layer = name;
                if (name === 'satellite') {
                    baseLayer?.remove();
                    satelliteLayer.addTo(map);
                } else {
                    satelliteLayer.remove();
                    baseLayer?.addTo(map);
                }
            },

            toggleFullscreen() {
                this.fullscreen = !this.fullscreen;
                document.documentElement.classList.toggle('overflow-hidden', this.fullscreen);
                this.$nextTick(() => map.invalidateSize());
            },
        };
    });
}
