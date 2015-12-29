@if (isset($useFirebase))
<script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>
<script type="text/javascript">
  var useFirebase = true;
</script>
@endif

<script type="text/javascript">
  Vue.component('item-table', {
    template: '#item-table-template',
    props: ['items', 'onOffType'],

    data:function(){},

    computed:{
      _items:function(){
        var that = this;
        var tmp = this.items.filter(function(item){
          return that.onOffType == 'on' && item.status >= 0 || that.onOffType == 'off' && item.status < 0;
        })
        return tmp.sort( function(a,b){return that.isOn ? (a.status-b.status) :b.status-a.status } );
      },

      isOn:function(){
        return this.onOffType == 'on';
      }
    },

    methods:{
    // 新增商品
    newItem: function () {
      this.items.push({
        id:null,
        name: '',
        price: 0,
        edit: true,
        status: 1
      });
    },

    // 刪除商品
    removeItem: function (item) {
      this.items.$remove(item);
    },

    // 上移
    move:function(_items , index , type){
      var item = _items[index];
      var item2 = _items[index + type];
      //console.log(_items, index , item2.name);
      if(item2.status){
        if(item.status == item2.status){
          item.status += type;
        }
        else{
          var tmp = item2.status;
          item2.status = item.status;
          item.status = tmp;
        }
      }
    },
  },
});

Vue.config.debug = true // turn on debugging mode
$.cookie.json = true;

var fire;
var items = items || [];
var itemAttrs = itemAttrs || [];
var maxId = 0;
var demarcation = demarcation || '|';
var order_cookie_name = order_cookie_name || '';
var chose = chose || $.cookie(order_cookie_name);
var orders = orders || [];
var multiselectConfig = {allSelectedText:'全選',nonSelectedText:'未選',nSelectedText:'已選'};
chose = (Object.prototype.toString.call(chose) === '[object Object]') ? chose : {};
// 完成頁 刪掉舊cookie
if(order_cookie_name && orders.length) $.removeCookie(order_cookie_name, { path: '/' });



items.forEach( function (item) {
  var item_id = parseInt(item.id);
  item.edit = false;
  item.totalPrice = item.price;
  item.choseKey = item_id;
  item.fullName = item.name;
  maxId = (item_id > maxId) ? item_id : maxId;
});

itemAttrs.forEach( function (itemAttr) {
  var itemAttrId = parseInt(itemAttr.id);
  itemAttr.edit = false;
  itemAttr.clickCount = 0;
  itemAttr.max = parseInt(itemAttr.max);
  maxId = (itemAttrId > maxId) ? itemAttrId : maxId;
  itemAttr.option.forEach( function (option){ option.clicked = false;});

  if(items.length && itemAttr.item_id){
    itemAttr.item_id.forEach( function (item_id) {
      var a = $.grep(items, function(e){ return e.id == item_id; });
      if (a.length) {
        a[0].attrs = a[0].attrs || {};
        a[0].attrs[itemAttrId] = $.extend(true, {}, itemAttr);
      }
    });
  }
});

orders.forEach( function (order) {
  order.showDetail = false;
});

var vue = new Vue({
  el: '#item',

  data: {
    orders: orders,
    chose: chose,
    items: items,
    itemAttrs: itemAttrs,
    maxId: maxId,
    demarcation: demarcation,
    msg: [],
  },

  filters: {
    removeZero: function (price) {
      return price.replace(".00", "");
    }
  },

  computed: {
    info: function() {
      var info = {"price": 0, "count": 0, "kind": 0};
      var chose = this.chose;
      for (var key in chose) {
        info.price += chose[key]["price"]*chose[key]["count"];
        info.count += chose[key]["count"];
        info.kind++;
      }

      return info;
    }
  },

  watch: {
    // 更新select2
    'itemAttrs': function() {
      this.$nextTick(function () {
        $('.bootstrap-multiselect').multiselect(multiselectConfig);
      });
    },

    // 更新timeago
    'orders': function (val, oldVal) {
      $(".timeago").timeago();
    },
  },

  ready: function () {
    $('.bootstrap-multiselect').multiselect(multiselectConfig);
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
    this.checkChose();

    var choseTab = $("#myTab #chose-tab");
    if(!$.isEmptyObject(this.chose) && choseTab) choseTab.trigger( "click" );
  },

  methods: {
    // 新增屬性
    newItemAttr: function () {
      this.maxId++;
      this.itemAttrs.push({
        id:this.maxId,
        item_id: [],
        option: [],
        max: 0,
        edit: true,
        name: ''
      });
    },

    // 新增屬性選項
    newOption: function (itemAttr){
      itemAttr.option.push({
        name: ''
      });
    },

    // 刪除屬性
    removeItemAttr: function (itemAttr) {
      this.itemAttrs.$remove(itemAttr);
    },

    // 刪除屬性選項
    removeItemAttrOption: function (itemAttr, option) {
      itemAttr.option.$remove(option);
    },

    // 建立訂單 點屬性checkbox
    clickItemAttr: function (item, itemAttr, option) {
      option.clicked = !option.clicked;
      var options = item.options || {};
      var optionClicked = $.grep(itemAttr.option, function(e){ return e.clicked; });
      itemAttr.clickCount = optionClicked.length;

      // 勾超過max
      if(itemAttr.max > 0 && itemAttr.clickCount > itemAttr.max){
        option.clicked = false;
        return false;
      }

      // 更新價錢
      var add = (option.clicked) ? 1 : -1;
      item.totalPrice += option.price*add;

      // 更新options
      options[itemAttr.id] = [];
      optionClicked.forEach(function (option){
        options[itemAttr.id].push(option.id);
      });
      options[itemAttr.id].sort();
      Vue.set( item, 'options', options );

      // 更新chosekey fullName
      var choseKey = item.id;
      var fullName = item.name;
      for(var attrId in item.options){
        for(var optionIndex in item.options[attrId]){
          var optionId = item.options[attrId][optionIndex];
          choseKey += this.demarcation + attrId + this.demarcation + optionId;

          var tmpOption = $.grep(item.attrs[attrId]["option"], function(e){ return e.id == optionId; });
          fullName = fullName + "," + tmpOption[0]['name'];
        }
      }
      //console.log(choseKey ,fullName );
      //item.choseKey=choseKey;
      Vue.set( item, 'choseKey', choseKey );
      Vue.set( item, 'fullName', fullName );
    },

    // 增加-減少
    addChoseCount: function (item, count) {
      var choseKey = (typeof item == "string") ? item : item.choseKey;
      var chose = this.chose[choseKey] || {
        id: item.id,
        price: item.totalPrice,
        name: item.fullName,
        simpleName: item.name,
        count: 0,
        status: item.status
      };

      if(count == 0){
        chose['count'] = 0;
      }
      else{
        chose['count'] += count;
      }

      if(isNaN(chose["count"]) || chose["count"] <= 0){
        Vue.delete( this.chose, choseKey);
      }
      else{
        Vue.set( this.chose, choseKey, chose );
      }

      // 若都沒選了 就回到menu
      if($.isEmptyObject(this.chose)){
        $("#myTab #menu-tab").trigger( "click" );
      }
      $.cookie(order_cookie_name, this.chose, { path: '/' });
    },

    // 刪掉有問題的chose
    checkChose: function () {
      if(typeof errorChoseKey == "string") Vue.delete( this.chose, errorChoseKey);
    },

    // Firebase啟動
    fireOn: function(){
      fire.child('order/' + store.id).on("value", function(snapshot) {
        var tmp = snapshot.val();
        if(tmp){
          vue.$set('orders', tmp);
        }
        else{
          vue.$set('orders', 'none');
        }
      });
    },

    ajax_result: function(data){
      if(data['msg']){
       vue.$set('msg', data['msg']);
     }
   }

 }
});

if(typeof useFirebase != 'undefined'){
  var script = document.createElement('script');
  script.onload = function() {
    fire = new Firebase('https://onininon-store.firebaseio.com/');
    vue.fireOn();
  };
  script.async=true;
  script.src = "https://cdn.firebase.com/js/client/2.3.1/firebase.js";
  document.getElementsByTagName('head')[0].appendChild(script);
}

$(function() {
  $('#flash-overlay-modal').modal();
  jQuery.timeago.settings.localeTitle = true;
  $(".timeago").timeago();
  $(".bind-form").on("submit", ".form-ajax", function(e){
    e.preventDefault();
    var form = $(this);
    $.post(form.attr("action"), form.serialize(), function(data){
      vue.ajax_result(data);
    }, "json");
    return false;
  });
});
</script>