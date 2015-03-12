<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	<link rel="stylesheet" type="text/css" href="{{ url( elixir('css/all.css') ) }}">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	@include('partials.nav')
	@include('flash::message')
    @include('partials.errors')

	{{--<div class="container">--}}
		{{-- @include('flash::message') --}}

		@yield('content')
	{{--</div>--}}

	<!-- Scripts -->
	<script src="{{ url( elixir('js/all.js') ) }}" type="text/javascript"></script>

	<script type="text/javascript">
		$('#flash-overlay-modal').modal();
	</script>

	@if(Config::get('app.debug'))
	<script type="text/javascript">
	    var queries = {{ json_encode(DB::getQueryLog()) }};
	    console.log('/****************************** Database Queries ******************************/');
	    console.log(' ');
	    queries.forEach(function(query) {
	        console.log('   ' + query.time + ' | ' + query.query + ' | ' + query.bindings[0]);
	    });
	    console.log(' ');
	    console.log('/****************************** End Queries ***********************************/');
	</script>
	@endif
</body>
</html>
