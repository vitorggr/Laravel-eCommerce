<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ env(', GOOGLE_ANALYTICS, ') }}');
    </script>
    <?php $directory=null ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Easy Shop</title>
    <meta name="description" content="Modern open-source e-commerce framework for free">
    <meta name="tags" content="modern, opensource, open-source, e-commerce, framework, free, laravel, php, php7, symfony, shop, shopping, responsive, fast, software, blade, cart, test driven, adminlte, storefront">
    <meta name="author" content="Jeff Simons Decena">
    <link href="{{ asset(''.$directory.'css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(''.$directory.'css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(''.$directory.'css/jquery.mCustomScrollbar.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(''.$directory.'css/jquery-ui.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset(''.$directory.'css/header.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(''.$directory.'css/star-rating-svg.css') }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="{{ asset('https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    @yield('css')
    <meta property="og:url" content="{{ request()->url() }}" />
    @yield('og')

</head>

<body>
    <noscript>
        <p class="alert alert-danger">
            You need to turn on your javascript. Some functionality will not work if this is disabled.
            <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
        </p>
    </noscript>
    <div class="super_container">
        @include('layouts.front.header')
        @yield('content')
    </div>
    @include('layouts.front.footer')
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('js/popper.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/easing.js')}}"></script>
    <script src="{{ asset('js/parallax.min.js')}}"></script>
    <script src="{{ asset('js/custom.js')}}"></script>
    <script src="{{ asset('js/owl.carousel.js')}}"></script>
    <script src="{{ asset('js/jquery.colorbox-min.js')}}"></script>
    @yield('js')
</body>

</html>