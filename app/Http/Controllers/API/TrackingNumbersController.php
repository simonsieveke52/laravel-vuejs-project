<?php

namespace App\Http\Controllers\API;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackingNumbersResource;

class TrackingNumbersController extends Controller
{
    /**
     * Show tracking numbers
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection response
     */
    public function show(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $order = Order::findOrFail($request->id);

        $order->loadMissing('trackingNumbers');

        return TrackingNumbersResource::collection($order->trackingNumbers);
    }
}
