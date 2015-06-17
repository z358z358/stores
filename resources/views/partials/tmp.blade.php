<script type="text/javascript">
var items = items || [];
var itemAttrs = itemAttrs || [];
var maxId = 0;
var demarcation = demarcation || '|';

items.forEach( function (item) {
  var item_id = parseInt(item.id);
  item.edit = false;
  item.totalPrice = item.price;
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
        a[0].attrs[itemAttrId] = $.extend(true, {}, itemAttr)
      }
    });
  }
});

new Vue({
  el: '#item',

  data: {
    maxId: maxId,
    demarcation: demarcation,
    items: items,
    itemAttrs: itemAttrs,

    filters: {
      onShelf: function (item) {
        return item.status >= 0;
      },

      offShelf: function (item) {
        return item.status < 0;
      },

      optionClicked: function(option) {
        return option.clicked;
      }
    }
  },

  computed: {
      onShelf: function() {
          return this.items.filter(this.filters.onShelf);
      },

      offShelf: function() {
          return this.items.filter(this.filters.offShelf);
      },
  },

  watch: {
    // 更新select2
    'itemAttrs': function (val, oldVal) {
      $(".select2NoTags").select2({
        placeholder: '選擇商品',
        width: '100%'
      });
    }
  },

  ready: function () {
    $( ".sortable" ).sortable();
    $( ".sortable" ).disableSelection();
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
        edit: true
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
      this.items.$remove(item.$data);
    },

    // 刪除屬性
    removeItemAttr: function (itemAttr) {
      this.itemAttrs.$remove(itemAttr);
    },

    // 刪除屬性選項
    removeItemAttrOption: function (itemAttr, index) {
      itemAttr.option.$remove(index);
    },

    // 建立訂單 點屬性checkbox
    clickItemAttr: function (item, itemAttr, option) {
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

      var choseKey = item.id + demarcation;
      optionClicked.forEach(function (option){
        choseKey += itemAttr.id + demarcation + option.id;
      });
      // 更新choseKey
      item.$set('choseKey', choseKey);
    }

  }
});
</script>