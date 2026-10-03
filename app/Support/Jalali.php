<?php

namespace App\Support;

use DateTimeInterface;
use IntlDateFormatter;

/**
 * تبدیل تاریخ میلادی ذخیره‌شده به شمسی برای نمایش. تنها نقطه تبدیل در پروژه.
 */
final class Jalali
{
    public static function format(?DateTimeInterface $date, string $pattern = 'yyyy/MM/dd'): string
    {
        if ($date === null) {
            return '';
        }

        $formatter = new IntlDateFormatter(
            'fa_IR@calendar=persian',
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            $date->getTimezone(),
            IntlDateFormatter::TRADITIONAL,
            $pattern,
        );

        return (string) $formatter->format($date);
    }

    public static function long(?DateTimeInterface $date): string
    {
        return self::format($date, 'EEEE d MMMM yyyy');
    }
}
