<div class="footer">
    <!-- <div class="bg-highlight min-h-65px">
        <div class="container">
            <div class="text-center mx-auto">
                <subscribe-form-component class="py-3">
                    <div class="mb-0">Thank you.</div>
                </subscribe-form-component>
            </div>
        </div>
    </div> -->
    <div class="bg-secondary-1 font-family-open-sans">
        <div class="container">
            <div class="row footer__links text-dark px-0 pt-2 px-lg-2 pt-lg-5">
                <div class="col-xl-4 text-center d-flex align-items-center justify-content-center">
                    <div class="mt-3">
                        <div class="text-left mb-3">
                            <img 
                                src="{{ asset('images/logo.png') }}" 
                                class="img-fluid max-w-175px mb-3" 
                                alt="{{ config('app.name') }}"
                            >
                            <p class="line-height-1-6 mb-0 opacity-0-85">
                                Copyright 2021 Shade Pro, Inc. All rights reserved. No part of this website may be reproduced in any form, by any means, without written permission from Shade Pro, Inc. 
                            </p>
                        </div>
                        <!-- <div class="opacity-0-85">
                            <div class="d-flex flex-row flex-nowrap mb-3">
                                <div>
                                    <img 
                                        data-error="/storage/notfound.png" 
                                        src="/images/px.png"
                                        :data-src="{{ json_encode(asset('images/px.png')) }}" 
                                        v-lazy="{{ json_encode(asset('images/glob-icon.png')) }}" 
                                        class="img-fluid" 
                                        alt="naturalhouse location"
                                    >
                                </div>
                                <address class="text-left line-height-1-6 pl-3">
                                     Illinois, US
                                </address>
                            </div>
                            <div class="d-flex flex-row flex-nowrap">
                                <div>
                                    <a class="text-dark opacity-0-85" href="tel:{{ config('default-variables.phone') }}">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                </div>
                                <address class="text-left opacity-0-85 line-height-1-6 pl-3">
                                    <a class="text-dark" href="tel:{{ config('default-variables.phone') }}">{{ formatPhone(config('default-variables.phone')) }}</a> <br>
                                    Mon - Fri: 9AM-5PM EST
                                </address>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-xl-8 opacity-0-85">
                    <div class="row">
                        @foreach ($categories->sortByDesc('children') as $category)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                            <div class="footer-category-container px-md-3 h-100">
                                <ul class="list-unstyled mb-1">
                                    <li class="mb-3">
                                        <a class="text-dark font-weight-bold font-size-md-1-1rem text-uppercase" href="{{ route('category.filter', $category) }}">{{ $category->name }}</a>
                                    </li>
                                    <ul class="list-unstyled card-columns" style="column-count: 1;">
                                        @foreach ($category->children as $child)
                                            <li class="line-height-1-2rem mb-3"><a class="text-dark font-size-md-0-9rem font-weight-light text-capitalize" href="{{ route('category.filter', $child) }}">{{ $child->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </ul>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-secondary-1 bottom-footer">
        <div class="container">
            <div class="row d-flex justify-content-around align-items-center py-2">
                <div class="col-12 col-sm-6 text-center d-flex flex-wrap align-items-center justify-content-center justify-content-md-start">
                    <div class="d-flex justify-content-center flex-row flex-wrap flex-sm-nowrap small justify-content-center">
                        <ul class="list-inline mb-0 text-muted text-left text-nowrap">
                            <li class="list-inline-item mx-0"><a class="text-dark-4" href="{{ route('privacy-policy') }}">Privacy</a></li>
                            <li class="list-inline-item text-dark-4 mx-1">|</li>
                            <li class="list-inline-item mx-0"><a class="text-dark-4" href="{{ route('terms-and-conditions') }}">Terms and Conditions</a></li>
                        </ul>
                    </div>
                    <ul class="list-inline mb-0 text-muted text-center d-flex d-lg-none align-items-center justify-content-center">
                        <li class="list-inline-item"><i class="fab fa-cc-mastercard"></i></li>
                        <li class="list-inline-item"><i class="fab fa-cc-amex"></i></li>
                        <li class="list-inline-item"><i class="fab fa-cc-visa"></i></li>
                    </ul>
                </div>

                <div class="col-5 d-none d-lg-flex justify-content-end align-items-center">
                    <ul class="list-inline mb-0 text-muted text-right">
                        <li class="list-inline-item"><i class="fab fa-cc-mastercard"></i></li>
                        <li class="list-inline-item"><i class="fab fa-cc-amex"></i></li>
                        <li class="list-inline-item"><i class="fab fa-cc-visa"></i></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-center d-lg-none fixed-bottom w-100 bg-secondary border-top mobile-cart-container">
        <div class="container text-center small py-2">
            <div class="btn-group">
                <a class="btn btn-secondary text-decoration-none" href="#search-modal" role="button" data-toggle="modal">
                    <span class="fa-layers fa-fw mb-0 position-relative d-flex flex-row">
                        <i class="fas fa-search text-muted text-hover-darker fa-2x"></i>
                    </span>
                </a>
                <a class="btn btn-secondary text-decoration-none" href="{{ route('favorites') }}">
                    <favorites-component css-class="text-muted text-hover-darker fa-2x"></favorites-component>
                </a>
                <button class="btn btn-secondary ">
                    <cart-component css-classes="d-flex text-decoration-none mb-0 fa-2x text-muted text-hover-darker">
                    </cart-component>
                </button>
            </div>
        </div>
    </div>
</div>
