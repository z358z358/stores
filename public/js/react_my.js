/**
 * @jsx React.DOM
 */

var MenuRow = React.createClass({displayName: "MenuRow",
  render: function() {
    return (
        React.createElement("tr", null, 
            React.createElement("td", null, this.props.name), 
            React.createElement("td", null, this.props.price)
        )
    );
  }
});

var MenuList = React.createClass({displayName: "MenuList",
  render: function() {
    var rows = [];
    this.props.items.forEach(function(item) {
        rows.push(React.createElement(MenuRow, {name: item.name, price: item.price}));
    }.bind(this));
    return {rows};
  }
});


var MenuApp = React.createClass({displayName: "MenuApp",
  getInitialState: function() {
    return {items: [{name:'測試1',price:'123'},{name:'測試2',price:'456'}]};
  },
  render: function() {
    return (
      React.createElement("div", {className: "dataTable_wrapper"}, 
        React.createElement("table", {className: "table table-striped table-bordered table-hover", id: "dataTables-example"}, 
        React.createElement("thead", null, 
            React.createElement("tr", null, 
                React.createElement("th", null, "名稱"), 
                React.createElement("th", null, "價錢")
            )
        ), 
        React.createElement("tbody", null, 
          React.createElement(MenuList, {items: this.state.items})
        )
        )
      )
    );
  }
});

React.render(React.createElement(MenuApp, null), document.getElementById('react'));