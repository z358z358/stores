@extends('app')

@section('title')
修改商店商品
@stop

@section('content')
<div id="item" class="container-fluid">
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
                  <th class="col-md-6">名稱</th>
                  <th class="col-md-3">單價</th>
                  <th class="col-md-3"></th>
              </tr>
              </thead>
              <tbody v-repeat="onShelf | orderBy 'status'" track-by="id" class="item-tbody ui-state-default">
                <tr v-show="! edit">
                  <td v-text="name"></td>
                  <td v-text="price | currency"></td>
                  <td>
                    <button v-on="click: edit = true" class="btn btn-default" type="button">修改</button>
                    <button v-on="click: status = -1" class="btn btn-default" type="button">下架</button>
                  </td>
                </tr>

                <tr v-show="edit">
                  <td><input type="text" name="items[@{{ id }}][name]" placeholder="名稱" v-model="name"/></td>
                  <td><input type="number" name="items[@{{ id }}][price]"  placeholder="單價" v-model="price" number /></td>
                  <td>
                    <input type="hidden" name="items[@{{ id }}][id]" v-model="id" />
                    <input type="hidden" name="items[@{{ id }}][status]" v-model="status" />
                    <button v-on="click: editDone(this)" class="btn btn-default" type="button" >確定</button>
                  </td>
                </tr>
              </tbody>
            </table>
            <button v-on="click: newItem" type="button">新增商品</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div v-show="offShelf.length" class="panel panel-default">
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
             <tbody v-repeat="offShelf | orderBy 'status' true" track-by="id" class="item-tbody ui-state-default">
                <tr>
                  <td v-text="name"></td>
                  <td v-text="price | currency"></td>
                  <td>
                    <input type="hidden" name="items[@{{ id }}][id]" v-model="id" />
                    <input type="hidden" name="items[@{{ id }}][status]" v-model="status" />
                    <input type="hidden" name="items[@{{ id }}][name]" v-model="name" />
                    <input type="hidden" name="items[@{{ id }}][price]" v-model="price" />
                    <button v-on="click: status = 1" class="btn btn-default" type="button">上架</button>
                    <button v-on="click: removeItem(this)" class="btn btn-default btn-danger" type="button">刪除</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  {!! Form::submit('送出', ['class' => 'btn btn-primary form-control']) !!}
  {!! Form::close() !!}
  <pre>@{{ $data | json}}</pre>
</div>


@endsection

@section('footer')

@include('partials.tmp')

@stop