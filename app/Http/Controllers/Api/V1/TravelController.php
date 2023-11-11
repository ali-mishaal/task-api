<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TravelController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
       $travels = Travel::public()->paginate();

       return TravelResource::collection($travels);
    }
}
