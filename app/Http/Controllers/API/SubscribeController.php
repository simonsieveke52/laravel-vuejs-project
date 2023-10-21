<?php

namespace App\Http\Controllers\API;

use App\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Events\SubscriberCreateEvent;
use App\Http\Requests\SubscribeRequest;

class SubscribeController extends Controller
{
    /**
     * Store new email
     *
     * @param  SubscribeRequest $request [description]
     * @return JsonResponse
     */
    public function store(SubscribeRequest $request)
    {
        // create new subscription
        $subscriber = Subscriber::create([
            'email' => $request->email,
        ]);

        // need to send email to admin
        event(new SubscriberCreateEvent($subscriber));

        // return json response
        return response()->json(true);
    }
}
