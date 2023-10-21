<header>
    <div class="text-gray bg-highlight w-100 py-2" style="height: 44px;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-nowrap font-size-0-8rem font-size-sm-1rem font-weight-light d-flex flex-row flex-wrap align-items-center justify-content-center justify-content-sm-between">
                        <div class="py-1 mr-2">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a class="text-gray" target="_blank" href="{{ config('default-variables.social_media.facebook') }}">
                                        <i class="fab fa-facebook-square"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-gray" target="_blank" href="{{ config('default-variables.social_media.youtube') }}">
                                        <i class="fab fa-youtube-square"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-gray" target="_blank" href="{{ config('default-variables.social_media.twitter') }}">
                                        <i class="fab fa-twitter-square"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="py-1 mr-2 text-uppercase lead font-weight-bolder">
                            10 Day Hassle Free Returns
                        </div>
                        <div class="py-1 ml-2">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a class="text-gray lead font-weight-bolder">
                                        CALL US
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-gray lead font-weight-bolder">
                                        (855)-924-1049
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-gray w-100 py-3 border-bottom">
        <div class="container">
            <div class="flex items-center">
                <a class="pl-3 pr-lg-3 pr-xl-4 m-auto" href="{{ route('home') }}">
                    <img
                        src="{{ asset('images/logo.png?v') }}"
                        class="img-fluid position-relative max-w-175px max-w-md-215px"
                        alt="{{ config('app.name') }}"
                    >
                </a>

                <div class="pt-4 pb-0 py-md-4 py-lg-0" id="primary-top-navbar">
                    <div class="ml-auto mr-0">
                        <div class="d-flex flex-row align-items-center justify-content-between justify-content-sm-end small">
                            <div class="list-inline mb-0 text-nowrap small d-flex w-100 align-items-center justify-content-between">
                                <li class="list-inline-item mr-4 pr-1">
                                    <a class="text-decoration-none" href="#search-modal" role="button" data-toggle="modal">
                                        <span class="fa-layers fa-fw mb-0 position-relative d-flex flex-row">
                                            <img
                                                src="{{ asset('images/search.png?v') }}"
                                                class="img-fluid position-relative max-w-175px max-w-md-215px"
                                            >
                                        </span>
                                    </a>
                                </li>
                                <li class="list-inline-item mr-3 pr-2">
                                    <a class="text-decoration-none" href="{{ route('customer.account') }}">
                                        <img
                                            src="{{ asset('images/user.png?v') }}"
                                            class="img-fluid position-relative max-w-175px max-w-md-215px"
                                        >
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <cart-component css-classes="d-flex text-decoration-none mb-0 fa-2x text-muted text-hover-darker">
                                    </cart-component>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white py-2 border-secondary border-bottom">
        <div class="container">

            <button
                class="navbar-toggler rounded-0"
                type="button"
                data-toggle="collapse"
                data-target="#primary-top-navbar"
                aria-controls="primary-top-navbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse pt-4 pb-0 py-md-4 py-lg-0 navbar-collapse" id="primary-top-navbar">
                <ul class="navbar-nav mr-0">

                    @foreach ($categories as $category)
                    
                        @if (! isset($category->children) || $category->children->isEmpty())

                            <li class="nav-item font-size-lg-0-8rem px-xl-3">
                                <a
                                    class="nav-link text-uppercase text-nowrap text-soft-dark"
                                    href="{{ route('category.filter', $category) }}"
                                    >
                                    {{ $category->name }}
                                </a>
                            </li>

                        @else

                            <li class="nav-item font-size-lg-0-8rem dropdown navbar--primary__container__row__nav-item mr-4">
                                <a
                                    class="nav-link text-uppercase text-nowrap text-soft-dark"
                                    href="#"
                                    id="{{ $category->slug }}"
                                    role="button"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    {{ $category->name }}
                                </a>
                                <div class="dropdown-menu bg-secondary shadow-sm border-0 mt-0 pt-0 pb-0 px-2 px-md-0 rounded-bottom max-w-1371px mx-auto" aria-labelledby="{{ $category->slug }}">
                                    <div class="align-items-start p-2 py-md-3 px-lg-4 max-w-1000px mx-auto">
                                        <div class="d-flex flex-row align-items-center justify-content-between">
                                            <div>
                                                <ul class="list-unstyled pl-3">
                                                    @foreach ($category->children as $child)
                                                    <li class="navbar--primary__listiitem mb-4 font-size-lg-0-8rem">
                                                        <a
                                                            class="font-weight-normal text-uppercase text-dark px-2 rounded avbar--primary__link navbar--primary__link--top"
                                                            href="{{ route('category.filter', $child) }}"
                                                            >
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="p-3">
                                                <img 
                                                    data-error="/storage/notfound.png" 
                                                    src="/images/px.png"
                                                    :data-src="{{ json_encode(asset('images/px.png')) }}" 
                                                    v-lazy="{{ json_encode(asset($category->dropdown_image . '?v')) }}" 
                                                    alt="{{ $category->name }}" 
                                                    class="img-fluid rounded-sm"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        @endif

                    @endforeach

                    <li class="nav-item font-size-lg-0-8rem dropdown navbar--primary__container__row__nav-item mr-4">
                        <a
                            class="nav-link text-uppercase text-nowrap font-weight-bold text-black"
                            href="#"
                            id="natural-magic"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            rv Accessories
                        </a>
                        <div class="dropdown-menu bg-secondary shadow-sm border-0 mt-0 pt-0 pb-0 px-2 px-md-0 rounded-bottom max-w-1371px mx-auto" aria-labelledby="natural-magic">
                            <div class="align-items-start p-2 py-md-3 px-md-4 max-w-1000px mx-auto">
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <ul class="list-unstyled pl-3">
                                            <li class="navbar--primary__listiitem mb-4 font-size-lg-0-8rem">
                                                <a
                                                    class="font-weight-normal text-uppercase text-dark px-2 rounded avbar--primary__link navbar--primary__link--top"
                                                    href="{{ route('about-us') }}"
                                                    >
                                                    Our Story
                                                </a>
                                            </li>
                                            <li class="navbar--primary__listiitem mb-4 font-size-lg-0-8rem">
                                                <a
                                                    class="font-weight-normal text-uppercase text-dark px-2 rounded avbar--primary__link navbar--primary__link--top"
                                                    href="{{ route('technology') }}"
                                                    >
                                                    Technology
                                                </a>
                                            </li>
                                            <li class="navbar--primary__listiitem mb-4 font-size-lg-0-8rem">
                                                <a
                                                    class="font-weight-normal text-uppercase text-dark px-2 rounded avbar--primary__link navbar--primary__link--top"
                                                    href="{{ route('in-the-press') }}"
                                                    >
                                                    In The Press
                                                </a>
                                            </li>
                                            <li class="navbar--primary__listiitem mb-4 font-size-lg-0-8rem">
                                                <a
                                                    class="font-weight-normal text-uppercase text-dark px-2 rounded avbar--primary__link navbar--primary__link--top"
                                                    href="{{ route('wholesale') }}"
                                                    >
                                                    Wholesale & Bulk Sizes
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="p-3">
                                        <img 
                                            data-error="/storage/notfound.png" 
                                            src="/images/px.png"
                                            :data-src="{{ json_encode(asset('images/px.png')) }}" 
                                            v-lazy="{{ json_encode(asset('images/NATURALMAGICTOPNAV.jpg?v')) }}" 
                                            alt="Natural magic" 
                                            class="img-fluid rounded-sm"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    

                </ul>
            </div>
        </div>
    </nav>
</header>