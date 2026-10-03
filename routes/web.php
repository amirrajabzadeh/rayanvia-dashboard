<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapSearchController;
use App\Http\Controllers\PreviewController;
use Illuminate\Support\Facades\Route;

/*
| صفحه اصلی: مهمان همیشه به ورود می‌رود؛ کاربر واردشده به داشبورد.
*/
Route::get('/', fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:20,1')->name('login.store');
});

/* داشبورد: در حالت پیش‌نمایش بدون ورود باز می‌شود، وگرنه فقط برای کاربر واردشده. */
Route::get('/dashboard', DashboardController::class)
    ->middleware(app()->isLocal() && config('ui.preview') ? [] : ['auth'])
    ->name('dashboard');

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
| حالت پیش‌نمایش UI: کتابخانه کامپوننت بدون احراز هویت.
| فقط در محیط local و با UI_PREVIEW=true فعال می‌شود؛ پیش از ساخت محصول واقعی حذف می‌شود.
*/
if (app()->isLocal() && config('ui.preview')) {
    Route::get('/preview/kit', [PreviewController::class, 'kit'])->name('preview.kit');
    Route::get('/preview/table', [PreviewController::class, 'sampleTable'])->name('preview.table');
    // جستجوی نقشه: پس از پیاده‌سازی احراز هویت باید پشت middleware('auth') و Policy برود
    Route::get('/map/search', MapSearchController::class)->middleware('throttle:30,1')->name('map.search');
}
