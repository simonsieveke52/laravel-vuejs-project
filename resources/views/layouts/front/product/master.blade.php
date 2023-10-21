@extends('layouts.front.app')

@section('body_class', 'product')

@section('seo')

	@if (isset($product) && $product instanceof \App\Product)
		<meta name="title" content="{{ cleanedSubstr($product->name, 55) }}">
        <meta name="description" content="{{ cleanedSubstr($product->description, 155) }}">
		<meta property="og:title" content="{{ cleanedSubstr($product->name, 55) }}"/>
		<meta property="og:description" content="{{ cleanedSubstr($product->description, 155) }}"/>
		<meta property="og:image" content="{{ asset($product->main_image) }}"/>
	@else
		<meta name="title" content="@yield('title', config('app.name'))">
        <meta name="description" content="">
		<meta property="og:title" content="@yield('title', config('app.name'))"/>
		<meta property="og:description" content="@yield('description', '')"/>
		<meta property="og:image" content=""/>
	@endif

	<meta property="og:type" content="product"/>
	<link rel=“canonical” href="{{ request()->url() }}" />
@endsection

@if (isset($product) && $product instanceof \App\Product)
	@section('title'){{ cleanedSubstr($product->name, 55) }}@endsection
@endif

@section('content')

<div class="container bg-white mt-4">

	<div class="row pb-5">
		<div class="col-12">
			@yield('page-content')
		</div>
	</div>

</div>

@endsection

