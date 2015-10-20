@extends('app')

@section('title')
建立訂單
@stop

@section('content')
<div id="item" class="container-fluid col-md-10 col-md-offset-1">
  <div class="row">
      <div class="col-lg-10">
          <h1 class="page-header">
              <a href="{{ route('store.slug', $store->slug) }}">{{ $store->name }}</a>
          </h1>
      </div>
  </div>

  <ul id="myTab" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#menu" id="menu-tab" role="tab" data-toggle="tab" aria-controls="menu" aria-expanded="true">Menu</a></li>
    <li role="presentation" v-show="info.kind"><a href="#chose" role="tab" id="chose-tab" data-toggle="tab" aria-controls="chose" aria-expanded="false">已選</a></li>
  </ul>
  <div id="myTabContent" class="tab-content">
    <div role="tabpanel" class="tab-pane fade active in" id="menu" aria-labelledby="menu-tab">
      <table class="menu-item-table table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="col-md-6 col-sm-6 col-xs-5">名稱</th>
            <th class="col-md-2 col-sm-2 col-xs-3">單價</th>
            <th class="col-md-4 col-sm-4 col-xs-4"></th>
        </tr>
        </thead>
        <tbody class="item-tbody" v-repeat="item :items | orderBy 'status'">
          <tr class="menu-item-tr">
            <td>
              <span v-text="item.name"></span>
              <small class="chose-count" v-if="chose[item.choseKey]" v-text="chose[item.choseKey] && chose[item.choseKey].count"></small>
              <ul>
                <li v-repeat="attr :item.attrs">
                  <span v-text="attr.name"></span>
                  <label class="checkbox-inline" v-repeat="attr.option"><input type="checkbox" v-model="clicked" v-on="click: clickItemAttr(item, attr, this)"><span v-text="name"></span></label>
                </li>
              </ul>
            </td>
            <td><span v-text="item.totalPrice | currency | removeZero"></span></td>
            <td class="btn-chose">
              <button type="button" class="btn btn-default" v-on="click: addChoseCount(item, 3)">+3</button>
              <button type="button" class="btn btn-default" v-on="click: addChoseCount(item, 1)">+1</button>
              <span v-show="chose[item.choseKey]">
                <button type="button" class="btn btn-default" v-on="click: addChoseCount(item, -1)">-1</button>
                <button type="button" class="btn btn-danger" v-on="click: addChoseCount(item, 0)">清除</button>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="chose-info"></div>
    </div>

    <div role="tabpanel" class="tab-pane fade" id="chose" aria-labelledby="chose-tab">
      {!! Form::model($store, ['route' => ['menu.submit', $store->slug], 'method' => 'post', 'class' => 'form-horizontal']) !!}
      <table class="menu-item-table table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="col-md-6 col-sm-6 col-xs-5">名稱</th>
            <th class="col-md-1 col-sm-1 col-xs-1">單價</th>
            <th class="col-md-1 col-sm-1 col-xs-2">數量</th>
            <th class="col-md-4 col-sm-4 col-xs-4"></th>
        </tr>
        </thead>
        <tbody v-repeat="chose | orderBy 'status'">
          <tr>
            <td>
              <span v-text="name"></span>
            </td>
            <td v-text="price | currency | removeZero"></td>
            <td v-text="count"></td>
            <td class="btn-chose">
              <button type="button" class="btn btn-default" v-on="click: addChoseCount($key, 3)">+3</button>
              <button type="button" class="btn btn-default" v-on="click: addChoseCount($key, 1)">+1</button>
              <span>
                <button type="button" class="btn btn-default" v-on="click: addChoseCount($key, -1)">-1</button>
                <button type="button" class="btn btn-danger" v-on="click: addChoseCount($key, 0)">清除</button>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="chose-info"></div>
      {!! Form::hidden('chose', '@{{ chose | json}}') !!}
      {!! Form::hidden('info', '@{{ info | json}}') !!}
      {!! Form::hidden('order_id', $order_id) !!}
      {!! Form::submit('結帳', ['class' => 'btn btn-primary form-control', 'v-show' => 'info.kind']) !!}
      {!! Form::close() !!}
    </div>
  </div>
  <pre>@{{ $data | json }}</pre>
</div>
@endsection

@section('footer')

@include('partials.tmp')

@stop