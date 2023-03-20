<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('vendor/other/img/favicon.png') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/other/css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/other/css/fontawesome-all.min.css') }}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/other/font/flaticon.css') }}">
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/other/style.css') }}">
</head>

<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <section class="fxt-template-animation fxt-template-layout21">
        <!-- Animation Start Here -->
		<div id="particles-js"></div>
		<!-- Animation End Here -->
        @yield('content')
    </section>
    <!-- jquery-->
    <script src="{{ asset('vendor/other/js/jquery-3.5.0.min.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ asset('vendor/other/js/popper.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('vendor/other/js/bootstrap.min.js') }}"></script>
    <!-- Imagesloaded js -->
    <script src="{{ asset('vendor/other/js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- Particles js -->
	<script src="{{ asset('vendor/other/js/particles.min.js') }}"></script>
	<script src="{{ asset('vendor/other/js/particles-1.js') }}"></script>
    <!-- Validator js -->
    <script src="{{ asset('vendor/other/js/validator.min.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('vendor/other/js/main.js') }}"></script>

</body>

</html>