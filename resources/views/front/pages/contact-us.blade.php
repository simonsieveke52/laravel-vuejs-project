@extends('layouts.front.app')

@section('content')
    
    <div class="container px-2 mt-5 mb-4">
        <div class="row">
            <div class="col-md-6 order-2 order-lg-1">
                <div class="jq-busyload">
                    <iframe width="100%" height="470" id="gmap_canvas" src="" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
            </div>
            <div class="col-md-6 order-1 order-lg-2">
                <div class="px-2">
                    <div class="mb-4">
                        <h1>Contact Us</h1>
                        <p class="lead mb-3">
                            Want to talk to us? Call, click, or chat! <br>
                        </p>
                        <address class="h5 font-weight-light">
                            <a href="tel:{{ config('default-variables.phone') }}">{{ formatPhone(config('default-variables.phone')) }}</a> <br>
                            Address here... <br>
                        </address>
                    </div>
                    <div>
                        <contact-request-form class="text-left"></contact-request-form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $('.jq-busyload').busyLoad('show')

    setTimeout(function() {
        $('.jq-busyload').busyLoad('hide')
    }, 1500)

    // $('#gmap_canvas').prop('src', 'https://maps.google.com/maps?ll=33.81516267391131,-118.33885252475739&q=&t=&z=18&ie=UTF8&iwloc=&output=embed');
    
</script>
@endpush