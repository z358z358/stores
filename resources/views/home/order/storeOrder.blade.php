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
<table class="table table-hover table-striped" v-repeat="orders">
<thead>
<tr>
<th>訂單編號</th>
<th>商店</th>
<th>總價錢</th>
<th>時間</th>
<th></th>
</tr>
</thead>
<tbody id="order-${id}-tbody" class="order-stauts-${status}" data-order-id="${id}" data-order-key="${key}">
<tr class="">
<td class="">${id}</td>
<td class="">${store_name}</td>
<td class="">${price}</td>
<td class=""><time class="timeago" datetime="${updated_at}">${updated_at}</time></td>
<td class="">
<input class="bind-button del-button btn btn-default" data-action="more" type="button" value="詳細" />
{!! Form::open(['route' => ['order.update'], 'method' => 'put', 'class' => 'order-accept form-inline inline-my']) !!}
{!! Form::hidden('id', '${id}') !!}
{!! Form::hidden('store_id', '${store_id}') !!}
{!! Form::hidden('step', 'accept') !!}
{!! Form::hidden('order_token', '${order_token}') !!}
{!! Form::submit('接受', ['class' => 'btn btn-warning']) !!}
{!! Form::close() !!}

{!! Form::open(['route' => ['order.update'], 'method' => 'put', 'class' => 'order-done form-inline inline-my']) !!}
{!! Form::hidden('id', '${id}') !!}
{!! Form::hidden('store_id', '${store_id}') !!}
{!! Form::hidden('step', 'done') !!}
{!! Form::hidden('order_token', '${order_token}') !!}
{!! Form::submit('已付款', ['class' => 'btn btn-success']) !!}
{!! Form::close() !!}

{!! Form::open(['route' => ['order.destroy'], 'method' => 'delete', 'class' => 'order-del form-inline inline-my']) !!}
{!! Form::hidden('id', '${id}') !!}
{!! Form::hidden('order_token', '${order_token}') !!}
{!! Form::submit('刪除', ['class' => 'btn btn-danger']) !!}
{!! Form::close() !!}
</td>
</tr>

<tr class="order-tr-detail warning">
<th>名稱</th><th>單價</th><th>數量</th><th colspan="2">小計</th>
</tr>

</tbody>
</table>
</div>
<pre>@{{ $data | json}}</pre>
</div>
@endsection

@section('footer')

@include('partials.tmp')

<script id="order-tr" type="text/x-handlebars-template">
<tr class="order-tr-detail warning">
    <td>${name}</td><td>${price}</td><td>${count}</td><td colspan="2">${sum}</td>
</tr>
</script>

@stop