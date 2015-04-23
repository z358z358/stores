@extends('app')

@section('title')
未完成的訂單
@stop

@section('content')
@if ($orders)
	@foreach ($orders as $order)
		{{ var_dump($order->content_array) }}
	@endforeach
@endif

@endsection

@section('footer')
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$.removeCookie("{{ $order_cookie_name }}", { path: '/' });

});
</script>
@stop