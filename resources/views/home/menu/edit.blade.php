@extends('app')

@section('title')
修改商店商品
@stop

@section('content')
<div id="item" class="container-fluid">
  @include('home.menu.nav')
  {!! Form::model($store, ['route' => ['menu.update', $store->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}
  <div class="row">
    <item-table :items="items" on-off-type="on"></item-table>
  </div>
  <div class="row">
    <item-table :items="items" on-off-type="off"></item-table>
  </div>
  <!--<pre>@{{ items | json}}</pre>-->
  {!! Form::hidden('items_json', '@{{ items | orderBy \'status\' -1 | json}}') !!}
<!--
    <div class="col-md-12">
      <div v-show="offShelf.length" class="panel panel-default">
        <div class="panel-heading">已下架的商品</div>
        <div class="panel-body">
          <div class="dataTable_wrapper">
            <item-table :items="offShelf" onOff="off"></item-table>
            <table id="item-table-remove" class="item-table form-table sortable table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>名稱</th>
                  <th>單價</th>
                  <th></th>
                </tr>
              </thead>
             <tbody v-for="item in offShelf | orderBy 'status' true" track-by="id" class="item-tbody ui-state-default">
                <tr>
                  <td v-text="item.name"></td>
                  <td v-text="item.price | currency"></td>
                  <td>
                    <input type="hidden" name="items[@{{ item.id }}][id]" v-model="item.id" />
                    <input type="hidden" name="items[@{{ item.id }}][status]" v-model="item.status" />
                    <input type="hidden" name="items[@{{ item.id }}][name]" v-model="item.name" />
                    <input type="hidden" name="items[@{{ item.id }}][price]" v-model="item.price" />
                    <button @click="item.status = 1" class="btn btn-default" type="button">上架</button>
                    <button @click="removeItem(item)" class="btn btn-default btn-danger" type="button">刪除</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>-->
  {!! Form::submit('送出', ['class' => 'btn btn-primary form-control']) !!}
  {!! Form::close() !!}
</div>

<template id="item-table-template">
  <div class="col-md-12" v-if="_items.length || isOn">
    <div class="panel panel-default">
      <div class="panel-heading" v-text="isOn ? '修改商品' : '已下架的商品'"></div>
      <div class="panel-body">
        <div class="dataTable_wrapper">
          <table id="item-table" class="item-table form-table table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="col-md-6">名稱</th>
                <th class="col-md-3">單價</th>
                <th class="col-md-3"></th>
              </tr>
            </thead>
            <tbody v-for="(index, item) in _items" class="item-tbody">
              <tr v-show="! item.edit">
                <td v-text="item.name"></td>
                <td v-text="item.price | currency"></td>
                <td>
                @{{item.status}}
                  <button @click="move(_items , index , -1)" :class="{disabled:index == 0}" class="btn btn-default" type="button">↑</button>
                  <button @click="move(_items , index , 1)"  :class="{disabled:index == _items.length - 1}" class="btn btn-default" type="button">↓</button>
                  <span v-if="isOn">
                    <button @click="item.edit = true" class="btn btn-default" type="button">修改</button>
                    <button @click="item.status = -1" class="btn btn-default" type="button">下架</button>
                  </span>
                  <span v-else>
                    <button @click="item.status = 1" class="btn btn-default" type="button">上架</button>
                    <button @click="removeItem(item)" class="btn btn-link" type="button">刪除</button>
                  </span>
                </td>
              </tr>
              <tr v-else>
                <td><input type="text" name="items[name][]" placeholder="名稱" v-model="item.name"/></td>
                <td><input type="number" name="items[price][]"  placeholder="單價" v-model="item.price" number /></td>
                <td>
                  <input type="hidden" name="items[id][]" v-model="item.id" />
                  <input type="hidden" name="items[status][]" v-model="item.status" />
                  <button @click="item.edit = false" class="btn btn-default" type="button" >確定</button>
                </td>
              </tr>
            </tbody>
          </table>
          <button type="button" @click="newItem" v-if="isOn" >新增商品</button>
        </div>
      </div>
    </div>
  </div>
</template>

@endsection

@section('footer')

@include('partials.tmp')

@stop