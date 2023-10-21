<div class="col-12 d-block d-sm-none">
	<h1 class="h3 mt-3 font-size-1-3rem line-height-1-8rem">{{ $product->name ?? '' }}</h1>
</div>

<div class="col-md-7">
	<div class="product--image__wrapper alert alert-light text-center d-flex align-items-center mb-2 mb-md-0">
		<product-images-component :product="{{ json_encode($product) }}"></product-images-component>
	</div>
</div>

<div class="col-md-5">

	<h1 class="h3 d-none d-sm-block font-size-md-1-5rem line-height-md-2-1rem">{{ $product->name ?? '' }}</h1>
	
	@if ($product->brand instanceof \App\Brand && isset($product->brand->name))
		<h2 class="h5">{{ $product->brand->name ?? '' }}</h2>
	@endif

	<div class="form-group mt-4">
		<money-component class="text-decoration-line-through" :value="{{ $product->original_price }}"></money-component>
		<money-component class="text-highlight h3" :value="{{ $product->price }}"></money-component>
	</div>

	<div class="d-flex d-md-none mb-3 justify-content-between">	
		<product-cart-component class="d-flex flex-row align-items-center justify-content-center" :product="{{ json_encode($product) }}"></product-cart-component>
		<add-to-favorites :product="{{ json_encode($product) }}"></add-to-favorites>
	</div>

	<div class="p-3 bg-secondary rounded-lg mb-3">
		<table class="table mb-0">

			<tr>
				<th class="py-2 px-0 border-0">
					Product ID
				</th>
				<td class="py-2 px-0 border-0 text-right text-highlight">
					{{ $product->id }}
				</td>
			</tr>

			@isset($product->sku)
				<tr>
					<th class="py-2 px-0">
						SKU:
					</th>
					<td class="py-2 px-0 text-right text-highlight">
						{{ $product->sku }}
					</td>
				</tr>
			@endisset

			@if ($product->category instanceof \App\Category)
				<tr>
					<th class="py-2 px-0">
						Category:
					</th>
					<td class="py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->category->name ?? '' }}</span>
					</td>
				</tr>
			@endif

			@if ($product->brand instanceof \App\Brand)
				<tr>
					<th class="py-2 px-0">
						Brand:
					</th>
					<td class="py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->brand->name ?? '' }}</span>
					</td>
				</tr>
			@endif
			@if ($product->mpn !== null)
				<tr>
					<th class="py-2 px-0">
						Model Number:
					</th>
					<td class="py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->mpn }}</span>
					</td>
				</tr>
			@endif
			<tr>
				<th class="py-2 px-0">
					Weight:
				</th>
				<td class="py-2 px-0 text-right">
					<span class="text-highlight">{{ $product->weight }} {{ $product->weight_uom }}</span>
				</td>
			</tr>
			@if ($product->upc !== null)
				<tr>
					<th class="py-2 px-0">
						GTIN/UPC:
					</th>
					<td class="py-2 px-0 text-right">
						<span class="text-highlight">{{ $product->upc }}</span>
					</td>
				</tr>
			@endif
		</table>
	</div>
	<div class="d-none d-md-flex justify-content-between">	
		<product-cart-component class="d-flex flex-row align-items-center justify-content-center" :product="{{ json_encode($product) }}"></product-cart-component>
		<add-to-favorites :product="{{ json_encode($product) }}"></add-to-favorites>
	</div>	
</div>

<div class="py-5 bg-secondary mb-5">
	<div class="col-12">
		{!! $product->description !!}
	</div>
</div>