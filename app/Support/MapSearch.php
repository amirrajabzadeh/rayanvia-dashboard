<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * جستجوی مکان برای کامپوننت نقشه.
 * با کلید سرویس نشان از Search API نشان استفاده می‌شود؛ بدون کلید از Nominatim (OpenStreetMap).
 * کلید فقط سمت سرور می‌ماند و نتیجه‌ها کوتاه‌مدت کش می‌شوند تا سهمیه سرویس هدر نرود.
 */
final class MapSearch
{
    private const CACHE_SECONDS = 600;

    /**
     * @return list<array{title: string, address: string, lat: float, lng: float}>
     *
     * @throws MapSearchFailed
     */
    public function search(string $term, ?float $lat = null, ?float $lng = null): array
    {
        $center = [$lat ?? config('ui.map.center')[0], $lng ?? config('ui.map.center')[1]];
        $key = 'map-search:'.sha1(mb_strtolower($term).'|'.round($center[0], 2).'|'.round($center[1], 2).'|'.($this->usesNeshan() ? 'n' : 'o'));

        return Cache::remember($key, self::CACHE_SECONDS, fn () => $this->usesNeshan()
            ? $this->viaNeshan($term, $center)
            : $this->viaNominatim($term));
    }

    private function usesNeshan(): bool
    {
        return filled(config('services.neshan.api_key'));
    }

    /**
     * @param  array{0: float, 1: float}  $center
     * @return list<array{title: string, address: string, lat: float, lng: float}>
     */
    private function viaNeshan(string $term, array $center): array
    {
        $query = json_encode([
            'term' => $term,
            'center' => ['latitude' => $center[0], 'longitude' => $center[1]],
        ], JSON_UNESCAPED_UNICODE);

        $items = $this->get(
            rtrim(config('services.neshan.base_url'), '/').'/v3/search',
            ['q' => $query],
            ['Api-Key' => config('services.neshan.api_key')],
        )['items'] ?? [];

        return collect($items)
            ->filter(fn ($item) => isset($item['location']['x'], $item['location']['y']))
            ->map(fn (array $item): array => [
                'title' => (string) ($item['title'] ?? ''),
                'address' => trim(($item['region'] ?? '').' '.($item['neighbourhood'] ?? '').' '.($item['address'] ?? '')),
                'lat' => (float) $item['location']['y'],
                'lng' => (float) $item['location']['x'],
            ])
            ->take(10)
            ->values()
            ->all();
    }

    /**
     * @return list<array{title: string, address: string, lat: float, lng: float}>
     */
    private function viaNominatim(string $term): array
    {
        $rows = $this->get(
            rtrim(config('services.nominatim.base_url'), '/').'/search',
            ['q' => $term, 'format' => 'jsonv2', 'limit' => 10, 'accept-language' => 'fa'],
        );

        return collect($rows)
            ->filter(fn ($row) => isset($row['lat'], $row['lon']))
            ->map(fn (array $row): array => [
                'title' => ($row['name'] ?? '') !== '' ? (string) $row['name'] : (string) strtok((string) ($row['display_name'] ?? ''), ','),
                'address' => (string) ($row['display_name'] ?? ''),
                'lat' => (float) $row['lat'],
                'lng' => (float) $row['lon'],
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, string>  $headers
     * @return array<mixed>
     *
     * @throws MapSearchFailed
     */
    private function get(string $url, array $query, array $headers = []): array
    {
        try {
            return Http::timeout((int) config('services.neshan.timeout'))
                ->withUserAgent(config('app.name'))
                ->withHeaders($headers)
                ->acceptJson()
                ->get($url, $query)
                ->throw()
                ->json() ?? [];
        } catch (RequestException $exception) {
            throw MapSearchFailed::fromStatus($exception->response->status(), $exception->getMessage(), $exception);
        } catch (ConnectionException $exception) {
            throw new MapSearchFailed($exception->getMessage(), previous: $exception);
        }
    }
}
