@extends('app')

@section('title')
修改商店商品
@stop

@section('content')
<div class="container-fluid">  
  @include('home.menu.nav')          
  {!! Form::model($store, ['route' => ['menu.update', $store->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">修改商品</div>
        <div class="panel-body">
          <div class="dataTable_wrapper">
            <table id="item-table" class="item-table form-table sortable table table-striped table-bordered table-hover">
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
      </div>
    </div>

    <div id="item-panel-remove" class="panel panel-default">
      <div class="panel-heading">已下架的商品</div>
      <div class="panel-body">
        <div class="dataTable_wrapper">
          <table id="item-table-remove" class="item-table form-table sortable table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>名稱</th>
                <th>單價</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

  </div>
  {!! Form::submit('送出', ['class' => 'btn btn-primary form-control']) !!}
  {!! Form::close() !!}
</div>
@endsection

@section('footer')
<script id="item-tbody" type="text/x-handlebars-template">
<tbody data-item-id="${item_id}" class="item-tbody ui-state-default">
  <tr class="item-tr-show tr-show">
    <td id="item-${item_id}-name" class="col-md-6">${name}</td>
    <td id="item-${item_id}-price" class="col-md-3">${price}</td>
    <td class="col-md-3">
      <input class="bind-button edit-button btn btn-default" data-action="edit" type="button" value="修改" />
      <input class="bind-button on-button btn btn-default" data-action="remove" type="button" value="下架" />
      <input class="bind-button off-button btn btn-default" data-action="ready" type="button" value="上架" />
      <input class="bind-button del-button btn btn-danger" data-action="del" type="button" value="刪除" />
    </td>
  </tr>

  <tr class="item-tr-edit tr-edit">
    <td class="col-md-6"><input type="text" name="items[${item_id}][name]" data-pre-value="${name}" data-show-id="item-${item_id}-name" value="${name}" placeholder="名稱" /></td>
    <td class="col-md-3"><input type="number" name="items[${item_id}][price]" data-pre-value="${price}" data-show-id="item-${item_id}-price" value="${price}" placeholder="單價" /></td>
    <td class="col-md-3">
      <input type="hidden" name="items[${item_id}][item_id]" value="${item_id}" />
      <input type="hidden" name="items[${item_id}][id]" value="${id}" />
      <input class="item-stauts" type="hidden" name="items[${item_id}][status]" value="${status}" />
      <input class="bind-button btn btn-default" data-action="edit_done" type="button" value="確定" />
      <input class="bind-button btn btn-default" data-action="edit_cancel" type="button" value="取消" />
    </td>
  </tr>
</tbody>
</script>
<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script src="{{ url( elixir('js/item_my.js') ) }}" type="text/javascript"></script>
@stop