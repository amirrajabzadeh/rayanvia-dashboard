<?php

use App\Models\User;

/*
 * مسیرهای پیش‌نمایش فقط در local + UI_PREVIEW ثبت می‌شوند؛ پس برنامه را با همان محیط دوباره می‌سازیم
 * و پس از هر تست به محیط تست برمی‌گردانیم.
 */
function setPreviewEnvironment(string $env, string $preview): void
{
    foreach (['APP_ENV' => $env, 'UI_PREVIEW' => $preview] as $name => $value) {
        putenv("{$name}={$value}");
        $_ENV[$name] = $_SERVER[$name] = $value;
    }
}

beforeEach(function () {
    setPreviewEnvironment('local', 'true');
    $this->refreshApplication();
});

afterEach(fn () => setPreviewEnvironment('testing', 'false'));

it('redirects guests from the home page to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
});

it('redirects signed-in users from the home page to the dashboard', function () {
    $this->actingAs((new User)->forceFill(['id' => 1]))
        ->get('/')
        ->assertRedirect(route('dashboard'));
});

it('renders the component library with the sample menu', function () {
    $this->get(route('preview.kit'))
        ->assertOk()
        ->assertSee(__('ui.kit.title'))
        ->assertSee(__('ui.menu.analytics'))
        ->assertSee(__('ui.menu.users'));
});

it('renders the login page', function () {
    $this->get(route('login'))->assertOk()->assertSee(__('ui.auth.title'));
});

it('filters the sample table by status and search', function () {
    $this->get(route('preview.table', ['status' => 'active']))
        ->assertOk()
        ->assertSee(__('ui.sample.statuses.active'))
        ->assertDontSee(__('ui.sample.statuses.closed').'</span>', false);

    $this->get(route('preview.table', ['search' => 'no-such-record']))
        ->assertOk()
        ->assertSee(__('ui.table.empty'));
});

it('renders the dashboard with sample data in preview mode', function () {
    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee(__('ui.dashboard.revenue_title'))
        ->assertSee(__('ui.dashboard.orders_title'))
        ->assertSee('O-4821');
});
