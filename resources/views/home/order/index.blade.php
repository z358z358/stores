@extends('app')

@section('title')
未完成的訂單
@stop

@section('content')
<div id="item">
  <main class="bs-docs-masthead" id="content" role="main" tabindex="-1" v-if="!orders">
    <div class="container">
      <p class="lead">沒有未完成的訂單</p>
    </div>
  </main>
  <div class="table-responsive" v-if="orders">
    <table class="table table-hover table-striped">
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
      <tbody v-repeat="orders">
        <tr>
          <td v-text="id"></td>
          <td v-text="store.name"></td>
          <td v-text="price"></td>
          <td><time class="timeago" title="@{{ updated_at }}" datetime="@{{ updated_at }}"></time></td>
          <td v-text="status_name"></td>
          <td>
            {!! Form::open(['route' => ['order.destroy'], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <button class="btn btn-default" type="button">詳細</button>
            <a class="btn btn-default" href="">修改</a>
            {!! Form::hidden('id', '@{{ id }}') !!}
            {!! Form::hidden('order_token', '@{{ order_token }}') !!}
            {!! Form::submit('刪除', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
          </td>
        </tr>
        <tr class="warning" v-show="showDetail">
          <th>名稱</th>
          <th>單價</th>
          <th>數量</th>
          <th colspan="3">小計</th>
        </tr>
      </tbody>
    </table>
  </div>
  <pre>@{{ $data | json}}</pre>
</div>

@endsection

@section('footer')
@include('partials.tmp')
<script id="order-tbody" type="text/x-handlebars-template">

</script>

<script id="order-tr" type="text/x-handlebars-template">
<tr class="order-tr-detail warning">
    <td>${name}</td>
    <td>${price}</td>
    <td>${count}</td>
    <td colspan="3">${sum}</td>
</tr>
</script>
@stop