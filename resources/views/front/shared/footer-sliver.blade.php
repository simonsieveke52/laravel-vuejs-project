<footer class="container footer-font">
    <div class="bg-med-grey text-dark py-2">
        <div class="d-flex flex-md-row flex-column justify-content-between align-items-center with 100%. bg-med-grey text-dark">
            <div class="text-md-left text-center pl-md-4 col-md-4 order-2 order-md-1">
                <img src="{{ asset('images/payment_grey.png') }}" class="img-fluid my-3 my-sm-0 max-width-200">
            </div>
            <div class="col-md-4 order-3 order-md-2">
                <h5 class="font-open-sans text-center font-weight-bold pb-2 pt-1 m-0 text-dark">
                    <small>&copy;Copyright {{ \Carbon\Carbon::now()->year }} - RebelSmuggling</small>
                </h5>
            </div>
            <div class="col-md-4 order-1 order-md-3">
                <h5 class="font-open-sans text-md-right text-center font-weight-bold pb-2 pt-1 m-0 text-dark">
                    <small><a href="{{ route('terms-and-conditions') }}" class="text-dark">Terms and Conditions</a></small>
                    <small>|</small>
                    <small><a href="{{ route('privacy-policy') }}" class="text-dark">Privacy Policy</a></small>
                </h5>
            </div>
        </div>
    </div>
</footer>