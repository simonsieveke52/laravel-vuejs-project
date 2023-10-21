@extends('layouts.front.simple')

@section('content')

<div class="mt-5 min-h-700px">

    <div class="container">
        <div class="row">

            <div class="col-md-7">
                <checkout-component 
                    class="mb-md-5 pb-md-5"
                    route-url="{{ route('guest.checkout.store') }}"
                    validate-url="{{ route('address-validation.store') }}"
                    :errors="{{ json_encode($errors->getMessages()) }}"
                >
                    <slot>
                        @csrf
                    </slot>
                </checkout-component>  
            </div>

            <div class="col-md-4 mr-md-0 ml-md-auto position-relative">
                <cart-overview-component class="shadow-sm mt-1 px-4 pt-4 pb-4 mb-5 bg-secondary-1"></cart-overview-component>
            </div>
        </div>
    </div>

</div>

<address-validation-component></address-validation-component>

@endsection
