@extends('app')

@section('title')
{{ $title }}
@stop

@section('content')
<div id="item" class="bind-form">
  <div v-if="msg.length">
    <div class="alert alert-dismissible alert-@{{_msg.type}}" role="alert" v-for="_msg in msg">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong v-text="_msg.title"></strong> <span v-text="_msg.content"></span>
    </div>
  </div>
  <main class="bs-docs-masthead" id="content" role="main" tabindex="-1" v-if="orders == 'none'">
    <div class="container">
      <p class="lead" v-text="'沒有{{ $title }}'"></p>
    </div>
  </main>
  <main class="bs-docs-masthead" id="content" role="main" tabindex="-1" v-if="orders.length == 0">
    <div class="container">
      <p class="lead">讀取中</p>
    </div>
  </main>
  <div class="table-responsive" v-if="orders !== 'none' && orders.length > 0">
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
          <td>
            <i class="fa fa-2x fa-pencil-square-o" title="剛建立訂單，等待訂單接受" v-if="order.status == 50" ></i>
            <i class="fa fa-2x fa-check-square" title="店家已接受，等待確認付款" v-if="order.status == 75" ></i>
            <span class="hidden-sm hidden-xs" v-text="order.status_name"></span>
          </td>
          <td>
            <button class="btn btn-default" type="button" @click="order.show_detail = !order.show_detail">詳細</button>
            {!! Form::open(['route' => ['order.update'], 'method' => 'put', 'class' => 'form-inline inline-my form-ajax', 'v-if' => 'order.status < 75']) !!}
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('store_id', '@{{ order.store_id }}') !!}
            {!! Form::hidden('token', '@{{ order.token }}') !!}
            {!! Form::hidden('step', 'accept') !!}
            {!! Form::submit('接受', ['class' => 'btn btn-warning']) !!}
            {!! Form::close() !!}

            {!! Form::open(['route' => ['order.update'], 'method' => 'put', 'class' => 'form-inline inline-my form-ajax', 'v-if' => 'order.status < 100']) !!}
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('store_id', '@{{ order.store_id }}') !!}
            {!! Form::hidden('token', '@{{ order.token }}') !!}
            {!! Form::hidden('step', 'done') !!}
            {!! Form::submit('已付款', ['class' => 'btn btn-success']) !!}
            {!! Form::close() !!}

            {!! Form::open(['route' => ['order.destroy'], 'method' => 'delete', 'class' => 'form-inline inline-my form-ajax', 'v-if' => 'order.status < 100']) !!}
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('token', '@{{ order.token }}') !!}
            {!! Form::submit('刪除', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
          </td>
        </tr>
        <tr class="warning" v-show="order.show_detail">
          <th>名稱</th>
          <th>單價</th>
          <th>數量</th>
          <th colspan="3">小計</th>
        </tr>
        <tr class="order-tr-detail warning" v-show="order.show_detail" v-for="_chose in order.content_array.chose">
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