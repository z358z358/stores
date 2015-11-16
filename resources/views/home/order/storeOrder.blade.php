@extends('app')

@section('title')
{{ $title }}
@stop

@section('content')
<div id="item">
  <main class="bs-docs-masthead" id="content" role="main" tabindex="-1" v-if="orders.length == 0">
    <div class="container">
      <p class="lead" v-text="'沒有{{ $title }}'"></p>
    </div>
  </main>
  <div class="table-responsive" v-if="orders.length > 0">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>訂單編號</th>
          <th>總價錢</th>
          <th>時間</th>
          <th>狀態</th>
          <th></th>
        </tr>
      </thead>
      <tbody v-for="order in orders">
        <tr>
          <td v-text="order.id"></td>
          <td v-text="order.price | currency"></td>
          <td><time class="timeago" title="@{{ order.updated_at }}" datetime="@{{ order.updated_at }}"></time></td>
          <td v-text="order.status_name"></td>
          <td>
            <button class="btn btn-default" type="button" @click="order.showDetail = !order.showDetail">詳細</button>
            {!! Form::open(['route' => ['order.update'], 'method' => 'put', 'class' => 'form-inline inline-my', 'v-if' => 'order.status < 75']) !!}
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('store_id', '@{{ order.store_id }}') !!}
            {!! Form::hidden('order_token', '@{{ order.order_token }}') !!}
            {!! Form::hidden('step', 'accept') !!}
            {!! Form::submit('接受', ['class' => 'btn btn-warning']) !!}
            {!! Form::close() !!}

            {!! Form::open(['route' => ['order.update'], 'method' => 'put', 'class' => 'form-inline inline-my', 'v-if' => 'order.status < 100']) !!}
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('store_id', '@{{ order.store_id }}') !!}
            {!! Form::hidden('order_token', '@{{ order.order_token }}') !!}
            {!! Form::hidden('step', 'done') !!}
            {!! Form::submit('已付款', ['class' => 'btn btn-success']) !!}
            {!! Form::close() !!}

            {!! Form::open(['route' => ['order.destroy'], 'method' => 'delete', 'class' => 'form-inline inline-my', 'v-if' => 'order.status < 100']) !!}
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('order_token', '@{{ order.order_token }}') !!}
            {!! Form::submit('刪除', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
          </td>
        </tr>
        <tr class="warning" v-show="order.showDetail">
          <th>名稱</th>
          <th>單價</th>
          <th>數量</th>
          <th colspan="3">小計</th>
        </tr>
        <tr class="order-tr-detail warning" v-show="order.showDetail" v-for="_chose in order.content.chose">
          <td v-text="_chose.name"></td>
          <td v-text="_chose.price | currency"></td>
          <td v-text="_chose.count"></td>
          <td colspan="3" v-text="_chose.count*_chose.price | currency"></td>
        </tr>
      </tbody>
    </table>
  </div>

  @if ($orders_page)
  <nav class="text-center">
  {!! $orders_page->render(); !!}
  </nav>
  @endif

  <pre>@{{ $data | json}}</pre>
</div>

@endsection

@section('footer')
@include('partials.tmp')
@stop