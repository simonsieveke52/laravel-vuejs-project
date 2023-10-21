@extends('layouts.front.app')

@section('content')

<div class="container-fluid">
	<div class="row flex-nowrap position-relative max-h-466px" style="background-image: url('/images/home_baner.png'); background-size: cover; background-repeat: no-repeat; background-position: center;">

		<div 
			class="col-md-7 position-relative min-h-466px w-100"
		>
			<div class="d-flex flex-column align-items-start justify-content-center p-5 p-lg-4 p-xl-5 max-w-705px mt-4 mt-lg-0 text-left mx-auto">
				<h1 class="font-weight-bolder font-size-lg-4rem text-white mb-3 line-height-1-1">
					Extensive inventory of top brand camper and RV awning shades
				</h1>
				<p class="lead text-white font-family-open-sans">
					FREE Shipping on UPS Ground orders over $99.
				</p>
				<a href="{{ route('category.filter') }}" class="btn btn-green px-4 py-2 text-uppercase mt-3">
					<small class="py-2 d-block font-weight-bold">Shop All Products</small>
				</a>
			</div>
		</div>
	</div>	
</div>

<!-- <div class="bg-secondary categories-container py-5">
	<div class="container py-4">
		<div class="row mb-4">
			<div class="col-12 text-center">
				<h2 class="font-weight-light text-dark h3 mb-3">A custom clean for <span class="font-weight-bold">every room.</span></h2>
				<p class="text-muted-3 lead mx-auto font-family-open-sans max-w-860px">
					The things getting your kitchen dirty are different from what gets your clothes or bathroom dirty. Try a complete clean for every room of the house tailored to natural perfection.
				</p>
			</div>
		</div>
		<div class="row">
			@foreach ($categories as $category)
				<div class="col-12 col-sm-6 col-md-3 text-center align-items-end justify-content-center text-center mb-3 pb-2">
					<a class="d-flex hover-overlay flex-column align-items-center justify-content-center w-100 h-100" href="{{ route('category.filter', $category)}}">
						<img 
							src="{{ asset($category->cover) }}"
							alt="{{ $category->name }}" 
							class="img-fluid mb-3"
						>
						<h3 class="font-weight-normal h6 text-uppercase text-darker mb-0 mt-auto">{{ $category->name }}</h3>
					</a>
				</div>
			@endforeach
		</div>
	</div>
</div> -->

<div class="bg-white py-5">
	<div class="container">
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="font-weight-bolder text-dark font-size-lg-3rem mb-3">RV awning shades and accessories</h2>
			</div>
		</div>
		<div class="row">
			@foreach ($products as $product)
				<div class="col-12 col-sm-6 col-lg-4">
					<product-component 
						class="pb-4 h-100"
						product-class="img-fluid img-responsive w-auto d-block m-auto min-w-200px max-h-300px h-auto"
						view-type="custom" 
						:product="{{ json_encode($product) }}"
					>		
					</product-component>
				</div>
			@endforeach
		</div>
	</div>
</div>

<div class="bg-secondary flex-column flex-lg-row align-items-center" style="background-image: url('/images/sharde_control.png'); background-size: cover; background-repeat: no-repeat; background-position: center; height: 557px;">
	<h1 class="text-white font-weight-bold text-center	py-4">Leaders in Sun & Shade Control</h1>
	<div class="container">
		<div class="row no-gutters ftco-services">
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<div class="block-6 services mb-md-0 mb-4">
				<div class="sharder-icon bg-white active d-flex justify-content-center align-items-center mb-2">
					<span><img 
						src="{{ asset('images/icon1.png') }}" 
						class="img-fluid" 
					></span>
				</div>
				<div class="media-body px-4">
					<h3 class="heading font-weight-bold text-white">Low price guarantee</h3>
					<span class="text-white">Our manufacturer relationships enable us to attain the highest-quality awnings and RV awning accessories at low prices, allowing us to pass the value along to you!</span>
				</div>
				</div>      
				</div>
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<div class="block-6 services mb-md-0 mb-4">
				<div class="sharder-icon bg-white d-flex justify-content-center align-items-center mb-2">
					<img 
						src="{{ asset('images/icon2.png') }}" 
						class="img-fluid" 
					>
				</div>
				<div class="media-body px-4">
					<h3 class="heading font-weight-bold text-white">Mobile installations</h3>
					<span class="text-white">Many of our products are simple to install; however, you may feel better if you have a professional install everything. If this is the case, we can install it for you!</span>
				</div>
				</div>    
			</div>
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<div class="block-6 services mb-md-0 mb-4">
				<div class="sharder-icon bg-white d-flex justify-content-center align-items-center mb-2">
					<img 
						src="{{ asset('images/icon3.png') }}" 
						class="img-fluid" 
					>
				</div>
				<div class="media-body px-4">
					<h3 class="heading font-weight-bold text-white">Expert technicians</h3>
					<span class="text-white">You can rest assured that you will receive the custom care and service needed to order and install your Awnings and ShadePro accessories</span>
				</div>
				</div>      
			</div>
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<div class="block-6 services mb-md-0 mb-4">
				<div class="sharder-icon bg-white d-flex justify-content-center align-items-center mb-2">
					<img 
						src="{{ asset('images/icon4.png') }}" 
						class="img-fluid" 
					>
				</div>
				<div class="media-body px-4">
					<h3 class="heading font-weight-bold text-white">Free shipping</h3>
					<span class="text-white">FREE Shipping on UPS Ground orders over $99s</span>
				</div>
				</div>      
			</div>
			</div>
		</div>
	</div>
</div>

<div class="bg-secondary">
	<quote-slide></quote-slide>
</div>

<div style="background-image: url('/images/contact_banner.png'); background-size: cover; background-repeat: no-repeat; background-position: center; height: 982px;">
	<div class="container flex items-start justify-between justify-items-stretch">
		<div class="row no-gutters ftco-services">
			<div class="col-md-6 p-5 text-lg-right">
				<div class="d-flex flex-column align-items-start justify-content-center p-5 p-lg-4 p-xl-5 mt-4 mt-lg-0 text-left mx-auto">
					<h3 class="font-weight-bolder font-size-lg-2rem text-white mb-3 line-height-1-1">
						Need help with your RV shades and accessories? 
					</h3>
					<p class="lead text-white font-family-open-sans">
					We are available to help troubleshoot problems, connect you with our support team and solve problems quickly - so you can get back to your life. Use the form to get in touch with our support team
					</p>
					<a href="{{ route('category.filter') }}">
						<small class="lead py-2 text-white font-weight-bold">(855) 924-1049</small>
					</a>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-md-6 order-md-last d-flex">
					<form action="#" class="bg-white p-5 contact-form">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Your Name">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Your Email">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Subject">
					</div>
					<div class="form-group">
						<textarea name="" id="" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
					</div>
					<div class="form-group">
						<input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="flex-column flex-lg-row align-items-center">
	<h1 class="text-dark font-size-lg-2rem font-weight-bold text-center	py-5">Authorized retailer of the top sun protection brands</h1>
	<div class="container">
		<div class="row no-gutters ftco-services">
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<a href="#" class="mx-auto"><img src="images/partner-1.png" class="img-fluid" alt="Colorlib Template"></a>
			</div>
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<a href="#" class="mx-auto"><img src="images/partner-2.png" class="img-fluid" alt="Colorlib Template"></a>
			</div>
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<a href="#" class="mx-auto"><img src="images/partner-3.png" class="img-fluid" alt="Colorlib Template"></a>
			</div>
			<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
				<a href="#" class="mx-auto"><img src="images/partner-4.png" class="img-fluid" alt="Colorlib Template"></a>
			</div>
		</div>
	</div>
</div>

<product-modal-component></product-modal-component>

@endsection
