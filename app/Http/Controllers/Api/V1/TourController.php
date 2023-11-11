<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourFilterRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TourController extends Controller
{
    public function index(Travel $travel, TourFilterRequest $request): AnonymousResourceCollection
    {
        $tours = $travel->tours()
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->whereDate('start_date', '>=', $request->dateFrom);
            })->when($request->dateTo, function ($query) use ($request) {
                $query->whereDate('start_date', '<=', $request->dateTo);
            })->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom);
            })->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo);
            })->when($request->sortBy, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder ?? 'asc');
            })
            ->orderBy('start_date')
            ->paginate();

        return TourResource::collection($tours);
    }
}
