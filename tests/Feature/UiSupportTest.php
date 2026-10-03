<?php

use App\Support\ApiResponse;
use App\Support\Fa;
use App\Support\Jalali;
use App\Support\Menu;

it('converts digits and formats numbers in persian', function () {
    expect(Fa::digits('0912'))->toBe('۰۹۱۲')
        ->and(Fa::number(1250000))->toBe('۱٬۲۵۰٬۰۰۰');
});

it('formats a gregorian date as jalali', function () {
    expect(Jalali::format(new DateTimeImmutable('2026-03-21', new DateTimeZone('UTC'))))->toBe('۱۴۰۵/۰۱/۰۱');
});

it('builds api responses in the standard shape', function () {
    $ok = ApiResponse::success('ok', '/dashboard')->getData(true);
    $fail = ApiResponse::error('bad', ['name' => ['required']])->getData(true);

    expect($ok)->toBe(['success' => true, 'message' => 'ok', 'redirect' => '/dashboard'])
        ->and($fail['success'])->toBeFalse()
        ->and($fail['errors'])->toBe(['name' => ['required']]);
});

it('resolves the sidebar menu from config for guests without permissions', function () {
    $groups = Menu::for(null);

    expect($groups)->not->toBeEmpty()
        ->and($groups[0]['items'][0]['label'])->toBe('داشبورد');
});
