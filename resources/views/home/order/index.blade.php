@extends('app')

@section('title')
未完成的訂單
@stop

@section('content')
<div id="item" class="col-md-10 col-md-offset-1">
  <main class="bs-docs-masthead" id="content" role="main" tabindex="-1" v-if="!orders">
    <div class="container">
      <p class="lead">沒有未完成的訂單</p>
    </div>
  </main>
  <div class="" v-if="orders">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th class="col-md-1 col-sm-1 col-xs-1">編號</th>
          <th class="col-md-3 col-sm-3 col-xs-3">商店</th>
          <th class="col-md-2 col-sm-2 col-xs-2">總價錢</th>
          <th class="col-md-1 col-sm-2 col-xs-2">時間</th>
          <th class="col-md-2 col-sm-1 col-xs-1">狀態</th>
          <th class="col-md-3 col-sm-3 col-xs-3"></th>
        </tr>
      </thead>
      <tbody v-for="order in orders">
        <tr>
          <td v-text="order.id"></td>
          <td v-text="store.name"></td>
          <td v-text="order.price | currency | removeZero"></td>
          <td><time class="timeago" title="@{{ order.updated_at }}" datetime="@{{ order.updated_at }}"></time></td>
          <td>
            <i class="fa fa-2x fa-pencil-square-o" title="剛建立訂單，等待訂單接受" v-if="order.status == 50" ></i>
            <i class="fa fa-2x fa-check-square" title="店家已接受，等待確認付款" v-if="order.status == 75" ></i>
            <span class="hidden-sm hidden-xs" v-text="order.status_name"></span>
          </td>
          <td class="btn-chose">
            {!! Form::open(['route' => ['order.destroy'], 'method' => 'delete', 'class' => 'form-inline']) !!}
            <button class="btn btn-default" type="button" @click="order.showDetail = !order.showDetail">詳細</button>
            <a class="btn btn-default" href="{{ route('order.editById',"") }}/@{{ order.id }}/@{{ order.created_at}}">修改</a>
            {!! Form::hidden('id', '@{{ order.id }}') !!}
            {!! Form::hidden('order_token', '@{{ order.order_token }}') !!}
            {!! Form::submit('刪除', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
          </td>
        </tr>
        <tr class="warning" v-show="order.showDetail">
          <th class="col-md-6 col-sm-6 col-xs-6" colspan="3">名稱</th>
          <th class="col-md-1 col-sm-1 col-xs-1">單價</th>
          <th class="col-md-3 col-sm-3 col-xs-3">數量</th>
          <th class="col-md-2 col-sm-2 col-xs-2">小計</th>
        </tr>
        <tr class="order-tr-detail warning" v-show="order.showDetail" v-for="_chose in order.content.chose">
          <td colspan="3" v-text="_chose.name"></td>
          <td v-text="_chose.price | currency | removeZero"></td>
          <td v-text="_chose.count"></td>
          <td v-text="_chose.count*_chose.price | currency | removeZero"></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('footer')
@include('partials.tmp')
@stop