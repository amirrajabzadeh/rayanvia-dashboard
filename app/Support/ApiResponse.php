<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * قالب پاسخ استاندارد JSON: { success, message, redirect } و در خطا { success:false, message, errors }.
 */
final class ApiResponse
{
    public static function success(?string $message = null, ?string $redirect = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'redirect' => $redirect,
        ], $status);
    }

    /**
     * @param  array<string, list<string>>  $errors
     */
    public static function error(string $message, array $errors = [], int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
