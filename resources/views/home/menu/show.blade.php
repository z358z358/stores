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
    <div class="chose-info"></div>
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
    <div class="chose-info"></div>
    {!! Form::submit('結帳', ['class' => 'btn btn-primary form-control']) !!}
    {!! Form::close() !!}
  </div>

</div>
@endsection

@section('footer')
<script id="menu-item-tbody" type="text/x-handlebars-template">
<tbody data-item-id="${id}" data-item-attrs="${item_attrs}" data-item-full-name="${name}" class="item-tbody item-${id}-tbody ui-state-default ${tbody_class}">
  <tr class="menu-item-tr">
    <td id="" class="item-${id}-name col-md-6">
      ${name}<small class="item-${id}-count chose-count"></small>
      <ul class="item-${id}-attrs"></ul>
    </td>
    <td id="item-${id}-price" class="col-md-3"><span class="item-price-total">${price}</span><small class="item-price-str"></small></td>
    <td class="item-${id}-count menu-all-hidden col-md-1">${count}</td>
    <td class="">
      <input class="bind-button btn btn-default" data-action="count" data-value="3" type="button" value="+3" />
      <input class="bind-button btn btn-default" data-action="count" data-value="1" type="button" value="+1" />
      <input class="bind-button btn btn-default hide-my remove-one" data-action="count" data-value="-1" type="button" value="-1" />
      <input class="bind-button btn btn-default hide-my remove-three" data-action="count" data-value="-3" type="button" value="-3" />
      <input type="hidden" name="item_id[]" value="${id}" />
      <input type="hidden" name="count[]" value="${count}" />
      <input type="hidden" name="price[]" value="${price}" />
      <input type="hidden" name="item_attrs[]" value="${item_attrs}" />

    </td>  
  </tr>
</tbody>
</script>
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  var items = {!! json_encode($items) !!};
  var itemAttrs = {!! json_encode($itemAttrs) !!};
  var chose = {};
  var item_ids = {};

  // id對照陣列
  if(items.length){
    for(var key in items) {
      item_ids[items[key]["id"]] = key;
      items[key]["item_attrs"] = items[key]["id"];
      items[key]["price"] = parseFloat(items[key]["price"]);
    }
  }

  if($("#menu-item-tbody").length){
    $.tmpl( $("#menu-item-tbody").html(), items ).appendTo( "#menu-item-table" );
  }

  if(itemAttrs){
    itemAttrs.map(function(itemAttr , index){
      var optionHtml = "";

      if(!$.isEmptyObject(itemAttr["option"])){
        for(var key in itemAttr["option"]){
          var item_id = itemAttr["item_id"][key];
          optionHtml = optionHtml + '<label class="checkbox-inline"><input class="bind-button" data-action="item_attr" data-attr-index="' + index + '" data-attr-id="' + itemAttr['id'] + '" data-attr-key="' + key + '" type="checkbox">' + key + '</label>';
        }
      }
      //console.log(optionHtml);

      if(optionHtml && itemAttr["item_id"].length){
        for(var key in itemAttr["item_id"]){
          var item_id = itemAttr["item_id"][key];
          $(".item-" + item_id + "-attrs").append('<li class="li-attr-' + itemAttr['id'] + '">' + optionHtml + '</li>');
        }
      }
    });
  }
  refreshChose();
  //console.log("init:",item_ids, items, itemAttrs);

  // 綁定按鈕
  $(".menu-item-table").on("click", ".bind-button", function(){
    var action = $(this).data("action");
    var tbody = $(this).closest("tbody");
    var item_id = tbody.data("item-id");
    
    switch(action) {
      // 增加-減少
      case "count":
        var name = tbody.data("item-attrs");
        var value = $(this).data("value");

        chose[name] = (chose[name])? chose[name] : {id:item_id};
        chose[name]["count"] = (chose[name]["count"])? chose[name]["count"] : 0;
        chose[name]["count"] += value;
        chose[name]["price"] = parseFloat( tbody.find(".item-price-total").html() );
        chose[name]["name"] = tbody.data("item-full-name");

        if(isNaN(chose[name]["count"]) || chose[name]["count"] <= 0){
          delete chose[name];
        }

        chose = sortByKey(chose);
      break;

      // 屬性checkbox
      case "item_attr":
        var attr_index = $(this).data("attr-index");
        var attr_id = $(this).data("attr-id");
        var attr_key = $(this).data("attr-key");
        var attr = itemAttrs[attr_index];
        var attr_checked = tbody.find(".li-attr-" + attr_id + " input:checked");

        if(attr && attr['id'] == attr_id){
          // 最多可以勾幾個
          if(attr['max']){
            attr['max'] = parseFloat(attr['max']);
            var nowLength = attr_checked.length;
            //console.log(nowLength, attr['max']);
            if(nowLength > attr['max']){
              return false;
            }
          }

          var price = items[item_ids[item_id]]['price'];
          var price_str = items[item_ids[item_id]]['price'];
          var attr_str = item_id + "";
          var item_name = items[item_ids[item_id]]['name'];

          // 更新屬性
          tbody.find(".item-" + item_id + "-attrs input:checked").each(function(){
            var attr_index = $(this).data("attr-index");
            var attr_id = $(this).data("attr-id");
            var attr_key = $(this).data("attr-key");
            var attr = itemAttrs[attr_index];
            var attr_price = attr['option'][attr_key];

            // 單價變動
            if(attr_price){
              var add_str = (attr_price > 0 )? "+" : "";

              attr_price = parseFloat(attr_price);
              price += attr_price;    
              price_str = price_str + add_str + attr['option'][attr_key];
            }
            attr_str = attr_str + "." + attr_id + "." + attr_key;
            item_name = item_name + "," + attr_key;

          });

          if(price == price_str){
            price_str = "";
          }

          tbody.find(".item-price-total").html(price);
          tbody.find(".item-price-str").html(price_str);
          tbody.data("item-attrs", attr_str);
          tbody.data("item-full-name", item_name);
          //console.log(price);    
        }
      break;
    }
    refreshChose();
    console.log("switch:",chose);
  });

  function refreshChose(){
    var name, item_id, tmp_item,  tmp_items = [];
    var info = {"money": 0, "count": 0, "kind": 0};

    $(".chose-count").html("");
    $(".item-three-more").removeClass("item-three-more");
    $(".item-one-more").removeClass("item-one-more");
    $("#chose-table tbody").remove();

    for(var key in chose){
      //console.log(key);
      var _chose = chose[key];
      var tbody = $(".item-" + _chose["id"] + "-tbody");
      var tbody_class = "";

      tmp_item = jQuery.extend({}, items[ item_ids[_chose["id"]] ]);
      tmp_item["item_attrs"] = key;
      tmp_item["name"] = _chose["name"];
      tmp_item["count"] = _chose["count"];
      tmp_item["price"] = _chose["price"];

      // 顯示已買數量
      if(key == tbody.data("item-attrs")){
        $(".item-" + _chose["id"] + "-count").html(tmp_item["count"]);
        if(tmp_item["count"] > 2){
          tbody.addClass("item-three-more");
          tbody_class = tbody_class + " item-three-more";
        }
        tbody.addClass("item-one-more");
        tbody_class = tbody_class + " item-one-more";
      }
      tmp_item["tbody_class"] = tbody_class;
     
      tmp_items.push(tmp_item);

      info.money += tmp_item["price"]*tmp_item["count"];
      info.count += tmp_item["count"];
      info.kind++;

      
    }

    $.tmpl( $("#menu-item-tbody").html(), tmp_items ).appendTo( "#chose-table" );
    $(".chose-info").html("總價錢:" + info.money + ",總數量:" + info.count + ",種類:" + info.kind);

    //console.log("refresh:" , tmp_items);
  }

  function sortByKey(myObj){
    myObj = jQuery.extend({}, myObj);
    var keys = Object.keys(myObj);
    var newObj = {};
    keys.sort();
    for (i = 0; i < keys.length; i++)
    {
        k = keys[i];
        newObj[k] = myObj[k];
    }
    return newObj;
  }

});
</script>
@stop