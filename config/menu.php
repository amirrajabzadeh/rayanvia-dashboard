<?php

/*
| منوی سایدبار (داده‌محور). کلیدهای label و title از lang/fa/ui.php می‌آیند.
| permission: نام مجوز Spatie (خالی = همه). route: نام مسیر؛ اگر وجود نداشته باشد '#'.
| active: الگوی routeIs برای برجسته‌سازی.
| آیتم‌های زیر نمونه‌اند و صفحه ندارند؛ فقط کتابخانه کامپوننت به صفحه واقعی وصل است.
*/
return [
    'groups' => [
        [
            'title' => 'ui.menu.overview',
            'items' => [
                ['label' => 'ui.menu.dashboard', 'icon' => 'dashboard', 'route' => 'dashboard', 'active' => 'dashboard'],
                [
                    'label' => 'ui.menu.analytics',
                    'icon' => 'chart',
                    'children' => [
                        ['label' => 'ui.menu.analytics_traffic', 'route' => 'analytics.traffic', 'active' => 'analytics.traffic'],
                        ['label' => 'ui.menu.analytics_conversion', 'route' => 'analytics.conversion', 'active' => 'analytics.conversion'],
                    ],
                ],
            ],
        ],
        [
            'title' => 'ui.menu.management',
            'items' => [
                ['label' => 'ui.menu.users', 'icon' => 'users', 'route' => 'users.index', 'active' => 'users.*'],
                ['label' => 'ui.menu.orders', 'icon' => 'clipboard', 'route' => 'orders.index', 'active' => 'orders.*'],
                ['label' => 'ui.menu.products', 'icon' => 'box', 'route' => 'products.index', 'active' => 'products.*'],
                ['label' => 'ui.menu.invoices', 'icon' => 'receipt', 'route' => 'invoices.index', 'active' => 'invoices.*'],
                ['label' => 'ui.menu.messages', 'icon' => 'lifebuoy', 'route' => 'messages.index', 'active' => 'messages.*', 'badge' => '۳'],
                ['label' => 'ui.menu.reports', 'icon' => 'file', 'route' => 'reports.index', 'active' => 'reports.*'],
            ],
        ],
        [
            'title' => 'ui.menu.system',
            'items' => [
                ['label' => 'ui.menu.roles', 'icon' => 'shield', 'route' => 'roles.index', 'active' => 'roles.*'],
                ['label' => 'ui.menu.settings', 'icon' => 'settings', 'route' => 'settings.index', 'active' => 'settings.*'],
                ['label' => 'ui.menu.ui_kit', 'icon' => 'palette', 'route' => 'preview.kit', 'active' => 'preview.kit'],
            ],
        ],
    ],
];
