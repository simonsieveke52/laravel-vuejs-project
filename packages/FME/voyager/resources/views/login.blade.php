<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <title>@yield('title', config('app.name'))</title>
        @yield('seo')
        <meta name="Description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="{{ asset('favicons/fav.png') }}" sizes="32x32">
        <link rel="icon" href="{{ asset('favicons/32.png') }}" sizes="32x32">
        <link rel="icon" href="{{ asset('favicons/32.png') }}" sizes="32x32">
        <link rel="icon" href="{{ asset('favicons/57.png') }}" sizes="57x57">
        <link rel="icon" href="{{ asset('favicons/76.png') }}" sizes="76x76">
        <link rel="icon" href="{{ asset('favicons/96.png') }}" sizes="96x96">
        <link rel="icon" href="{{ asset('favicons/128.png') }}" sizes="128x128">
        <link rel="icon" href="{{ asset('favicons/228.png') }}" sizes="228x228">
        <link rel="shortcut icon" href="{{ asset('favicons/128.png') }}" sizes="128x128">
        <link rel="apple-touch-icon" href="{{ asset('favicons/96.png') }}" sizes="96x96">
        <link rel="apple-touch-icon" href="{{ asset('favicons/128.png') }}" sizes="128x128">
        <link rel="apple-touch-icon" href="{{ asset('favicons/228.png') }}" sizes="228x228">
        <meta name="msapplication-TileColor" content="#fff">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/228.png') }}">
        <meta name="msapplication-config" content="{{ asset('favicons/browserconfig.xml') }}" />

        <meta property="og:title" content="@yield('title', config('app.name'))"/>
        <meta property="og:type" content=""/>
        <meta property="og:url" content="{{ request()->url() }}"/>
        <meta property="og:site_name" content=""/>
        <meta property="og:description" content=""/>

        <meta name="_token" content="{{ csrf_token() }}" />
        <link rel="preconnect" href="//www.google-analytics.com">
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ config('default-variables.gtm') }}');</script>
        <!-- End Google Tag Manager -->
        @yield('javascript')
        @yield('og')
        @stack('stylesheet')
        @yield('css')
        <style>
            .spinner-pump{width: 40px; height: 40px; position: relative;}
            .double-bounce1,.double-bounce2{width: 100%; height: 100%; border-radius: 50%; background-color: #333; opacity: 0.6; position: absolute; top: 0; left: 0; -webkit-animation: sk-bounce 2s infinite ease-in-out; animation: sk-bounce 2s infinite ease-in-out;}
        </style>
    </head>
    <body class="bg-white">
        <div id="app">
            <div class="container bg-white" style="min-height: 500px;">
                <div class="row">
                    <div class="col-12 text-center">
                        @include('layouts.errors-and-messages')
                    </div>
                </div>
                <div class="jq-page-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 500px;">
                                <div class="d-flex">
                                    <form class="p-4 bg-white shadow rounded-lg" action="{{ route('voyager.login') }}" method="POST" style="min-width: 400px;">
                                        <div class="mb-3">
                                            <a class="px-0 py-3 text-center d-block" href="{{ route('home') }}">
                                                <img
                                                    src="{{ asset('images/logo.png') }}"
                                                    class="img-fluid mr-0 mr-sm-3 position-relative"
                                                    style="max-width: 250px;"
                                                    alt="{{ config('app.name') }}"
                                                >
                                            </a>
                                        </div>
                                        {{ csrf_field() }}
                                        <div class="form-group form-group-default" id="emailGroup">
                                            <label>{{ __('voyager::generic.email') }}</label>
                                            <div class="controls">
                                                <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-default" id="passwordGroup">
                                            <label>{{ __('voyager::generic.password') }}</label>
                                            <div class="controls">
                                                <input type="password" name="password" placeholder="{{ __('voyager::generic.password') }}" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group" id="rememberMeGroup">
                                            <div class="controls">
                                                <input type="checkbox" name="remember" id="remember" value="1">&nbsp;<label for="remember" class="remember-me-text">{{ __('voyager::generic.remember_me') }}</label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-block btn-danger shadow-sm rounded-lg">Login</button>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var btn = document.querySelector('button[type="submit"]');
            var form = document.forms[0];
            var email = document.querySelector('[name="email"]');
            var password = document.querySelector('[name="password"]');
            btn.addEventListener('click', function(ev){
                if (form.checkValidity()) {
                    btn.querySelector('.signingin').className = 'signingin';
                    btn.querySelector('.signin').className = 'signin hidden';
                } else {
                    ev.preventDefault();
                }
            });
            email.focus();
            document.getElementById('emailGroup').classList.add("focused");

            // Focus events for email and password fields
            email.addEventListener('focusin', function(e){
                document.getElementById('emailGroup').classList.add("focused");
            });
            email.addEventListener('focusout', function(e){
               document.getElementById('emailGroup').classList.remove("focused");
            });

            password.addEventListener('focusin', function(e){
                document.getElementById('passwordGroup').classList.add("focused");
            });
            password.addEventListener('focusout', function(e){
               document.getElementById('passwordGroup').classList.remove("focused");
            });
        </script>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>