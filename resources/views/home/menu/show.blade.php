@extends('app')

@section('title')
建立訂單
@stop

@section('content')
<div class="row">
    <div class="col-lg-10">
        <h1 class="page-header">
            <a href="{{ route('store.slug', $store->slug) }}">{{ $store->name }}</a>           
        </h1>                    
    </div>
</div>

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
    {!! Form::hidden('order_id', $order_id) !!}
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
<script src="{{ url( elixir('js/item_my.js') ) }}" type="text/javascript"></script>
@stop