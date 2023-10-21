<?php

namespace App\Http\Controllers\Customer;

use App\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Payment\PaypalRepository;
use App\Repositories\Payment\AuthorizeNetRepository;

class ProductSubscriptionController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderProduct  $orderProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderProduct $orderProduct)
    {
        if ((int) $orderProduct->is_subscription === 0) {
            abort(404);
        }

        if ((int) $orderProduct->subscription_id === 0) {
            abort(404);
        }

        if ((int) $orderProduct->is_active_subscription === 0) {
            abort(404);
        }

        $order = $orderProduct->order;

        if ($order->customer_id !== $this->loggedUser()->id) {
            abort(404);
        }

        try {
            switch ($order->payment_method) {
                case 'credit_card':
                    (new AuthorizeNetRepository())->cancelSubscription($orderProduct->subscription_id);
                    break;
                
                case 'paypal':
                    (new PaypalRepository())->suspend($orderProduct->subscription_id);
                    break;
                
                default:
                    throw new \Exception("Error Processing Request");
            }

            $orderProduct->canceled_at = date('Y-m-d H:i:s');
            $orderProduct->is_active_subscription = false;
            $orderProduct->save();

            return back()->with('message', 'Subscription canceled successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, try again later');
        }

        abort(404);
    }
}
