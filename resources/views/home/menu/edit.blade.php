@extends('app')

@section('title')
修改商店資料
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
				    <div class="panel-heading">修改商店資料</div>
				    <div class="panel-body">
            {!! Form::model($store, ['route' => ['menu.update', $store->id], 'method' => 'post', 'class' => 'form-horizontal']) !!}

						    <div id="react"></div>
                {!! Form::submit('送出', ['class' => 'btn btn-primary form-control']) !!}
            {!! Form::close() !!}
            </div>
				</div>

        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.1/react.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.1/JSXTransformer.js"></script>
<script type="text/jsx">
/**
 * @jsx React.DOM
 */

var MenuRow = React.createClass({
  getInitialState: function() {
    return {item: this.props.item};
  },

  onChange: function(e){
    var nowItem = {
      name:this.refs.name.getDOMNode().value,
      price:this.refs.price.getDOMNode().value,
      item_id:this.props.item.item_id
    };
    this.setState({item: nowItem});
  },

  handleDeleteRow: function() {
    this.props.onRowDelete( this.props.index );
    return false;
  },

  handleCancel: function() {
    this.props.onCancel( this.props.index );
    return false;
  },

  handleEditRow: function() {
    this.props.onEditRow( this.props.index );
    return false;
  },

  handleSaveRow: function() {
    this.checkRowValue();
    this.props.onSaveRow( this.props.index, this.state.item );
    return false;
  },

  checkRowValue: function() {
    var nowItem = this.state.item;
    nowItem.price = isNaN(Number(nowItem.price)) ? 0 : Number(nowItem.price);
    this.setState({item: nowItem});
  },

  render: function() {
    var editClass = this.props.item.edit ? '' : 'hide' ;
    var showClass = this.props.item.edit ? 'hide' : '' ;

    return (
      <tbody>
        <tr className={showClass}>
          <td>{this.props.item.name}</td>
          <td>{this.props.item.price}</td>
          <td>
            <input type="button" onClick={this.handleEditRow} value="修改" />
            <input type="button" onClick={this.handleDeleteRow} value="刪除" />
          </td>
        </tr>

        <tr className={editClass}>
          <td><input name={"items[" + this.props.index + "][name]"} ref="name"  onChange={this.onChange} value={this.state.item.name} placeholder="名稱" /></td>
          <td><input name={"items[" + this.props.index + "][price]"} ref="price" onChange={this.onChange} value={this.state.item.price} placeholder="價錢" /></td>
          <td>
            <input type="hidden" name={"items[" + this.props.index + "][item_id]"} value={this.props.item.item_id} />
            <input type="button" onClick={this.handleSaveRow} value="確定" />
            <input type="button" onClick={this.handleCancel} value="取消" />
          </td>
        </tr>

      </tbody>
    );
  }
});

var MenuList = React.createClass({
  getInitialState: function() {
    var max = 0;
    this.props.items.items.forEach(function(item){
      var item_id = parseInt(item.item_id);
      max = (item_id > max)?item_id:max;
    }.bind(max));

    return {items: this.props.items.items, max:max};
  },

  onRowDelete: function(itemIndex) {
    this.state.items.splice(itemIndex, 1);
    this.setState({ items: this.state.items });
  },

  onCancel: function(itemIndex) {
    this.state.items[itemIndex].edit = false;
    this.setState({ items: this.state.items });
  },

  onEditRow: function(itemIndex) {
    this.state.items[itemIndex].edit = true;
    this.setState({ items: this.state.items });
  },

  onSaveRow: function(itemIndex, newItem) {
    this.state.items[itemIndex] = newItem;
    this.state.items[itemIndex].edit = false;
    this.setState({ items: this.state.items });
  },

  handleNewRow: function() {
    var item_id = this.state.max;
    item_id++;
    this.setState({ max: item_id });
    this.setState({ items: this.state.items.concat({item_id:item_id}) });
  },

  render: function() {
    var rows = [];
    this.state.items.forEach(function(item, index) {
        rows.push(
          <MenuRow
          item={item}
          index={index}
          onEditRow={this.onEditRow}
          onRowDelete={this.onRowDelete}
          onCancel={this.onCancel} 
          onSaveRow={this.onSaveRow}  />
        );
    }.bind(this));
    return (
      <div>
      <table className="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>{this.state.max}
            <tr>
                <th>名稱</th>
                <th>價錢</th>
                <th></th>
            </tr>
        </thead>
        {rows}
      </table>
      <input type="button" onClick={this.handleNewRow} value="新增商品" />
      </div>
    );
  }
});


var MenuApp = React.createClass({
  render: function() {
    return (
      <div className="dataTable_wrapper">
          <MenuList items={this.props.items} />
      </div>
    );
  }
});


var ITEMS = { type:'edit' , items:{!! json_encode($items) !!} };
React.render(<MenuApp items={ITEMS} />, document.getElementById('react'));
</script>
@stop