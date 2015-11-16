@if (isset($useFirebase))
  <script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>
  <script type="text/javascript">
  var useFirebase = true;
  </script>
@endif

<script type="text/javascript">
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

    filters: {
      onShelf: function (item) {
        return item.status >= 0;
      },

      offShelf: function (item) {
        return item.status < 0;
      },
    }
  },

  filters: {
    removeZero: function (price) {
      return price.replace(".00", "");
    }
  },

  computed: {
      onShelf: function() {
          return this.items.filter(this.filters.onShelf);
      },

      offShelf: function() {
          return this.items.filter(this.filters.offShelf);
      },

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
    'itemAttrs': function (val, oldVal) {
      $(".select2NoTags").select2({
        placeholder: '選擇商品',
        width: '100%'
      });
    },
  },

  // 為了讓v-repeat v-model v-on一起用
  components:{
    options:{
      watch:{
        'clicked':function(v){
          alert(v);
        }
      }
    }
  },

  ready: function () {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
    this.checkChose();

    var choseTab = $("#myTab #chose-tab");
    if(!$.isEmptyObject(this.chose) && choseTab) choseTab.trigger( "click" );
  },

  methods: {
    // 新增商品
    newItem: function () {
      this.maxId++;
      this.items.push({
        id:this.maxId,
        name: '',
        price: 0,
        edit: true,
        status: 1
      });
    },

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

    // 完成編輯
    editDone: function (item) {
      item.edit = false;
    },

    // 刪除商品
    removeItem: function (item) {
      this.items.$remove(item);
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
        console.log(snapshot);
      });
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
</script>