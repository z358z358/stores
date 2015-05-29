
// home/menu/show.blade.php
$(function() {
  if(__act !== "home.menu.show") return;

  var item_ids = {};
  var attr_ids = {};
  var cookie_name = "{{ $store->order_cookie_name }}";
  var demarcation = "{{ $demarcation }}";
  $.cookie.json = true;
  var chose = $.cookie(cookie_name) || {};
  if(orderChose){
    chose = orderChose;
  }

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
  //console.log("init:",item_ids, attr_ids, items, itemAttrs);

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
            attr_price = parseFloat(attr_price);

            // 單價變動
            if(attr_price){
              var add_str = (attr_price > 0 )? "+" : "";
              
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
    //console.log("switch:",chose);
  });

  function refreshChose(){
    var name, item_id, tmp_item,  tmp_items = [];
    var info = {"price": 0, "count": 0, "kind": 0};
    var keys = Object.keys(chose);

    $(".chose-count").html("");
    $(".item-three-more").removeClass("item-three-more");
    $(".item-one-more").removeClass("item-one-more");
    $("#chose-table tbody").remove();

    keys.sort();
    for (var key_i = 0; key_i < keys.length; key_i++){
      var key = keys[key_i];
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
        //console.log("111" , item , _chose);
        _delete = true;
      }
     // console.log("item" , item , _chose);
      
      if(_delete){
        //console.log("delete" , chose[key] , item);
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
     //console.log("tmp_item", tmp_item);
      tmp_items.push(tmp_item);

      info.price += _chose["price"]*_chose["count"];
      info.count += _chose["count"];
      info.kind++;

      
    }

    $.tmpl( $("#menu-item-tbody").html(), tmp_items ).appendTo( "#chose-table" );
    $(".chose-info").html("總價錢:" + info.price + ",總數量:" + info.count + ",種類:" + info.kind);
    $.cookie(cookie_name, chose, { path: '/' });
    $("input#info").val(JSON.stringify(info));
    $("input#chose").val(JSON.stringify(chose));

    //console.log("refresh:" , chose);
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

// home/menu/attrEdit.blade.php
$(function() {  
  if(__act !== "home.menu.attrEdit") return;
  var max = 0;

  attrs.forEach(function(attr){
     var attr_id = parseInt(attr.id);
     max = (attr_id > max)?attr_id:max;
     attr.optionHtml = '';
     for(var name in attr.option){
      attr.optionHtml = optionHtml({id:attr_id, name:name, price:attr.option[name]});
     }
   }.bind(max));

  $.tmpl( $("#attr-tbody").html(), attrs ).appendTo( "#attr-table" );

  // init
  // 把select item加上selected
  $(".attr-item-select").each(function(){
    var selected = ($(this).data('pre-value') + "").split(",");

    if(selected){
      for(var key in selected){
        $(this).find("option[value='" + selected[key] + "']").attr("selected","true");
      }
    }
  });

  // option加上html
  attrs.forEach(function(attr){
     var attr_id = parseInt(attr.id);
     var html = '';
     for(var name in attr.option){
      html += optionHtml({id:attr_id, name:name, price:attr.option[name]});
     }
     $("#attr-tbody-"+attr_id+" .div-option").html(html);
   });

  resetSelect2();
  // end init

  // 新增商品
  $("#attr-add-new").click(function(){
    max++;
    var tmp_attr = {id: max};
    $.tmpl( $("#attr-tbody").html(), tmp_attr )
      .appendTo( "#attr-table" );

    $("#attr-table tbody:last .edit-button").trigger('click');
    resetSelect2();
  });

  // 綁定按鈕
  $(".attr-table").on("click", ".bind-button", function(){
    var action = $(this).data("action");
    var tbody = $(this).closest("tbody");
    switch(action) {
      // 編輯
      case "edit":
        tbody.find(".tr-show").hide();
        tbody.find(".tr-edit").show();
      break;

      // 編輯確定
      case "edit_done":
        tbody.find(".tr-show").show();
        tbody.find(".tr-edit").hide();

        tbody.find("input").each( function(){
          $(this).data("pre-value", $(this).val());
          var tmpId = $(this).data("show-id");
          if(tmpId){
            $("#"+tmpId).html($(this).val());
          }
        });

        tbody.find(".select2NoTags").each( function(){
          $(this).data("pre-value", $(this).val());  
        });

      break;

      // 編輯取消
      case "edit_cancel":
        tbody.find(".tr-show").show();
        tbody.find(".tr-edit").hide();

        tbody.find("input").each( function(){
          var preValue = $(this).data("pre-value");
          if(preValue) $(this).val(preValue);
        });

        tbody.find(".select2NoTags").each( function(){
          var preValue = $(this).data("pre-value");
          if(preValue) $(this).select2("val", preValue);
        });
        //resetSelect2();

      break;

      // 刪除
      case "del":
        tbody.remove();
      break;

      // 新選項
      case "option_new":
        var id = tbody.data('id');
        var html = optionHtml({id:id});
        tbody.find(".div-option").append(html);
      break;

      // 刪除選項
      case "option_del":
        $(this).closest(".option-row").remove();
      break;
    }
  });

  function resetSelect2(){
    $(".select2NoTags").select2({
      placeholder: '選擇商品',
      width: '100%'
    });
  }

  function optionHtml(data){
    if(!data || !data.id) return '';
    data.name = (data.name) ? data.name : '';
    data.price = (data.price) ? data.price : '';

    return '<div class="form-group row option-row"><div class="col-md-3"><input id="attr-option-name-' + data.id + '" type="text" class="form-control" name="attr[' + data.id + '][option][name][]" value="' + data.name + '" placeholder="名稱"></div><div class="col-md-2"><input id="attr-option-price-' + data.id + '" type="number" class="form-control" name="attr[' + data.id + '][option][price][]" value="' + data.price + '" placeholder="單價變動"></div><div class="col-xs-2"><input class="bind-button del-button btn btn-danger" data-action="option_del"  type="button" value="刪除"></div></div>';
  }

});

// home/menu/edit.blade.php
$(function() {  
  if(__act !== "home.menu.edit") return;
  var max = 0;

  items.forEach(function(item){
     item["item_id"] = item.id;
     var item_id = parseInt(item.item_id);
     max = (item_id > max)?item_id:max;
   }.bind(max));

  $.tmpl( $("#item-tbody").html(), items ).appendTo( "#item-table" );
  onAndOff();

  $( ".sortable" ).sortable();
  $( ".sortable" ).disableSelection();

  // 新增商品
  $("#item-add-new").click(function(){
    max++;
    var tmp_item = {item_id:max, status:100};
    $.tmpl( $("#item-tbody").html(), tmp_item )
      .appendTo( "#item-table" );
    $("#item-table tbody:last .edit-button").trigger('click');
  });

  // 綁定按鈕
  $(".item-table").on("click", ".bind-button", function(){
    var action = $(this).data("action");
    var tbody = $(this).closest("tbody");
    switch(action) {
      // 編輯
      case "edit":
        tbody.find(".tr-show").hide();
        tbody.find(".tr-edit").show();
      break;

      // 編輯確定
      case "edit_done":
        tbody.find(".tr-show").show();
        tbody.find(".tr-edit").hide();

        tbody.find("input").each( function(){
          $(this).data("pre-value", $(this).val());
          var tmpId = $(this).data("show-id");
          if(tmpId){
            $("#"+tmpId).html($(this).val());
          }
        });

      break;

      // 編輯取消
      case "edit_cancel":
        tbody.find(".tr-show").show();
        tbody.find(".tr-edit").hide();

        tbody.find("input").each( function(){
          var preValue = $(this).data("pre-value");
          if(preValue) $(this).val(preValue);
        });

      break;

      // 下架
      case "remove":
        tbody.find(".item-stauts").val('-1');
        onAndOff();
      break;

       // 下架
      case "ready":
        tbody.find(".item-stauts").val('1');
        onAndOff();
      break;

      // 刪除
      case "del":
        tbody.remove();
      break;
    }
  });

  function onAndOff(){
    $("#item-table .item-tbody").each(function(){
      if($(this).find(".item-stauts").val() <= 0){
        $(this).prependTo("#item-table-remove");
      }

    });

    $("#item-table-remove .item-tbody").each(function(){
      if($(this).find(".item-stauts").val() > 0){
        $(this).appendTo("#item-table");
      }
    });

    $("#item-panel-remove").show();
    if($("#item-table-remove .item-tbody").length == 0){
      $("#item-panel-remove").hide();
    }
  }

});