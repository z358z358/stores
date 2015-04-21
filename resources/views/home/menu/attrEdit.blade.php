@extends('app')

@section('title')
修改商品屬性
@stop

@section('content')
<div class="container-fluid">  
  @include('home.menu.nav')          
  {!! Form::model($store, ['route' => ['menu.attr.update', $store->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">修改屬性</div>
        <div class="panel-body">
          <div class="dataTable_wrapper">
            <table id="attr-table" class="attr-table form-table table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>名稱</th>
                  <th></th>
              </tr>
              </thead>
            </table>
          <input id="attr-add-new" type="button" value="新增屬性" />
        </div>              
      </div>
    </div>

  </div>
  {!! Form::submit('送出', ['class' => 'btn btn-primary form-control']) !!}
  {!! Form::close() !!}
</div>
@endsection

@section('footer')
@include('partials.select2')
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script id="attr-tbody" type="text/x-handlebars-template">
<tbody id="attr-tbody-${id}" class="attr-tbody" data-id="${id}">
  <tr class="attr-tr-show tr-show">
    <td id="attr-${id}-name" class="attr-td-name col-md-3">${name}</td>
    <td class="col-md-9">
      <input class="bind-button edit-button btn btn-default" data-action="edit" type="button" value="修改" />
      <input class="bind-button del-button btn btn-danger" data-action="del" type="button" value="刪除" />
    </td>
  </tr>

  <tr class="attr-tr-edit tr-edit">
    <td class="col-md-3"><input type="text" name="attr[${id}][name]" data-pre-value="${name}" data-show-id="attr-${id}-name" value="${name}" placeholder="名稱" /></td>
    <td class="col-md-9">

      <div class="div-option"></div>
      <input class="bind-button btn btn-default" data-action="option_new" type="button" value="新增選項" />
      <input type="number" name="attr[${id}][max]" data-pre-value="${max}" value="${max}" placeholder="最多可以選幾個" />
      {!! Form::select('attr[${id}][item_id][]', $item_list, null, ['data-pre-value' => '${item_id}', 'class' => 'attr-item-select form-control select2NoTags', 'multiple']) !!}

      <input type="hidden" name="attr[${id}][attr_id]" value="${attr_id}" />
      <input class="bind-button btn btn-default" data-action="edit_done" type="button" value="確定" />
      <input class="bind-button btn btn-default" data-action="edit_cancel" type="button" value="取消" />
    </td>
  </tr>
</tbody>
</script>
<script type="text/javascript">
$(function() {  
  var max = 0;
  var attrs = {!! json_encode($attrs) !!};

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
</script>
@stop