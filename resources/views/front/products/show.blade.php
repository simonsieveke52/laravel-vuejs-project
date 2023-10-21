@extends('layouts.front.product.master')

@section('page-content')

<section class="product__buy-box pt-lg-2 mb-0" style="min-height: 700px;">

    <product-page-component class="row" :product="{{ json_encode($product) }}" :options="{{ json_encode($options ?? []) }}" :start-page="{{ (int) request()->input('page', 1) }}">

    	@if (isset($parentCategories))
	    	<template v-slot:breadcrumb>
				<div class="row py-2 mt-1 py-md-0 my-md-3">
				    <div class="col-12 font-size-0-85rem">
				        <section class="section section--breadcrumb">
				            <div class="text-secondary-5">
				                
				                <a href="/">Home</a>

				                @foreach ($parentCategories as $category)
				                    <a class="text-nowrap" href="{{ route('category.filter', $category) }}">
				                        <span class="px-1">/</span> {{ $category->name }}
				                    </a>
				                @endforeach

				                @if (isset($product))
							        <a href="{{ route('product.show', $product->slug) }}" class="d-none d-md-inline-block text-dark-2">
							            <span class="px-1">/</span> {{ \Illuminate\Support\Str::limit($product->name, 25) }}
							        </a>
							    @endif

				            </div>
				        </section>
				    </div>
				</div>
			</template>
		@endif

		<template v-slot:shipping-label>
			<div class="my-4">
				<div class="form-group">
					<span class="font-weight-bolder text-dark mb-1 d-block">Shipping:</span>
					<div class="text-muted-3 font-family-open-sans text-muted-3">
						Qualifies for <span class="font-weight-bolder">Free Shipping on orders over ${{ number_format(setting('free_shipping.threshold', 35), 2) }}</span>
					</div>
				</div>
			</div>
		</template>

		<template v-slot:subscription-label>
			{!! str_replace('{discount}', setting('subscription.discount', 10), setting('subscription.discount_label', '<span class="text-dark-3 font-weight-bolder">Save {discount}%</span> when you subscribe')) !!}
		</template>

    </product-page-component>
    
</section>

@if (! $relatedProducts->isEmpty())

<div class="related-products bg-white py-5">
	<div class="py-4">
		<div class="row">
			<div class="col-12" style="overflow: hidden;">

				<h2 class="font-weight-light text-dark text-center h3 mb-3">You may  <span class="font-weight-bold">also like.</span></h2>

				<carousel :per-page-custom="[[400, 1], [500, 2], [600, 3], [992, 4], [1399, 5]]" :navigation-enabled="false" :pagination-enabled="false" :autoplay="true" :scroll-per-page="true">
					@foreach ($relatedProducts as $related)
						<slide>
							<product-component 
								class="pb-4 h-100 px-3"
								product-class="img-fluid img-responsive w-auto d-block m-auto min-w-200px max-h-170px h-auto"
								view-type="custom" 
								:product="{{ json_encode($related) }}"
							>		
							</product-component>
						</slide>
					@endforeach
				</carousel>
			</div>
		</div>
	</div>
</div>

<product-modal-component></product-modal-component>
	
@endif

<script type="application/ld+json">
	{
	  	"@context": "http://schema.org",
	  	"@type": "Product",
	  	"description": "{{ $product->short_description }}",
	  	"name": "{{ $product->name }}",
	  	"image": "{{ asset($product->main_image) }}",
	  	"offers": [
		  	{
				"@type": "Offer",
				"availability": "{{ $product->quantity > 0 ? 'http://schema.org/InStock' : 'http://schema.org/OutOfStock' }}",
				"price": "{{ number_format($product->price, 2) }}",
				"priceCurrency": "USD"
		  	}
	  	]
	}
</script>

@endsection