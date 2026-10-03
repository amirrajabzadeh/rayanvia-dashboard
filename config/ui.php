<?php

return [
    /*
    | حالت پیش‌نمایش رابط کاربری: صفحه‌های ورود و داشبورد بدون احراز هویت باز می‌شوند.
    | فقط در محیط local کار می‌کند و باید پیش از پیاده‌سازی احراز هویت حذف شود.
    */
    'preview' => (bool) env('UI_PREVIEW', false),

    /*
    | اطلاعات ورود نمونه که همیشه در فرم ورود پر می‌شود تا قالب با یک کلیک دیده شود.
    | برای محصول واقعی UI_DEMO_LOGIN=false بگذارید؛ کاربر نمونه را هم با db:seed می‌سازید.
    */
    'demo_login' => env('UI_DEMO_LOGIN', true) ? [
        'email' => env('UI_DEMO_EMAIL', 'admin@rayanvia.test'),
        'password' => env('UI_DEMO_PASSWORD', 'password'),
    ] : null,

    // نسخه سامانه که زیر نام کاربر در سایدبار نمایش داده می‌شود
    'version' => env('APP_VERSION', '1.0.0'),

    /*
    | نقشه. اگر NESHAN_MAP_KEY تنظیم شده باشد نقشه نشان نمایش داده می‌شود، وگرنه OpenStreetMap.
    | کلید نقشه (web.*) در مرورگر دیده می‌شود؛ در پنل نشان آن را به دامنه سامانه محدود کنید.
    | مرکز پیش‌فرض: مشهد.
    */
    'map' => [
        'neshan_key' => env('NESHAN_MAP_KEY'),
        'neshan_type' => env('NESHAN_MAP_TYPE', 'neshan'), // neshan, dreamy, dreamy-gold, standard-day, standard-night, osm-bright
        'neshan_poi' => (bool) env('NESHAN_MAP_POI', true),
        'neshan_traffic' => (bool) env('NESHAN_MAP_TRAFFIC', false),
        // ماهواره: کاشی تصویر هوایی (Esri World Imagery). برای خاموش‌کردن، MAP_SATELLITE_TILE_URL را خالی بگذارید.
        'satellite_tile_url' => env('MAP_SATELLITE_TILE_URL', 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
        'satellite_attribution' => env('MAP_SATELLITE_ATTRIBUTION', 'Tiles &copy; Esri'),
        'tile_url' => env('MAP_TILE_URL', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'),
        'attribution' => env('MAP_ATTRIBUTION', '&copy; OpenStreetMap'),
        'center' => [36.2972, 59.6067],
        'zoom' => 13,
    ],
];
