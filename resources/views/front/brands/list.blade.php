@extends('layouts.front.category.master')

@section('page-content')

	<div class="row mb-5">
		
		<div class="col-12">

				@if (request()->has('keyword'))

					Looking for {{ request()->keyword }}?

				@else
                    @if ($brand->cover)
					    <div class="text-center">
                            <img src="{{ Croppa::url('/storage/' . $brand->cover, 400, 'auto') }}" alt="{{ $brand->name }}" class="img-fluid">
                        </div>
                    @endif
                    <h1 class="h2 text-center text-uppercase mb-5">{{ $brand->name }}</h1>
					
				@endif
			

		</div>

		@if (!$products->isEmpty())

			@foreach ($products as $product)

				@include('front.products.product', compact('product'))
				
			@endforeach

			<div class="col-12 mt-5">
				<div class="flex-wrap align-items-center justify-content-center w-100 d-flex">
					{{ 
						$products->appends([
	                        'keywords' => request()->keywords,
	                        'from' => request()->from,
	                        'to' => request()->to,
	                    ])
						->links() 
	                }}
				</div>	
			</div>

		@else

			<div class="col-12 mt-4">
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					We're sorry, we currently do not have any products from this brand.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				{{-- product recommendations --}}
				
			</div>

		@endif


	</div>

@endsection