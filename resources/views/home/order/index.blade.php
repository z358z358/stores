@extends('app')

@section('title')
未完成的訂單
@stop

@section('content')
@if ($orders)
    <div class="table-responsive">
    <table id="order-table" class="table table-hover table-striped">
        <thead>
            <tr>
                <th>訂單編號</th>
                <th>商店</th>
                <th>總價錢</th>
                <th>時間</th>
                <th>狀態</th>
                <th></th>
            </tr>
        </thead>    
    </table>
</div>

@else

<main class="bs-docs-masthead" id="content" role="main" tabindex="-1">
  <div class="container">
    <p class="lead">沒有未完成的訂單</p>
  </div>
</main>

@endif

@endsection

@section('footer')
<script id="order-tbody" type="text/x-handlebars-template">
<tbody id="order-${id}-tbody" class="" data-order-id="${id}" data-order-key="${key}">
  <tr class="">
    <td class="">${id}</td>
    <td class="">${store_name}</td>
    <td class="">${price}</td>
    <td class=""><time class="timeago" datetime="${updated_at}">${updated_at}</time></td>
    <td class="">${status_name}</td>
    <td class="">
      {!! Form::open(['route' => ['order.destroy'], 'method' => 'delete', 'class' => 'form-inline']) !!}
      <input class="bind-button del-button btn btn-default" data-action="more" type="button" value="詳細" />
      <a class="bind-button edit-button btn btn-default" href="${edit_link}">修改</a>      
      {!! Form::hidden('id', '${id}') !!}
      {!! Form::hidden('order_token', '${order_token}') !!}
      {!! Form::submit('刪除', ['class' => 'del-button btn btn-danger']) !!}
      {!! Form::close() !!}
    </td>
  </tr>
  <tr class="order-tr-detail warning">
    <th>名稱</th>
    <th>單價</th>
    <th>數量</th>
    <th colspan="3">小計</th>
  </tr>
</tbody>
</script>

<script id="order-tr" type="text/x-handlebars-template">
<tr class="order-tr-detail warning">
    <td>${name}</td>
    <td>${price}</td>
    <td>${count}</td>
    <td colspan="3">${sum}</td>
</tr>
</script>

<script src="{{ url( elixir('js/jquery-ui.js') ) }}" type="text/javascript"></script>
<script src="{{ url( elixir('js/order_my.js') ) }}" type="text/javascript"></script>
@stop