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
    {!! Form::hidden('chose', '', ['id' => 'chose']) !!}
    {!! Form::hidden('info', '', ['id' => 'info']) !!}
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
  var item_ids = {};
  var attr_ids = {};
  var cookie_name = "{{ $store->order_cookie_name }}";
  var demarcation = "{{ $demarcation }}";
  $.cookie.json = true;
  var chose = $.cookie(cookie_name) || {};

  // id對照陣列
  if(items.length){
    for(var key in items) {
      item_ids[items[key]["id"]] = key;
      items[key]["item_attrs"] = items[key]["id"];
      items[key]["price"] = parseFloat(items[key]["price"]);
    }
  }
  if(itemAttrs.length){
    for(var key in itemAttrs) {
      attr_ids[itemAttrs[key]["id"]] = key;
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
  console.log("init:",item_ids, attr_ids, items, itemAttrs);

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
        chose[name]["simple_name"] = items[item_ids[item_id]]["name"];

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
            attr_str = attr_str + demarcation + attr_id + demarcation + attr_key;
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
    var keys = Object.keys(chose);

    $(".chose-count").html("");
    $(".item-three-more").removeClass("item-three-more");
    $(".item-one-more").removeClass("item-one-more");
    $("#chose-table tbody").remove();

    keys.sort();
    for (i = 0; i < keys.length; i++){
      var key = keys[i];
      var _chose = chose[key];
      var tbody = $(".item-" + _chose["id"] + "-tbody");
      var tbody_class = "";
      var _delete = false;

      // 檢查chose是否正確
      // id存在?
      if(tbody.length == 0){
        _delete = true;
      }

      // item內容
      var item = jQuery.extend( {} , items[item_ids[_chose["id"]]] );
      var attr = key.split(demarcation);
      var count = attr.length;
      if(item["name"] != _chose["simple_name"]){
        _delete = true;
      }

      if(count > 1 && (count % 2) == 1 ){
        var max = {};
        for(var i = 1; i < count; i+=2){
          var tmp_attr = itemAttrs[attr_ids[attr[i]]];
    
          // 屬性不存在或不屬於該item
          if(!tmp_attr || tmp_attr["option"][attr[i+1]] === undefined || tmp_attr["item_id"].indexOf( (""+_chose["id"]) ) == -1){  
            _delete = true;
            break;
          }
          max[attr[i]] = (max[attr[i]])? max[attr[i]] : 0;
          max[attr[i]]++;

          item["price"] += parseFloat(tmp_attr["option"][attr[i+1]]);
          //console.log('tmp',tmp_attr,attr[i] , max);
        }

        // 檢查max
        for(var attr_id in max){
          //console.log(_chose , max);
          if(itemAttrs[attr_ids[attr_id]]['max'] && itemAttrs[attr_ids[attr_id]]['max'] < max[attr_id]){
            _delete = true;
            break;
          }
        }
      }

      if(item["price"] != _chose["price"]){
        console.log("111" , item , _chose);
        _delete = true;
      }
     // console.log("item" , item , _chose);
      
      if(_delete){
        console.log("delete" , chose[key] , item);
        delete chose[key];
        continue;
      }

      tmp_item = jQuery.extend({}, items[ item_ids[_chose["id"]] ]);
      tmp_item["item_attrs"] = key;
      tmp_item["name"] = _chose["name"];
      tmp_item["count"] = _chose["count"];
      tmp_item["price"] = _chose["price"];

      // 顯示已買數量
      if(key == tbody.data("item-attrs")){
        $(".item-" + _chose["id"] + "-count").html(_chose["count"]);
        if(_chose["count"] > 2){
          tbody.addClass("item-three-more");
        }
        tbody.addClass("item-one-more");
      }

      if(_chose["count"] > 2){
        tbody_class = tbody_class + " item-three-more";
      }
      tbody_class = tbody_class + " item-one-more";

      tmp_item["tbody_class"] = tbody_class;
     
      tmp_items.push(tmp_item);

      info.money += _chose["price"]*_chose["count"];
      info.count += _chose["count"];
      info.kind++;

      
    }

    $.tmpl( $("#menu-item-tbody").html(), tmp_items ).appendTo( "#chose-table" );
    $(".chose-info").html("總價錢:" + info.money + ",總數量:" + info.count + ",種類:" + info.kind);
    $.cookie(cookie_name, chose, { path: '/' });
    $("input#info").val(JSON.stringify(info));
    $("input#chose").val(JSON.stringify(chose));

    console.log("refresh:" , chose);
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