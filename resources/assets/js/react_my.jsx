/**
 * @jsx React.DOM
 */

var MenuRow = React.createClass({
  render: function() {
    return (
        <tr>
            <td>{this.props.name}</td>
            <td>{this.props.price}</td>
        </tr>
    );
  }
});

var MenuList = React.createClass({
  render: function() {
    var rows = [];
    this.props.items.forEach(function(item) {
        rows.push(<MenuRow name={item.name} price={item.price} />);
    }.bind(this));
    return {rows};
  }
});


var MenuApp = React.createClass({
  getInitialState: function() {
    return {items: [{name:'測試1',price:'123'},{name:'測試2',price:'456'}]};
  },
  render: function() {
    return (
      <div className="dataTable_wrapper">
        <table className="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th>名稱</th>
                <th>價錢</th>
            </tr>
        </thead>
        <tbody>
          <MenuList items={this.state.items} />
        </tbody>
        </table>
      </div>
    );
  }
});

React.render(<MenuApp />, document.getElementById('react'));