<?php

namespace App\Http\Controllers\API;

use App\OrderProduct;
use App\SubscriptionHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Payment\PaypalRepository;
use App\Repositories\Payment\AuthorizeNetRepository;

class ProductSubscriptionController extends Controller
{
    /**
     * @param  Request $request
     * s
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        logger($request->all());

        switch ($request->segment(2)) {
            
            case 'paypal-subscription':

                $request->validate([
                    'resource' => 'required|array',
                ]);

                if ($request->event_type === 'PAYMENT.SALE.COMPLETED') {
                    $this->processPaypal($request);
                }

                if ($request->event_type === 'BILLING.SUBSCRIPTION.CANCELLED') {
                    $this->processPaypalCanceled($request);
                }

                return response()->json(true);
            
            case 'authorizenet-subscription':

                $request->validate([
                    'payload' => 'required|array',
                ]);

                $this->processAuth($request);

                return response()->json(true);
        }

        abort(404);
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function processPaypalCanceled(Request $request)
    {
        if (! array_key_exists('id', $request->resource)) {
            abort(404);
        }

        $agreementId = $request->resource['id'];

        $orderProduct = OrderProduct::where('subscription_id', $agreementId)->firstOrFail();

        $history = SubscriptionHistory::create([
            'transaction_id' => $agreementId,
            'response' => json_encode($request->all())
        ]);

        try {
            $history->status = true;
            $history->order_id = $orderProduct->order_id;
            $history->product_id = $orderProduct->product->id;
            $history->save();

            $orderProduct->is_subscription = false;
            $orderProduct->save();

            return response()->json($history->transaction_id);
        } catch (\Exception $e) {
            logger($e->getMessage());
            $history->status = false;
            $history->save();
            abort(404);
        }
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    public function processPaypal(Request $request)
    {
        if (! array_key_exists('billing_agreement_id', $request->resource)) {
            abort(404);
        }

        $agreementId = $request->resource['billing_agreement_id'];

        $history = SubscriptionHistory::create([
            'transaction_id' => $agreementId,
            'response' => json_encode($request->all())
        ]);

        try {
            $transaction = (new PaypalRepository())->getAgreement($agreementId);

            $history->transaction_details = $transaction->toArray();
            $history->save();

            $orderProduct = OrderProduct::where('subscription_id', $agreementId)
                ->where('is_subscription', true)
                ->firstOrFail();
            
            $history->order_id = $orderProduct->order_id;
            $history->product_id = $orderProduct->product->id;
            $history->save();

            $orderProduct->product->notify(new SubscriptionProcessedNotification($orderProduct));

            return response()->json($history->transaction_id);
        } catch (\Exception $e) {
            logger($e->getMessage());
            $history->status = false;
            $history->save();
            abort(404);
        }
    }

    /**
     * @param  Request $request
     * @return mixed
     */
    protected function processAuth(Request $request)
    {
        if (isset($request->payload['id']) && (int) $request->payload['id'] === 0) {
            abort(404);
        }

        $history = SubscriptionHistory::create([
            'transaction_id' => $request->payload['id'],
            'response' => json_encode($request->all())
        ]);

        $transaction = (new AuthorizeNetRepository())->getTransaction($history->transaction_id);

        try {
            logger($transaction);

            $history->transaction_details = $transaction;

            $orderProduct = OrderProduct::where('subscription_id', $history->transaction_details['subscription'] ?? null)
                ->where('is_subscription', true)
                ->firstOrFail();
            
            $history->product_id = $orderProduct->product->id;
            $history->save();

            $orderProduct->product->notify(new SubscriptionProcessedNotification($orderProduct));

            return response()->json($history->transaction_id);
        } catch (\Exception $e) {
            logger($e->getMessage());
            $history->status = false;
            $history->save();
        }
    }
}
