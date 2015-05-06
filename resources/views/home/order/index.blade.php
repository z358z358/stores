@extends('app')

@section('title')
未完成的訂單
@stop

@section('content')
@if ($orders)
    <div class="table-responsive">
    <table id="order-table" class="table table-hover table-striped">
        <thead>
            <tr>
                <th>訂單編號</th>
                <th>商店</th>
                <th>總價錢</th>
                <th>建立時間</th>
                <th></th>
            </tr>
        </thead>    
    </table>
</div>
	
@endif

@endsection

@section('footer')
<script id="order-tbody" type="text/x-handlebars-template">
<tbody class="">
  <tr class="">
    <td class="">${id}</td>
    <td class="">${store_name}</td>
    <td class="">${price}</td>
    <td class="">${created_at}</td>
    <td class="">
      <input class="bind-button edit-button btn btn-default" data-action="edit" type="button" value="修改" />
      <input class="bind-button on-button btn btn-default" data-action="remove" type="button" value="下架" />
      <input class="bind-button off-button btn btn-default" data-action="ready" type="button" value="上架" />
      <input class="bind-button del-button btn btn-danger" data-action="del" type="button" value="刪除" />
    </td>
  </tr>

  <tr class="item-tr-edit tr-edit">    
  </tr>
</tbody>
</script>
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script type="text/javascript">
var orders = {!! json_encode($orders) !!};
$(function() {
	$.removeCookie("{{ $order_cookie_name }}", { path: '/' });

    if(orders.length){
        for(var key in orders) {
            orders[key]["store_name"] = orders[key]["store"]["name"];
        }
    }

    $.tmpl( $("#order-tbody").html(), orders ).appendTo( "#order-table" );
});
</script>
@stop