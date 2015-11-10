@extends('app')

@section('title')
修改商品屬性
@stop

@section('content')
<div id="item" class="container-fluid">
  @include('home.menu.nav')
  {!! Form::model($store, ['route' => ['menu.attr.update', $store->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">修改屬性</div>
        <div class="panel-body">
          <div class="dataTable_wrapper">
            <table id="attr-table" class="attr-table form-table table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>名稱</th>
                  <th></th>
              </tr>
              </thead>
              <tbody class="attr-tbody" v-for="itemAttr in itemAttrs" track-by="id">
                <tr v-show="! itemAttr.edit">
                  <td class="attr-td-name col-md-3" v-text="itemAttr.name"></td>
                  <td class="col-md-9">
                    <input class="btn btn-default" type="button" value="修改" @click="itemAttr.edit = true" />
                    <input class="btn btn-danger" type="button" value="刪除" @click="removeItemAttr(itemAttr)" />
                  </td>
                </tr>
                <tr v-show="itemAttr.edit">
                  <td class="col-md-3"><input type="text" name="attr[@{{ itemAttr.id }}][name]" placeholder="名稱" v-model="itemAttr.name" /></td>
                  <td class="col-md-9">
                    <p v-if="itemAttr.option.length">選項</p>
                    <div class="form-group row div-option" v-for="option in itemAttr.option">
                      <div class="col-sm-3">
                      <input type="hidden" class="form-control" name="attr[@{{ itemAttr.id }}][option][id][]" v-model="option.id">
                      <input type="text" class="form-control" name="attr[@{{ itemAttr.id }}][option][name][]" placeholder="名稱" v-model="option.name">
                      </div>
                      <div class="col-sm-2"><input type="number" class="form-control" name="attr[@{{ itemAttr.id }}][option][price][]" placeholder="單價變動" v-model="option.price"></div>
                      <div class="col-xs-2"><input class="btn btn-danger" type="button" value="刪除" @click="removeItemAttrOption(itemAttr, option)"></div>
                    </div>
                    <button class="btn btn-default" type="button" @click="newOption(itemAttr)">新增選項</button>
                    <div class="form-group">
                      <label class="col-sm-2">最多可以選幾個</label>
                      <div class="col-sm-10">
                        <input type="number" name="attr[@{{ itemAttr.id }}][max]" v-model="itemAttr.max" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2">套用的商品</label>
                      <div class="col-sm-10">
                    {!! Form::select('attr[@{{ itemAttr.id }}][item_id][]', $itemList, null, ['class' => 'form-control select2NoTags', 'multiple', 'v-model' => 'itemAttr.item_id']) !!}
                      </div>
                    </div>
                    <input type="hidden" name="attr[@{{ itemAttr.id }}][attr_id]" v-model="itemAttr.id" />
                    <input class="btn btn-default" type="button" value="確定" @click="editDone(itemAttr)" />
                  </td>
                </tr>
              </tbody>
            </table>
            <input id="attr-add-new" type="button" value="新增屬性" @click="newItemAttr" />
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