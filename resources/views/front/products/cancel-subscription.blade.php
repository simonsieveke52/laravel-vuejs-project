@extends('layouts.front.product.master')

@section('page-content')

<section class="product__buy-box pt-lg-2 mb-0 jq-main-container" style="min-height: 700px;">

	<h1 class="text-dark h2 mb-3">Unsubscribe</h1>
	<p>
		Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt, libero fuga nobis? Libero dolore, expedita perspiciatis deserunt corporis quo modi natus impedit, fuga et voluptas! Illum perspiciatis mollitia obcaecati molestiae.
	</p>

	<div class="btn-group">

		<a href="{{ route('home') }}" class="btn btn-highlight px-4 py-2 text-uppercase mt-3">
			<small class="py-2 d-block font-weight-bold">Cancel</small>
		</a>

		<button class="btn btn-outline-dark px-4 py-2 text-uppercase mt-3 jq-confirm-btn">
			<small class="py-2 d-block font-weight-bold">Confirm</small>
		</button>

	</div>
   

</section>

@endsection

@push('scripts')
	<script>
		$(function() {
			$('body').on('click', '.jq-confirm-btn', function(event) {
				event.preventDefault();
				$.busyLoadFull('show')

				$.ajax({
					url: location.href,
					type: 'GET',
					dataType: 'json',
				})
				.done(function(response) {
					$('.jq-main-container').html('<div class="alert alert-success">You are successfully unsubscribed to this product. Thank you</div>')
				})
				.fail(function() {
					alert('Something went wrong, try again later.')
				})
				.always(function() {
					$.busyLoadFull('hide')
				});
				
			});
		})
	</script>
@endpush