@extends('layouts.front.simple')

@section('content')

<div>
	<form 
	    method="POST" 
	    action="{{ route('checkout.execute') }}" 
	    class="form mt-4 form--checkout needs-validation {{ $errors->count() > 0 ? 'was-validated' : '' }} jq-checkout-form mb-3" 
	    novalidate=""
	>
	    @csrf
	    <div class="container">

	        <div class="row mb-4 mt-5">
	            <div class="col-12">
	                <h1 class="text-center h4 mb-3 font-weight-bold text-dark">SHIPPING OPTIONS</h1>
	            </div>
	        </div>

	        <input type="hidden" name="payment_method" value="credit_card">

	        <div class="row">
	    
	            <div class="col-12 col-md-7 col-lg-7 col-xl-7 order-xl-1">

	            	<div class="row">
	            		<div class="col-12">
	            			<ups-shipping-options
	            				class="mb-4"
			                    title="Choose your shipping method" 
			                    :selected="{{ json_encode(session('shipping', 1)) }}" 
			                >
			                </ups-shipping-options>
	            		</div>

	            		<div class="col-12">
			                <div id="credit-card-container" class="payment rounded-lg bg-lighter p-4 border border-secondary mb-5 mt-4 mt-lg-0 position-relative">

			                	<div class="jq-overlay d-flex align-items-center justify-content-center position-absolute rounded-lg" style="left: 0; right: 0; top: 0; bottom: 0; z-index: 100; opacity: 1;">
			                		<div class="jq-overlay d-flex align-items-center justify-content-center position-absolute bg-white rounded-lg" style="left: 0; right: 0; top: 0; bottom: 0; z-index: 100; opacity: 0.5;"></div>
			                	</div>

			                    <div class="mb-0">
			                        <div class="row">
			                            <div class="col-xl-12 mb-3">
			                                <label class="font-weight-bold text-dark" for="cc_number">Credit card number</label>
			                                <input name="cc_number" type="text" class="form-control" id="cc_number" value="{{ old('cc_number') }}" required="">
			    
			                                @if ($errors->has('cc_number'))
			                                    <div class="invalid-feedback d-block">
			                                        {{ $errors->first('cc_number') }}
			                                    </div>
			                                @endif
			    
			                            </div>
			                        </div>
			                        <div class="row mb-2">
			                            <div class="col-xl-6 mb-3">
			                                <label class="font-weight-bold text-dark" for="cc_name">Name on card</label>
			                                <input name="cc_name" type="text" class="form-control" id="cc_name" value="{{ old('cc_name') }}" required="">
			                                <small class="text-dark">Full name as displayed on card</small>
			    
			                                @if ($errors->has('cc_name'))
			                                    <div class="invalid-feedback d-block">
			                                        {{ $errors->first('cc_name') }}
			                                    </div>
			                                @endif
			                                
			                            </div>
			                            <div class="col-8 col-xl-4 mb-3">
			                                <label class="font-weight-bold text-dark text-nowrap" for="cc_expiration">Expiration (Month/Year)</label>

			                                <div class="d-flex flex-row">
			                                	<div class="mr-2 flex-fill">
					                                <input name="cc_expiration_month" type="number" step="1" min="01" max="12" class="form-control" placeholder="Month" id="cc_expiration_month" value="{{ old('cc_expiration_month') }}" required="" maxlength="2">
					                            </div>
					                            <div class="flex-fill">
					                                <input name="cc_expiration_year" type="number" step="1" min="19" max="30" class="form-control rounded" placeholder="Year" id="cc_expiration_year" value="{{ old('cc_expiration_year') }}" required="" maxlength="2">
					                            </div>
			                                </div>

			                                @if ($errors->has('cc_expiration_month'))
			                                    <div class="invalid-feedback d-block">
			                                        {{ $errors->first('cc_expiration_month') }}
			                                    </div>
			                                @endif

			                                @if ($errors->has('cc_expiration_year'))
			                                    <div class="invalid-feedback d-block">
			                                        {{ $errors->first('cc_expiration_year') }}
			                                    </div>
			                                @endif
			                            </div>
			                            <div class="col-4 col-xl-2 mb-3">
			                                
			                                <label class="font-weight-bold text-dark" for="cc_cvv">CVV</label>
			    
			                                <input name="cc_cvv" type="text" class="form-control" id="cc_cvv" value="{{ old('cc_cvv') }}" required="">
			    
			                                @if ($errors->has('cc_cvv'))
			                                    <div class="invalid-feedback d-block">
			                                        {{ $errors->first('cc_cvv') }}
			                                    </div>
			                                @endif
			                            </div>
			                        </div>
			                        <div class="row mb-2">
			                            <div class="col-12 text-right">
			                                <button type="submit" class="btn btn-highlight py-2 px-5 jq-confirm-checkout">Confirm</button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
	                	</div>

	            	</div>
	    
	            </div>
	    
	            <div class="col-12 col-md-5 col-lg-5 offset-lg-0 col-xl-4 offset-xl-1 order-xl-2 mb-4">
	                <div class="rounded-lg bg-lighter border-highlight border shadow px-4 pt-3 pb-4 mb-5">
	                    <cart-overview-component></cart-overview-component>
	                </div>
	                <div class="d-lg-block d-none">
	                	<div class="card-wrapper pt-3 row"></div>
	                </div>
	            </div>
	    
	        </div>
	   
	    </div>
	</form>
</div>

@endsection

@section('css')
	<style>
		.jp-card-container{
			margin: auto !important;
		}
		.form-control {
			border-bottom-left-radius: 5px;
		    border-top-left-radius: 5px;
		}
		.jp-card-container {
			transform: scale(1.14) !important;
		}
		.form-control {
			border: 1px solid #bbbbbb;
		}
	</style>
@endsection

@push('scripts')

	<script src="{{ asset('js/card.js') }}"></script>

	<script>
		
		$(function(){

			$('body').on('click', '.jq-confirm-checkout', function(event) {
				$.busyLoadFull('show')
			});

			$('[name="payment_method"]').val('credit_card')
		})

	</script>

@endpush