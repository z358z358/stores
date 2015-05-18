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
                <th>時間</th>
                <th></th>
            </tr>
        </thead>    
    </table>
</div>
	
@endif

@endsection

@section('footer')
<script id="order-tbody" type="text/x-handlebars-template">
<tbody id="order-${id}-tbody" class="" data-order-id="${id}" data-order-key="${key}">
  <tr class="">
    <td class="">${id}</td>
    <td class="">${store_name}</td>
    <td class="">${price}</td>
    <td class=""><time class="timeago" datetime="${updated_at}">${updated_at}</time></td>
    <td class="">
      <input class="bind-button del-button btn btn-default" data-action="more" type="button" value="顯示/隱藏 詳細內容" />
      <a class="bind-button edit-button btn btn-default" href="${edit_link}">修改</a>
      <input class="bind-button del-button btn btn-danger" data-action="del" type="button" value="刪除" />
    </td>
  </tr>

  <tr class="order-tr-detail warning">
    <th>名稱</th><th>單價</th><th>數量</th><th colspan="2">小計</th>
  </tr>
  
</tbody>
</script>

<script id="order-tr" type="text/x-handlebars-template">
<tr class="order-tr-detail warning">
    <td>${name}</td><td>${price}</td><td>${count}</td><td colspan="2">${sum}</td>
</tr>
</script>

<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script type="text/javascript">
var orders = {!! json_encode($orders) !!};
var detail = {};
$(function() {
    
	$.removeCookie("{{ $order_cookie_name }}", { path: '/' });

    if(orders.length){
        for(var key in orders){
            var order_id = orders[key]["id"];
            orders[key]["key"] = key;
            orders[key]["store_name"] = orders[key]["store"]["name"];
            orders[key]["edit_link"] = "{{ url('/') }}" + "/" + orders[key]["store"]["slug"] + "/menu?type=edit&id=" + order_id + "&created_at=" + orders[key]["created_at"];

            var content = JSON.parse(orders[key]["content"]);
            if(content && content["clear"]){
                for(var key2 in content["clear"]){
                    detail[order_id] = detail[order_id] || [];
                    var tmp = content["clear"][key2];

                    detail[order_id].push({
                        name: tmp["name"],
                        price: tmp["price"],
                        count: tmp["count"],
                        sum: tmp["price"]*tmp["count"]
                    });
                }
            }
        }
    }

    $.tmpl( $("#order-tbody").html(), orders ).appendTo( "#order-table" );

    if(detail){
        for(var order_id in detail){
            $.tmpl( $("#order-tr").html(), detail[order_id] ).appendTo( "#order-" + order_id + "-tbody" );
        }
        if(Object.keys(detail).length >= 1){
            $(".order-tr-detail").hide();
        }
    }

      // 綁定按鈕
  $("#order-table").on("click", ".bind-button", function(){
    var action = $(this).data("action");
    var tbody = $(this).closest("tbody");
    var order_id = tbody.data("order-id");
    
    switch(action) {
      // 增加-減少
      case "more":
        tbody.find(".order-tr-detail").toggle();
      break;
    }
  });

});
</script>
@stop