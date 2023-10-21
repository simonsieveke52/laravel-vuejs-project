@extends('layouts.front.app')

@section('content')

<div class="container">
    <div class="bg-white pb-5">

        <div class="alert mb-0 pt-4 pb-5" style="min-height: 400px;">
            <div class="row mt-3 mb-4">
                <div class="col-12">
                    <h1 class="h2 pb-2 border-bottom">Account information for {{ $customer->name }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-customer-tabs" role="tablist" aria-orientation="vertical">

                        <a 
                            class="nav-link active" 
                            data-toggle="pill" 
                            href="#v-pills-account" 
                            role="tab" 
                            aria-controls="v-pills-account" 
                            aria-selected="true"
                        >
                            Account details
                        </a>

                        <a 
                            class="nav-link"
                            data-toggle="pill" 
                            href="#v-pills-addresses" 
                            role="tab" 
                            aria-controls="v-pills-addresses" 
                            aria-selected="true"
                        >
                            Addresses
                        </a>

                        <a 
                            class="nav-link"
                            data-toggle="pill" 
                            href="#v-pills-orders" 
                            role="tab" 
                            aria-controls="v-pills-orders" 
                            aria-selected="true"
                        >
                            Order history
                        </a>

                        <a 
                            class="nav-link"
                            data-toggle="pill" 
                            href="#v-subscriptions" 
                            role="tab" 
                            aria-controls="v-subscriptions" 
                            aria-selected="true"
                        >
                            Subscriptions
                        </a>

                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">

                        <div 
                            class="tab-pane show active" 
                            id="v-pills-account" 
                            role="tabpanel" 
                            aria-labelledby="v-pills-account"
                        >
                            <div class="pb-4">
                                
                                <h4 class="h3 mb-4">
                                    <span>Account details</span>
                                </h4>

                                <p class="p-0 m-0">
                                    <strong>Full Name:</strong> <span class="text-capitalize">{{ $customer->name }}</span>
                                </p>
                                <p class="p-0 m-0">
                                    <strong>Phone:</strong> <a href="tel:{{ $customer->phone }}">{{ formatPhone($customer->phone) }}</a>
                                </p>
                                <p class="p-0 m-0">
                                    <strong>Email:</strong> <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                </p>
                                <p class="p-0 m-0">
                                    <strong>Member Since:</strong> <span class="text-capitalize">{{ $customer->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>


                        <div 
                            class="tab-pane" 
                            id="v-pills-orders" 
                            role="tabpanel" 
                            aria-labelledby="v-pills-orders"
                        >
                            <div class="pb-4">
                                <h4 class="h3 mb-4">
                                    <span>Order history</span>
                                </h4>
                                @if (!$orders->isEmpty())
                                    
                                    <table class="table table-borderless text-capitalize">
                                        <thead>
                                            <tr>
                                                <th class="pt-1 pb-3 px-1">Order #</th>
                                                <th class="pt-1 pb-3 px-1">Created</th>
                                                <th class="pt-1 pb-3 px-1">Shipping</th>
                                                <th class="pt-1 pb-3 px-1">Total</th>
                                                <th class="pt-1 pb-3 px-1">Status</th>
                                                <th class="pt-1 pb-3 px-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td class="py-1 px-1">{{ $order->id }}</td>
                                                    <td class="py-1 px-1">
                                                        <p class="m-0">
                                                            {{ $order->created_at->format('m/d/Y h:iA') }}
                                                        </p>
                                                    </td>
                                                    <td class="py-1 px-1 text-capitalize">
                                                        @php
                                                            $shipping = !is_null($order->shipping_id) 
                                                                ? $order->shipping->name 
                                                                : $order->carriers->first();

                                                            if (! is_null($shipping) && $shipping instanceof \App\Carrier) {
                                                                $shipping = $shipping->service_name;
                                                            }
                                                        @endphp

                                                        {{ $shipping ?? '' }}
                                                    </td>
                                                    <td class="py-1 px-1">
                                                        {{ config('cart.currency_symbol') . number_format($order->total, 2) }}
                                                    </td>
                                                    <td class="py-1 px-1">
                                                        <span class="badge px-2 py-1" style="background-color: {{ $order->orderStatus->color ?? '#326900' }}; color: #fff;">
                                                            {{ $order->orderStatus->name }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right py-1 px-1">
                                                        <a href="{{ route('invoice.show', $order) }}">Invoice</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-success mb-1">
                                        You haven't made any orders yet.
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div 
                            class="tab-pane" 
                            id="v-pills-addresses" 
                            role="tabpanel" 
                            aria-labelledby="v-pills-addresses"
                        >
                            <div class="pb-4 mb-4">

                                <div class="mb-4">
                                    <h4 class="h3">
                                        <span>Addresses</span>
                                    </h4>
                                    <h5 class="h4 mt-4">
                                        <span>Billing Addresses</span>
                                    </h5>
                                </div>


                                @if (!$billingAddresses->isEmpty())
                                    <ul class="list-group mb-3">

                                        @foreach ($billingAddresses as $address)
                                            <li class="row">
                                                <div class="col-11">
                                                    <address-item-component 
                                                        class="rounded mb-2"
                                                        selected-address-id="{{ session('billing_address', false) }}" 
                                                        address-type="{{ $address->type }}" 
                                                        :city="{{ json_encode($address->city) }}"
                                                        :state="{{ json_encode($address->state) }}"
                                                        :address="{{ json_encode($address) }}"
                                                    >
                                                    </address-item-component>
                                                </div>
                                                <div class="col-1 d-flex align-items-center">
                                                    <form action="{{ route('customer.address.destroy', [$customer->id, $address->id]) }}" method="post" class="form-horizontal">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="_method" value="delete">
                                                        <div class="btn-group">
                                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i><span class="sr-only"> Delete</span></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                        
                                    </ul>
                                @else
                                    <div class="mb-4">
                                        <p class="h4 text-highlight">You don't have any billing addresses! Please create one now.</p>
                                        <a href="{{ route('customer.address.create', $customer) }}" class="btn btn-highlight">New address</a>
                                    </div>
                                @endif
                                @if (!$shippingAddresses->isEmpty())

                                    <div class="mb-4 mt-4">
                                        <h5 class="h4 mb-4">
                                            <span>Shipping Addresses</span>
                                        </h5>

                                        <ul class="list-group">

                                            @foreach ($shippingAddresses as $address)
                                            <li class="row">
                                                <div class="col-11">
                                                    <address-item-component 
                                                        class="rounded mb-2"
                                                        selected-address-id="{{ session('billing_address', false) }}" 
                                                        address-type="{{ $address->type }}" 
                                                        :city="{{ json_encode($address->city) }}"
                                                        :state="{{ json_encode($address->state) }}"
                                                        :address="{{ json_encode($address) }}"
                                                    >
                                                    </address-item-component>
                                                </div>
                                                <div class="col-1 d-flex align-items-center">
                                                    <form action="{{ route('customer.address.destroy', [$customer->id, $address->id]) }}" method="post" class="form-horizontal">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="_method" value="delete">
                                                        <div class="btn-group">
                                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i><span class="sr-only"> Delete</span></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </li>
                                            @endforeach
                                            
                                        </ul>
                                    </div>

                                @endif

                                <div class="text-right mb-4">
                                    <a href="{{ route('customer.address.create', $customer) }}" class="btn btn-highlight">New address</a>
                                </div>

                            </div> 
                        </div>

                        <div 
                            class="tab-pane"
                            id="v-subscriptions"
                            role="tabpanel"
                            aria-labelledby="v-subscriptions"
                        >
                            <div class="pb-4 mb-4">
                                <h4 class="h3 mb-4">
                                    <span>Subscriptions</span>
                                </h4>

                                <div>
                                    <div class="row">

                                        @if ($subscriptions->isEmpty())
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    You don't have any enabled subscriptions yet.
                                                </div>
                                            </div>
                                        @endif

                                        @foreach ($subscriptions as $subscription)
                                            <div class="col-12 col-sm-6 col-lg-4">
                                                <div class="pb-4 h-100 px-3 mb-3">
                                                    <img 
                                                        class="img-fluid img-responsive w-auto d-block m-auto min-w-200px max-h-300px h-auto" 
                                                        src="{{ asset(str_replace('//', '/', '/storage/' . $subscription->product->main_image)) }}" 
                                                        alt="{{ $subscription->product->name }}"
                                                    >
                                                    <div class="text-center">
                                                        <div>
                                                            <h2 class="h5">{{ $subscription->product->name }}</h2>

                                                            <p class="mb-0"><strong>Price:</strong>&nbsp;${{ number_format($subscription->total, 2) }}</p>

                                                            <p>
                                                                <strong>Subscription:</strong>
                                                                @if ($subscription->order->apiResponses->isEmpty())
                                                                    <span class="badge badge-success">New</span>
                                                                @elseif ( (int) $subscription->is_active_subscription === 1)
                                                                    <span class="badge badge-success">Enabled</span>
                                                                @else
                                                                    <span class="badge badge-danger">Canceled</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group d-flex flex-row w-100 align-items-center justify-content-around">

                                                        @if ( (int) $subscription->is_active_subscription === 1)
                                                            <a 
                                                                href="{{ route('account-subscription.destroy', $subscription) }}" 
                                                                class="btn btn-danger btn-block py-2 text-uppercase text-nowrap my-0"
                                                            >
                                                                Cancel
                                                            </a>
                                                        @endif

                                                        <a href="{{ route('product.show', $subscription->product->slug) }}" class="btn btn-light my-0 btn-block py-2 text-uppercase text-nowrap">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="pb-5">&nbsp;</div>


    </div>
</div>

@endsection
