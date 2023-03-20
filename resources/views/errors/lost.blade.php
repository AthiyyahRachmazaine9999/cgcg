<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="maleser" />
    <meta name="keywords" content="maleser" />
    <meta name="description" content="maleser - Error page" />

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('vendor/lost/css/style.css') }}">

    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" media="all" href="css/ie.css" />
    <script type="text/javascript" src="js/html5.js" ></script>
    <![endif]-->

    <!-- Javascripts -->
    <script type="text/javascript" charset="utf-8" src="{{ asset('vendor/lost/js/jquery-1.8.3.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('vendor/lost/js/plax.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('vendor/lost/js/404.js') }}"></script>
</head>

<body id="errorpage" class="error404">
    <div id="pagewrap">

        <!--page content-->
        <div id="wrapper" class="clearfix">
            <div id="parallax_wrapper">
                <div id="content">
                    <h1>Oops Sorry<br />@yield('code') @yield('message') </h1>
                    <a href="{{ url('') }}" title="" class="button">Go Home</a>
                </div>
                <!--parallax-->
                <span class="scene scene_1"></span>
                <span class="scene scene_2"></span>
                <span class="scene scene_3"></span>
            </div>
        </div>

    </div><!-- end pagewrap -->

</body>

</html>