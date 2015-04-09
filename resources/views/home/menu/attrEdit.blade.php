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
            <table id="attr-table" class="attr-table table table-striped table-bordered table-hover">
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
<tbody class="attr-tbody">
  <tr class="attr-tr-show">
    <td class="attr-td-name">${name}</td>
    <td>
      <input class="bind-button edit-button btn btn-default" data-action="edit" type="button" value="修改" />
      <input class="bind-button del-button btn btn-danger" data-action="del" type="button" value="刪除" />
    </td>
  </tr>

  <tr class="attr-tr-edit">
    <td><input type="text" name="attr[${id}][name]" data-pre-value="${name}" data-show-id="item-${id}-name" value="${name}" placeholder="名稱" /></td>
    <td>
      {!! Form::select('attr[${id}][item_id][]', $item_list, null, ['class' => 'form-control select2NoTags', 'multiple']) !!}
      <input class="bind-button btn btn-default" data-action="edit_done" type="button" value="確定" />
      <input class="bind-button btn btn-default" data-action="edit_cancel" type="button" value="取消" />
    </td>
  </tr>
</tbody>
</script>
<script type="text/javascript">
$(function() {  
  var max = 0;

  // 新增商品
  $("#attr-add-new").click(function(){
    max++;
    var tmp_attr = {id: max};
    $.tmpl( $("#attr-tbody").html(), tmp_attr )
      .appendTo( "#attr-table" );

    $("#attr-table tbody:last .edit-button").trigger('click');
    resetSelect2();
  });


  function resetSelect2(){
    $(".select2NoTags").select2({
      placeholder: '選擇商品',
      width: '100%'
    });
  }

});
</script>
@stop