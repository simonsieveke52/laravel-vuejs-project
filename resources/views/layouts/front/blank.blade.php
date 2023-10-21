<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script async defer>
            var resource = document.createElement('link'); 
            resource.setAttribute("rel", "stylesheet");
            resource.setAttribute('href', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap');
            document.getElementsByTagName('head')[0].appendChild(resource);
        </script>
        <title>@yield('title', config('app.name'))</title>
        @section('seo')
            <meta name="title" content="@yield('title', config('app.name'))">
            <meta name="description" content="">
            <meta property="og:title" content="@yield('title', config('app.name'))"/>
            <meta property="og:description" content="@yield('description', '')"/>
            <meta property="og:type" content=""/>
            <meta property="og:image" content=""/>
        @show
        <meta property="og:site_name" content="{{ config('app.name') }}"/>
        <meta property="og:url" content="{{ request()->url() }}"/>
        <meta property="og:locale" content="en_GB" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=290, initial-scale=0.8, user-scalable=no">
        <meta name="_token" content="{{ csrf_token() }}" />

        <!-- generics -->
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-32.png') }}" sizes="32x32">
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-128.png') }}" sizes="128x128">
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-152.png') }}" sizes="152x152">
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-167.png') }}" sizes="167x167">   
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-180.png') }}" sizes="180x180">
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-192.png') }}" sizes="192x192">
        <link type="image/png" rel="icon" href="{{ asset('favicons/favicon-196.png') }}" sizes="196x196">

        <!-- Android -->
        <link type="image/png" rel="shortcut icon" sizes="196x196" href="{{ asset('favicons/favicon-196.png') }}">

        <!-- iOS -->
        <link type="image/png" rel="apple-touch-icon" href="{{ asset('favicons/favicon-152.png') }}" sizes="152x152">
        <link rel="apple-touch-icon" href="{{ asset('favicons/favicon-180.png') }}" sizes="180x180">

        <!-- Windows 8 IE 10-->
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/favicon-144.png') }}">
        <meta name="msapplication-config" content="{{ asset('favicons/browserconfig.xml') }}" />
        <link rel="preconnect" href="//www.google-analytics.com">

        <link rel="manifest" href="/manifest.json">

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ config('default-variables.gtm') }}');</script>
        <!-- End Google Tag Manager -->
        @yield('javascript')
        @stack('stylesheet')
        @yield('css')
        <style>
            .spinner-pump{width: 40px; height: 40px; position: relative;}
            .double-bounce1,.double-bounce2{width: 100%; height: 100%; border-radius: 50%; background-color: #333; opacity: 0.6; position: absolute; top: 0; left: 0; -webkit-animation: sk-bounce 2s infinite ease-in-out; animation: sk-bounce 2s infinite ease-in-out;}
        </style>
    </head>
    <body class="@yield('body_class')">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('default-variables.gtm') }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <div id="app" class="mx-auto max-w-1440px">

            <div class="container-fluid fixed-top max-w-1440px mx-auto">
                <div class="row">
                    <div class="col-12 px-0 text-center">
                        @include('layouts.errors-and-messages')
                    </div>
                </div>
            </div>

            @include('front.shared.header')
            <div>
                <div class="jq-page-content">
                    @section('content')
                    @show
                </div>
            </div>
            @include('front.shared.footer')
            @include('front.shared.modals')
        </div>
        @yield('js')
        <script>
            window.categoryBrowserUrl = @json(route('category-ajax.filter'))
        </script>
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>