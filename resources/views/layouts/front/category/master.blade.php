@extends('layouts.front.app')

@section('seo')

	@if (isset($category) && $category instanceof \App\Category)
		<meta id="category_title" name="title" content="{{ cleanedSubstr($category->name, 55) }}">
        <meta id="category_description" name="description" content="{{ cleanedSubstr($category->description, 155) }}">
		<meta id="category_og:title" property="og:title" content="{{ cleanedSubstr($category->name, 55) }}"/>
		<meta id="category_og:description" property="og:description" content="{{ cleanedSubstr($category->description, 155) }}"/>
	@else
		<meta id="category_title" name="title" content="@yield('title', config('app.name'))">
        <meta id="category_description" name="description" content="">
		<meta id="category_og:title" property="og:title" content="@yield('title', config('app.name'))"/>
		<meta id="category_og:description" property="og:description" content="@yield('description', '')"/>
	@endif

	<link rel=“canonical” href="{{ request()->url() }}" />

@endsection

@if (isset($category) && $category instanceof \App\Category)
	@section('title'){{ cleanedSubstr($category->name, 55) }}@endsection
@endif

@section('content')

	<div class="container bg-white">

		<div class="bg-secondary row d-block d-md-none">
			<div class="col-12">	
				<nav class="navbar navbar-expand-lg navbar-light text-dark px-0">
				    <button
		                class="navbar-toggler bg-white rounded-0"
		                type="button"
		                @click="toggleElement('#main-navbar')"
		                aria-expanded="false"
		            >
		                <span class="navbar-toggler-icon"></span>
		            </button>
				</nav>
			</div>
		</div>

	    <div class="row mt-4">

	        <div class="col-lg-3 col-md-4 d-md-block col-0">
	            @include('front.shared.navigation-primary')
	        </div>

	        <div class="col-12 col-md-8 col-lg-9 col-12 px-xl-3 pr-md-4 pl-md-2">
	            
	            <div class="row">
	                <div class="col-12 text-center">
	                    @include('layouts.errors-and-messages')
	                </div>
	            </div>

	            <div class="jq-page-content">
	                <div class="jq-categories-page">
						@yield('page-content')
					</div>
	            </div>

	        </div>
	    </div>
	</div>

@endsection

