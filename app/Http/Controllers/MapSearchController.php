<?php

namespace App\Http\Controllers;

use App\Http\Requests\Map\MapSearchRequest;
use App\Support\ApiResponse;
use App\Support\MapSearch;
use App\Support\MapSearchFailed;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MapSearchController extends Controller
{
    public function __invoke(MapSearchRequest $request, MapSearch $search): JsonResponse
    {
        try {
            $items = $search->search(
                $request->string('q')->trim()->toString(),
                $request->filled('lat') ? (float) $request->input('lat') : null,
                $request->filled('lng') ? (float) $request->input('lng') : null,
            );
        } catch (MapSearchFailed $exception) {
            Log::warning('map search failed', ['reason' => $exception->reason, 'error' => $exception->getMessage()]);

            return ApiResponse::error(__('ui.labels.map_search_failed_'.$exception->reason), status: 502);
        }

        return response()->json(['success' => true, 'items' => $items]);
    }
}
