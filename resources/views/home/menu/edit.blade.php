@extends('app')

@section('title')
修改商店資料
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            	<div class="panel panel-default">
				    <div class="panel-heading">修改商店資料</div>
				    <div class="panel-body">
						<div id="react"></div>
                	</div>
				</div>

        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.1/react.min.js"></script>
<script>
var store = [];
var TodoList = React.createClass({displayName: "TodoList",
  render: function() {
    var createItem = function(itemText) {
      return React.createElement("li", null, itemText);
    };
    return React.createElement("ul", null, this.props.items.map(createItem));
  }
});
var TodoApp = React.createClass({displayName: "TodoApp",
  getInitialState: function() {
    return {items: store, text: ''};
  },
  onChange: function(e) {
    this.setState({text: e.target.value});
  },
  handleSubmit: function(e) {
    e.preventDefault();
    var nextItems = this.state.items.concat([this.state.text]);
    var nextText = '';
    this.setState({items: nextItems, text: nextText});
  },
  render: function() {
    return (
      React.createElement("div", null, 
        React.createElement("h3", null, "TODO"), 
        React.createElement(TodoList, {items: this.state.items}), 
        React.createElement("form", {onSubmit: this.handleSubmit}, 
          React.createElement("input", {onChange: this.onChange, value: this.state.text}), 
          React.createElement("button", null, 'Add #' + (this.state.items.length + 1))
        )
      )
    );
  }
});

React.render(React.createElement(TodoApp, null), document.getElementById('react'));
</script>
@stop