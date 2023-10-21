@extends('layouts.front.category.master')

@section('page-content')

	<div class="mt-2 pt-2 mb-5">
		<products-component :start-page="{{ (int) request()->input('page', 1) }}"></products-component>
		<product-modal-component></product-modal-component>
	</div>

@endsection