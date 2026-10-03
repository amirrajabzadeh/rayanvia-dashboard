<?php

namespace App\Support;

/**
 * قالب‌بندی ارقام فارسی برای نمایش.
 */
final class Fa
{
    public static function digits(string|int|float $value): string
    {
        return strtr((string) $value, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }

    public static function number(int|float $value): string
    {
        return str_replace(',', '٬', self::digits(number_format($value)));
    }
}
