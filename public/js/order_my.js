
// home/order/index.blade.php
$(function() {
    if(__act !== "home.order.index") return;
    var detail = {};
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


// home/order/storeOrder.blade.php
$(function() {
    if(__act !== "home.order.storeOrder") return;

    var detail = {};
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

// home/order/storeOrderFinish.blade.php
$(function() {
    if(__act !== "home.order.storeOrderFinish") return;
    var detail = {};
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

});