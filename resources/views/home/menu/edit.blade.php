@extends('app')

@section('title')
修改商店資料
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
				    <div class="panel-heading">修改商店資料</div>
				    <div class="panel-body">
            {!! Form::model($store, ['route' => ['menu.update', $store->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}

						    <div class="dataTable_wrapper">
                    <table id="item-table" class="item-table sortable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>名稱</th>
                            <th>單價</th>
                            <th></th>
                        </tr>
                    </thead>
                  </table>
                  <input id="item-add-new" type="button" value="新增商品" />
                </div>
                {!! Form::submit('送出', ['class' => 'btn btn-primary form-control']) !!}
            {!! Form::close() !!}
            </div>
				</div>

        </div>
    </div>
</div>
@endsection

@section('footer')
<script id="item-tbody" type="text/x-handlebars-template">
<tbody data-item-id="${item_id}" class="ui-state-default">
  <tr class="item-tr-show">
    <td id="item-${item_id}-name">${name}</td>
    <td id="item-${item_id}-price">${price}</td>
    <td>
      <input class="bind-button edit-button" data-action="edit" type="button" value="修改" />
      <input class="bind-button" data-action="del" type="button" value="刪除" />
    </td>
  </tr>

  <tr class="item-tr-edit">
    <td><input type="text" name="items[${item_id}][name]" data-pre-value="${name}" data-show-id="item-${item_id}-name" value="${name}" placeholder="名稱" /></td>
    <td><input type="number" name="items[${item_id}][price]" data-pre-value="${price}" data-show-id="item-${item_id}-price" value="${price}" placeholder="單價" /></td>
    <td>
      <input type="hidden" name="items[${item_id}][item_id]" value="${item_id}" />
      <input type="hidden" name="items[${item_id}][status]" value="${status}" />
      <input class="bind-button" data-action="edit_done" type="button" value="確定" />
      <input class="bind-button" data-action="edit_cancel" type="button" value="取消" />
    </td>
  </tr>
</tbody>
</script>
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  var items = {!! json_encode($items) !!};
  var max = 0;

  items.forEach(function(item){
     var item_id = parseInt(item.item_id);
     max = (item_id > max)?item_id:max;
   }.bind(max));

  $.tmpl( $("#item-tbody").html(), items )
      .appendTo( "#item-table" );

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
  $("#item-table").on("click", ".bind-button", function(){
    var action = $(this).data("action");
    var tbody = $(this).closest("tbody");
    switch(action) {
      // 編輯
      case "edit":
        tbody.find(".item-tr-show").hide();
        tbody.find(".item-tr-edit").show();
      break;

      // 編輯確定
      case "edit_done":
        tbody.find(".item-tr-show").show();
        tbody.find(".item-tr-edit").hide();

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
        tbody.find(".item-tr-show").show();
        tbody.find(".item-tr-edit").hide();

        tbody.find("input").each( function(){
          var preValue = $(this).data("pre-value");
          if(preValue) $(this).val(preValue);
        });

      break;

      // 刪除
      case "del":
        tbody.remove();
      break;
    }
    console.log(action , tbody);
  });

});
</script>
@stop