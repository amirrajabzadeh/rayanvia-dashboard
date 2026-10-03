<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/*
 * مسیر جستجو فقط در حالت پیش‌نمایش (local + UI_PREVIEW) ثبت می‌شود؛ پس برنامه را با همان محیط دوباره می‌سازیم
 * و پس از هر تست به محیط تست برمی‌گردانیم.
 */
function bootInPreviewMode(): void
{
    foreach (['APP_ENV' => 'local', 'UI_PREVIEW' => 'true'] as $name => $value) {
        putenv("{$name}={$value}");
        $_ENV[$name] = $_SERVER[$name] = $value;
    }
}

beforeEach(function () {
    bootInPreviewMode();
    $this->refreshApplication();
    Cache::flush();
});

afterEach(function () {
    foreach (['APP_ENV' => 'testing', 'UI_PREVIEW' => 'false'] as $name => $value) {
        putenv("{$name}={$value}");
        $_ENV[$name] = $_SERVER[$name] = $value;
    }
});

function neshanResponse(): array
{
    return [
        'count' => 2,
        'items' => [
            ['title' => 'میدان آزادی', 'address' => 'میدان آزادی', 'region' => 'مشهد، خراسان رضوی', 'neighbourhood' => 'آزادی', 'location' => ['x' => 59.5452, 'y' => 36.3326]],
            ['title' => 'بدون مختصات', 'address' => 'x'],
        ],
    ];
}

it('returns normalized places from the neshan search api', function () {
    config(['services.neshan.api_key' => 'service.test']);
    Http::fake(['api.neshan.org/v3/search*' => Http::response(neshanResponse())]);

    $this->getJson(route('map.search', ['q' => 'میدان آزادی', 'lat' => 36.29, 'lng' => 59.6]))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'items')
        ->assertJsonPath('items.0.title', 'میدان آزادی')
        ->assertJsonPath('items.0.lat', 36.3326)
        ->assertJsonPath('items.0.lng', 59.5452);

    Http::assertSent(fn ($request) => $request->hasHeader('Api-Key', 'service.test')
        && str_contains(urldecode($request->url()), '"term":"میدان آزادی"'));
});

it('never exposes the service key to the client', function () {
    config(['services.neshan.api_key' => 'service.secret-key']);
    Http::fake(['api.neshan.org/*' => Http::response(neshanResponse())]);

    $this->getJson(route('map.search', ['q' => 'آزادی']))
        ->assertOk()
        ->assertDontSee('service.secret-key');
});

it('falls back to nominatim when no neshan key is configured', function () {
    config(['services.neshan.api_key' => null]);
    Http::fake(['nominatim.openstreetmap.org/*' => Http::response([
        ['name' => 'Azadi', 'display_name' => 'Azadi, Mashhad', 'lat' => '36.3', 'lon' => '59.5'],
    ])]);

    $this->getJson(route('map.search', ['q' => 'azadi']))
        ->assertOk()
        ->assertJsonPath('items.0.lat', 36.3);

    Http::assertNotSent(fn ($request) => str_contains($request->url(), 'neshan'));
});

it('validates the search term and coordinates', function (array $query, string $field) {
    $this->getJson(route('map.search', $query))
        ->assertUnprocessable()
        ->assertJsonValidationErrors($field);
})->with([
    'missing term' => [[], 'q'],
    'too short' => [['q' => 'a'], 'q'],
    'too long' => [['q' => str_repeat('a', 101)], 'q'],
    'latitude out of range' => [['q' => 'azadi', 'lat' => 120], 'lat'],
    'longitude not numeric' => [['q' => 'azadi', 'lng' => 'x'], 'lng'],
]);

it('answers 502 with a friendly message when the provider fails', function () {
    config(['services.neshan.api_key' => 'service.test']);
    Http::fake(['api.neshan.org/*' => Http::response(['status' => 'ERROR', 'code' => 485], 403)]);

    $this->getJson(route('map.search', ['q' => 'آزادی']))
        ->assertStatus(502)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', __('ui.labels.map_search_failed_unavailable'));
});

it('tells the user why the provider rejected the key', function (int $status, string $messageKey) {
    config(['services.neshan.api_key' => 'service.test']);
    Http::fake(['api.neshan.org/*' => Http::response(['status' => 'ERROR', 'code' => $status], $status)]);

    $this->getJson(route('map.search', ['q' => 'آزادی']))
        ->assertStatus(502)
        ->assertJsonPath('message', __('ui.labels.'.$messageKey));
})->with([
    'search service not enabled on the key' => [485, 'map_search_failed_service_disabled'],
    'invalid key' => [480, 'map_search_failed_invalid_key'],
    'quota exceeded' => [481, 'map_search_failed_quota'],
]);

it('caches identical searches so the provider is called once', function () {
    config(['services.neshan.api_key' => 'service.test']);
    Http::fake(['api.neshan.org/*' => Http::response(neshanResponse())]);

    $this->getJson(route('map.search', ['q' => 'آزادی']))->assertOk();
    $this->getJson(route('map.search', ['q' => 'آزادی']))->assertOk();

    Http::assertSentCount(1);
});

it('throttles excessive searches', function () {
    config(['services.neshan.api_key' => 'service.test']);
    Http::fake(['api.neshan.org/*' => Http::response(neshanResponse())]);

    foreach (range(1, 30) as $i) {
        $this->getJson(route('map.search', ['q' => 'آزادی']))->assertOk();
    }

    $this->getJson(route('map.search', ['q' => 'آزادی']))->assertStatus(429);
});
