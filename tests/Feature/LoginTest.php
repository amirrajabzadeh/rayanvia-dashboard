<?php

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
    RateLimiter::clear('admin@rayanvia.test|127.0.0.1');
});

it('renders the login page for guests', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee(__('ui.auth.title'));
});

it('prefills the demo credentials on the login page', function () {
    config(['ui.demo_login' => ['email' => 'demo@rayanvia.test', 'password' => 'demo-pass']]);

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('demo@rayanvia.test')
        ->assertSee(__('ui.auth.demo_notice'));
});

it('hides the demo credentials when disabled', function () {
    config(['ui.demo_login' => null]);

    $this->get(route('login'))
        ->assertOk()
        ->assertDontSee(__('ui.auth.demo_notice'));
});

it('logs a user in and returns the redirect target', function () {
    $user = User::factory()->create(['email' => 'admin@rayanvia.test']);

    $this->postJson(route('login.store'), ['email' => $user->email, 'password' => 'password'])
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertAuthenticatedAs($user);
});

it('rejects wrong credentials with 422', function () {
    User::factory()->create(['email' => 'admin@rayanvia.test']);

    $this->postJson(route('login.store'), ['email' => 'admin@rayanvia.test', 'password' => 'wrong'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->assertGuest();
});

it('validates required fields with 422', function () {
    $this->postJson(route('login.store'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('locks out after too many failed attempts', function () {
    User::factory()->create(['email' => 'admin@rayanvia.test']);

    foreach (range(1, 5) as $ignored) {
        $this->postJson(route('login.store'), ['email' => 'admin@rayanvia.test', 'password' => 'wrong']);
    }

    $this->postJson(route('login.store'), ['email' => 'admin@rayanvia.test', 'password' => 'password'])
        ->assertUnprocessable();

    $this->assertGuest();
});

it('redirects authenticated users away from the login page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));
});

it('logs a user out and rejects guests with a redirect', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();

    $this->post(route('logout'))->assertRedirect(route('login'));
});

it('sends guests away from the dashboard when preview mode is off', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});
