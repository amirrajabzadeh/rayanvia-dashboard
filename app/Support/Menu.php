<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;

/**
 * منوی داده‌محور از config/menu.php؛ فیلترشده بر اساس مجوزها.
 * پنهان کردن آیتم منو امنیت نیست؛ هر action باید Policy هم داشته باشد.
 */
final class Menu
{
    /**
     * @return list<array{title: string, items: list<array<string, mixed>>}>
     */
    public static function for(?Authenticatable $user): array
    {
        $groups = [];

        foreach (config('menu.groups', []) as $group) {
            $items = [];

            foreach ($group['items'] as $item) {
                $resolved = self::resolve($item, $user);

                if ($resolved !== null) {
                    $items[] = $resolved;
                }
            }

            if ($items !== []) {
                $groups[] = ['title' => __($group['title']), 'items' => $items];
            }
        }

        return $groups;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>|null
     */
    private static function resolve(array $item, ?Authenticatable $user): ?array
    {
        if (! self::allowed($item['permission'] ?? null, $user)) {
            return null;
        }

        $children = [];

        foreach ($item['children'] ?? [] as $child) {
            $resolved = self::resolve($child, $user);

            if ($resolved !== null) {
                $children[] = $resolved;
            }
        }

        if (isset($item['children']) && $children === []) {
            return null;
        }

        $active = self::isActive($item['active'] ?? null) || collect($children)->contains('active', true);

        return [
            'label' => __($item['label']),
            'icon' => $item['icon'] ?? 'dot',
            'url' => self::url($item['route'] ?? null),
            'active' => $active,
            'badge' => $item['badge'] ?? null,
            'children' => $children,
        ];
    }

    private static function allowed(?string $permission, ?Authenticatable $user): bool
    {
        if ($permission === null) {
            return true;
        }

        if ($user === null) {
            return app()->environment('local') && config('ui.preview');
        }

        return method_exists($user, 'can') && $user->can($permission);
    }

    private static function url(?string $route): string
    {
        return $route !== null && Route::has($route) ? route($route) : '#';
    }

    /**
     * @param  string|list<string>|null  $patterns
     */
    private static function isActive(string|array|null $patterns): bool
    {
        return $patterns !== null && request()->routeIs($patterns);
    }
}
