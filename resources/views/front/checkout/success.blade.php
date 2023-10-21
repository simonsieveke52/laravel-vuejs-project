@extends('layouts.front.simple')

@section('content')

	<div class="container">

		<div class="row">
			<div class="col-md-12">
				<h1 class="text-center mt-4 mb-3 h3">
					<strong>Thank you for your order!</strong>
				</h1>
			</div>
		</div>

		@include('front.invoices.invoice', [
			'order' => $order
		])

	</div>

@endsection

@section('js')
	<script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>
@endsection

@push('scripts')

<script>
	
	try {
		window.dataLayer.push({
            'event': 'Sale',
            'conversionValue': {{ number_format($order->subtotal, 2, '.', '') }},
            'transactionId': '{{ (string) $order->id }}'
        });
	} catch (e) {
		console.log(e)
	}

	try {

		window.dataLayer.push({
			'event': 'Purchase',
		  	'Category': 'Purchase',
		  	'Action': 'Checkout',
		  	'Value': {{ number_format($order->subtotal, 2, '.', '') }},
		  	'eventCategory': 'Purchase',
		  	'eventAction': 'Checkout',
		  	'eventValue': {{ number_format($order->subtotal, 2, '.', '') }},
		  	'ecommerce': {
			    'purchase': {
					'actionField': {
						'id': @json($order->id),
						'affiliation': @json(config('app.name')),
						'revenue': {{ number_format($order->subtotal, 2, '.', '') }},
						'tax': {{ number_format($order->tax, 2, '.', '') }},
						'shipping': {{ number_format($order->shipping_cost, 2, '.', '') }}
					},
					'products': [
						@foreach ($order->products as $product)
							{
								"id": @json($product->id),
								"name": @json($product->name),
								"category": @json($product->category->name ?? ''),
								"quantity": {{ $product->pivot->quantity }},
								"price": {{ number_format($product->price, 2, '.', '') }}
							}
							@if(! $loop->last)
							,
							@endif

						@endforeach
					]
			    }
		  	}
		});

	} catch(e) {
		console.log(e)
	}

</script>

@if (false)
	<script>
		try {
		  	window.renderOptIn = function() {
		    	window.gapi.load('surveyoptin', function() {
		      		window.gapi.surveyoptin.render({
						"merchant_id": 123262447,
						"order_id": @json($order->id),
						"email": @json($order->email),
						"delivery_country": "US",
						"estimated_delivery_date": @json(now()->addDay()->format('Y-m-d')),
						"products": [
		                  	@foreach ($order->products as $product)
			                    {
			                        "gtin": @json($product->upc)
			                    }
			                    @if(! $loop->last)
								,
								@endif
		                  	@endforeach
			            ]
		        	});
		    	});
		  	}
		} catch (e) {
			console.log(e)
		}
	</script>
@endif


@endpush