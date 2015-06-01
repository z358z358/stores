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
                  <td>@{{ name }}</td>
                  <td>@{{ price | currency }}</td>
                  <td>
                    <button v-on="click: edit = true" class="btn btn-default" type="button">修改</button>
                    <button v-on="click: status = -1" class="btn btn-default" type="button">下架</button>
                  </td>
                </tr>

                <tr v-show="edit">
                  <td><input v-model="name" name="items[@{{ id }}][name]" placeholder="名稱" /></td>
                  <td><input v-model="price" name="items[@{{ id }}][price]" type="number" placeholder="單價" number /></td>
                  <td>                    
                    <input type="hidden" name="items[@{{ id }}][id]" value="@{{ id }}" />
                    <input type="hidden" name="items[@{{ id }}][status]" value="@{{ status }}" />              
                    <button v-on="click: editDone(this)" class="btn btn-default" type="button" >確定</button>
                    <button v-on="click: editCancel(this)" class="btn btn-default" type="button" >取消</button>
                  </td>
                </tr>
              </tbody>
            </table>
          <button v-on="click: newItem" type="button">新增商品</button>
        </div>              
      </div>
    </div>

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
                <td>@{{ name }}</td>
                <td>@{{ price | currency }}</td>
                <td>
                  <input type="hidden" name="items[@{{ id }}][id]" value="@{{ id }}" />
                  <input type="hidden" name="items[@{{ id }}][status]" value="@{{ status }}" />
                  <input v-model="name" type="hidden" name="items[@{{ id }}][name]" />
                  <input v-model="price" type="hidden" name="items[@{{ id }}][price]" />        
                  <button v-on="click: status = 1" class="btn btn-default" type="button">上架</button>
                  <button v-on="click: removeItem(this)" class="btn btn-default btn-danger" type="button">刪除</button>
              </tr>
            </tbody>
          </table>
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

<script type="text/javascript">
var items = items || [];
var maxItemId = 0;
items.forEach( function (item) {
  var item_id = parseInt(item.id);
  item.old = {name:item.name, price:item.price};
  item.edit = false;
  maxItemId = (item_id > maxItemId) ? item_id : maxItemId;
});

new Vue({
  el: '#item',

  data: {
    maxItemId: maxItemId,
    items: items,
    filters: {
      onShelf: function (item) {
        return item.status >= 0;
      },

      offShelf: function (item) {
        return item.status < 0;
      }
    }
  },

  computed: {
      onShelf: function() {
          return this.items.filter(this.filters.onShelf);
      },

      offShelf: function() {
          return this.items.filter(this.filters.offShelf);
      }
  },

  ready: function () {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
  },

  methods: {
    // 新增商品
    newItem: function () {
      this.maxItemId++;
      this.items.push({
        id:this.maxItemId, 
        name: '',
        price: 0,
        edit: true,
        status: 1
      });      
    },

    // 完成編輯
    editDone: function (item) {
      item.old = { 
        name: item.name, 
        price: item.price
      };
      item.edit = false;
    },

    // 取消編輯
    editCancel: function (item) {
      item.name = item.old.name;
      item.price = item.old.price;
      item.edit = false;
    },

    // 刪除商品
    removeItem: function (item) {
      this.items.$remove(item.$data);
    }
  }
});
</script>
@stop