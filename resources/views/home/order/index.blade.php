@extends('app')

@section('title')
未完成的訂單
@stop

@section('content')
@if ($orders)
<div class="table-responsive">
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>訂單編號</th>
            <th>商店</th>
            <th>建立時間</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($orders as $order)
		<tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->store->name }}</td>
            <td>{{ $order->created_at }}</td>
        </tr>
	@endforeach
    </tbody>
</table>
</div>
	
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