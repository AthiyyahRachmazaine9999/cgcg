<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta charset="utf-8">
	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">


	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/global_assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/additional.css') }}" rel="stylesheet" type="text/css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
	<link href="{{ asset('vendor/assets/css/basic.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('vendor/assets/css/dropzone.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->

	<script>
		var token = "{{ csrf_token() }}";
	</script>
	<script src="{{ asset('vendor/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/main/bootstrap.bundle.min.js') }}" defer></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/ui/ripple.min.js') }}" defer></script>
	<!-- /core JS files -->

	<script src="{{ asset('vendor/global_assets/js/plugins/extensions/jquery_ui/core.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/extensions/jquery_ui/effects.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>

	<script src="{{ asset('vendor/global_assets/js/plugins/extensions/cookie.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/editors/summernote/summernote.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/trees/fancytree_all.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/trees/fancytree_childcounter.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/dropzone.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/dropzone.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/ui/perfect_scrollbar.min.js') }}"></script>

	<script src="{{ asset('vendor/global_assets/js/plugins/ui/fullcalendar/core/main.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/ui/fullcalendar/daygrid/main.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/ui/fullcalendar/timegrid/main.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/ui/fullcalendar/list/main.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/ui/fullcalendar/interaction/main.min.js') }}"></script>
	<!-- /core JS files -->


	<!-- Theme JS files -->
	<script src="{{ asset('vendor/global_assets/js/demo_pages/layout_fixed_sidebar_custom.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script src="{{ asset('vendor/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('js/custom.bundle.js') }}"></script>
	<script src="{{ asset('vendor/assets/js/app.js') }}"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
	<!-- /theme JS files -->



</head>

<body>
	@include('layouts.nav')

	<div class="page-content">

		@include('layouts.side')
		<div class="content-wrapper mb-3">
			<div id="Content" value="{{Auth::id()}}" data-division="{{Session::get('division_id')}}" data-count="">
				<div class="content mt-5" id="inidashboard">
				</div>
			</div>
			@yield('content')
			<!-- <router-view></router-view> -->
		</div>
	</div>
	@yield('script')
	<script src="{{ asset('js/notifikasi.js?v=').rand() }}" type="text/javascript"></script>
</body>

</html>