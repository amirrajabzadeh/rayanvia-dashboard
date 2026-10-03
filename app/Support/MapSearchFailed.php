<?php

namespace App\Support;

use RuntimeException;

/**
 * خطای سرویس جستجوی نقشه. reason برای نمایش پیام مناسب به کاربر است:
 * service_disabled (سرویس Search روی کلید نشان فعال نیست)، invalid_key، quota یا unavailable.
 */
final class MapSearchFailed extends RuntimeException
{
    public function __construct(string $message, public readonly string $reason = 'unavailable', ?\Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }

    /** کدهای خطای نشان: ۴۸۰ کلید نامعتبر، ۴۸۱ و ۴۸۲ سهمیه/نرخ، ۴۸۵ سرویس فعال‌نشده */
    public static function fromStatus(int $status, string $message, ?\Throwable $previous = null): self
    {
        $reason = match ($status) {
            485 => 'service_disabled',
            480, 483, 484 => 'invalid_key',
            481, 482, 429 => 'quota',
            default => 'unavailable',
        };

        return new self($message, $reason, $previous);
    }
}
