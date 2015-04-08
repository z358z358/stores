@extends('app')

@section('title')
修改商店資料
@stop

@section('content')
<ul id="myTab" class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#menu" id="menu-tab" role="tab" data-toggle="tab" aria-controls="menu" aria-expanded="true">Menu</a></li>
  <li role="presentation" class=""><a href="#chose" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">已選</a></li>
</ul>
<div id="myTabContent" class="tab-content">
  <div role="tabpanel" class="tab-pane fade active in" id="menu" aria-labelledby="menu-tab">
    <table id="menu-item-table" class="menu-item-table table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>名稱</th>
          <th>單價</th>
          <th></th>
      </tr>
      </thead>
    </table>
  </div>

  <div role="tabpanel" class="tab-pane fade" id="chose" aria-labelledby="chose-tab">
    {!! Form::model($store, ['route' => ['menu.submit', $store->slug], 'method' => 'post', 'class' => 'form-horizontal']) !!}
    <table id="chose-table" class="menu-item-table table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>名稱</th>
          <th>單價</th>
          <th>數量</th>
          <th></th>
      </tr>
      </thead>
    </table>
    <div id="chose-info"></div>
    {!! Form::submit('結帳', ['class' => 'btn btn-primary form-control']) !!}
    {!! Form::close() !!}
  </div>

</div>
@endsection

@section('footer')
<script id="menu-item-tbody" type="text/x-handlebars-template">
<tbody data-item-id="${item_id}" class="item-tbody ui-state-default">
  <tr class="menu-item-tr">
    <td id="item-${item_id}-name">${name}<small class="item-${item_id}-count chose-count"></small></td>
    <td id="item-${item_id}-price">${price}</td>
    <td class="item-${item_id}-count menu-all-hidden">${count}</td>
    <td>
      <input class="bind-button btn btn-default" data-action="count" data-value="3" type="button" value="+3" />
      <input class="bind-button btn btn-default" data-action="count" data-value="1" type="button" value="+1" />
      <input class="bind-button btn btn-default one-more" data-action="count" data-value="-1" type="button" value="-1" />
      <input class="bind-button btn btn-default three-more" data-action="count" data-value="-3" type="button" value="-3" />
      <input type="hidden" name="item_id[]" value="${item_id}" />
      <input type="hidden" name="count[]" value="${count}" />
      <input type="hidden" name="price[]" value="${price}" />

    </td>  
  </tr>
</tbody>
</script>
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  var items = {!! json_encode($items) !!};
  var chose = {};

  if($("#menu-item-tbody").length){
    $.tmpl( $("#menu-item-tbody").html(), items ).appendTo( "#menu-item-table" );
  }
console.log(items);
  refreshChose();

  // 綁定按鈕
  $(".menu-item-table").on("click", ".bind-button", function(){
    var action = $(this).data("action");
    var tbody = $(this).closest("tbody");
    var id = tbody.data("item-id");
    var name = "item" + id;
    switch(action) {
      // 增加-減少
      case "count":
        var value = $(this).data("value");
        chose[name] = (chose[name])? chose[name] : 0;
        chose[name] += value;
        if(isNaN(chose[name]) || chose[name] <= 0){
          delete chose[name];
        }
        var count = (chose[name]) ? chose[name] : "";
        $(".item-" + id + "-count").html(count);
        refreshChose();
      break;
    }
    console.log(chose ,action);
  });

  function refreshChose(){
    var name, id, tmp_item,  tmp_items = [];
    var info = {"money": 0, "count": 0, "kind": 0};

    items.map(function(item){
      id = item.item_id;
      name = "item" + id;
      if(chose[name] >= 0){
        tmp_item = item;
        tmp_item["count"] = chose[name];
        tmp_items.push(tmp_item);

        info.money += item.price*chose[name];
        info.count += chose[name];
        info.kind++;
      }
    });

    $("#chose-table tbody").remove();
    $.tmpl( $("#menu-item-tbody").html(), tmp_items ).appendTo( "#chose-table" );
    $("#chose-info").html("總價錢:" + info.money + ",總數量:" + info.count + ",總類:" + info.kind);

  }

});
</script>
@stop