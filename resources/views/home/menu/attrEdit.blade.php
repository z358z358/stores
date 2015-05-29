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
@include('partials.select2')
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script src="{{ url( elixir('js/item_my.js') ) }}" type="text/javascript"></script>
@stop