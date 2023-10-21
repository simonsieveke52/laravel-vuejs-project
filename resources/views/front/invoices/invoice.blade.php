<div class="row bg-white mb-0 rounded-0 py-4">
	<div class="col-12">
		<div class="row mt-4 mb-3">
			<div class="col">
				<h2 class="h5">
				<strong>Order:</strong> <kbd>#{{ $order->id }}</kbd>
				</h2>
			</div>
			<div class="col">
				<h3  class="text-right h5">
				<strong>Order Date:</strong> {{ $order->created_at->format('m/d/Y') }}
				</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<ul class="list-group">
					<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-secondary-3 border-secondary">
						<span class="flex-sm-grow-1 px-2">Product</span>
						<span class="min-w-sm-100px text-right">Quantity</span>
						<span class="min-w-sm-100px text-right">Price</span>
					</li>

					@php
						$products = $order->products
					@endphp

					@foreach ($products as $product)

						@if ( is_array($product->pivot->options) )
							@php
								$product->pivot->options = json_encode($product->pivot->options);
							@endphp
						@endif

						@php
							$product->pivot->options = json_decode($product->pivot->options, true);
						@endphp
						
						<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-light border-secondary">
							<a class="min-w-sm-100px text-center" href="{{ route('product.show', $product) }}">
								<img src="{{ asset( Croppa::url('/storage/' . $product->pivot->options['main_image'], 100, 100, ['resize', 'upscale' => true]) ) }}" class="img-fluid">
							</a>
							<span class="flex-sm-grow-1 px-3 d-none d-sm-block">
								<a class="mb-1 font-weight-normal text-secondary-5" href="{{ route('product.show', $product) }}">
									@if ( (int) $product->pivot->is_subscription === 1)
										(Subscription) -
									@endif
									{{ $product->name }}
								</a>
							</span>
							<span class="min-w-sm-100px text-right text-secondary-5">
								{{ $product->pivot->quantity }}
							</span>
							<span class="min-w-sm-100px text-right text-secondary-5">
								{{ config('cart.currency_symbol') . number_format($product->pivot->price, 2) }}
							</span>
						</li>

					@endforeach
					
				</ul>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-12">
				<div class="border-secondary rounded bg-lighter p-3">
					
					<div class="row">
						<div class="col-md-4 col-12">

							@if ($order->billing_address)
								<h5 class="py-1 h5">Billing Address</h5>
								<p class="mb-0">
									{{ $order->name }} <br>
									{{ $order->billing_address->address_1 }} <br>
									@if( isset($order->billing_address->address_2) && trim($order->billing_address->address_2) !== '' )
									{{ $order->billing_address->address_2 }} <br>
									@endif
									@if ($order->billing_address->city)
									{{ ucfirst($order->billing_address->city) }},
									@endif
									{{ strtoupper($order->billing_address->state->abv) }}
									{{ $order->billing_address->zipcode }}
								</p>
							@endif
							@if ($order->shipping_address)
							<div class="mt-4 mb-3">
								<h5 class="py-1 h5">Shipping Address</h5>
								<p class="mb-0">
									{{ $order->name }} <br>
									{{ $order->shipping_address->address_1 }} <br>
									@if( isset($order->shipping_address->address_2) && trim($order->shipping_address->address_2) !== '' )
									{{ $order->shipping_address->address_2 }} <br>
									@endif
									@if ($order->shipping_address->city)
									{{ ucfirst($order->shipping_address->city) }},
									@endif
									{{ strtoupper($order->shipping_address->state->abv) }}
									{{ $order->shipping_address->zipcode }}
								</p>
							</div>
							@endif
						</div>
						<div class="col-md-4 offset-md-4 col-12 offset-0">
							<h5 class="py-1 h5">Totals</h5>
							<table class="table mb-0">
								<tr>
									<td class="p-1 border-0">Subtotal</td>
									<td class="p-1 border-0">{{ config('cart.currency_symbol') . number_format($order->subtotal, 2) }}</td>
								</tr>
								@if($order->discount_amount > 0)
								<tr>
									<td class="p-1 border-0">Discount</td>
									<td class="p-1 border-0">{{ $order->discount_amount }}</td>
								</tr>
								@endif
								<tr>
									<td class="p-1 border-0">Tax</td>
									<td class="p-1 border-0">{{ config('cart.currency_symbol') . number_format($order->tax, 2) }}</td>
								</tr>
								<tr>
									<td class="p-1 border-0">Shipping</td>
									<td class="p-1 border-0">{{ config('cart.currency_symbol') . number_format($order->shipping_cost, 2) }}</td>
								</tr>
								
								<tr>
									<td class="p-1 border-0">Grand Total</td>
									<td class="p-1 border-0"><strong>{{ config('cart.currency_symbol') . number_format($order->total, 2) }}</strong></td>
								</tr>
							</table>

							<h6 class="mt-4 mb-1 pt-1 h5">Payment</h6>
							@if ($order->payment_method == 'paypal')
							<p class="mb-0">
								Paid using Paypal.
							</p>
							@else
							<p class="mb-0">
								{{ ucwords($order->card_type) }}: XXXX-XXXX-XXXX-{{ $order->LastCCDigits }}
							</p>
							@endif

							@if ($order->confirmed_at)
								<p class="m-0">
									<small class="text-muted">
									Confirmed {{ $order->confirmed_at->diffForHumans() }}
									</small>
								</p>
							@endif
						</div>
						
					</div>
				</div>
				
			</div>
		</div>
		
	</div>
</div>