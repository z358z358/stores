<?php
namespace app\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Item;
use App\Order;
use App\Store;
use Auth;
use DB;
use Illuminate\Http\Request;
use JavaScript;

class MenuController extends Controller {
	// 分隔用的keyword
	public $demarcation = '|';

	public function __construct() {
		$this->middleware('auth', ['except' => ['index', 'show', 'submit']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($slug) {
		$store = Store::findBySlug($slug);
		dd($store);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store() {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Store $store, Order $order, $created_at = '') {
		DB::enableQueryLog();
		$items = $store->items()->statusOn()->get();
		$itemAttrs = $this->getStoreItemAttrArray($store);
		$demarcation = $this->demarcation;
		$chose = null;
		$order_id = 0;
		$errorChoseKey = session()->get('errorChoseKey');
		if ($order && $created_at) {
			$where = ['id' => $order->id, 'created_at' => $created_at];
			$order = $store->orders()->idAndCreated($where)->first();
			if ($order) {
				flash()->warning('您正在編輯訂單' . $order->id . '');
				$chose = $order->content_array['chose'];
				$order_id = $order->id;
			}
		}

		JavaScript::put([
			'items' => $items,
			'itemAttrs' => $itemAttrs,
			'chose' => $chose,
			'demarcation' => $demarcation,
			'order_cookie_name' => $store->order_cookie_name,
			'errorChoseKey' => $errorChoseKey,
		]);

		return view('home.menu.show', compact('store', 'order_id'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Store $store) {
		$items = $store->items()->get();

		JavaScript::put(['items' => $items]);

		return view('home.menu.edit', compact('store'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Store $store, Request $request) {
		//dd($request->all());
		$items = [];

		if ($request->has('items_json')) {
			$items_json = json_decode($request->input('items_json'), true);
			$count = 2;
			$max = count($items_json) + $count;
			foreach ($items_json as $data) {
				$data['status'] = ($data['status'] >= 0) ? $max - $count : -$count;
				$item = $store->items()->findOrNew($data['id']);
				$item->fill($data);
				$item->save();
				$items[] = $item->id;
				$count++;
			}
			$store->items()->whereNotIn('id', $items)->delete();
			flash()->success('修改菜單成功');
		}
		return redirect(route('menu.edit', $store->id));
	}

	/**
	 * 商品屬性編輯頁面
	 * @param  Store  $store [description]
	 * @return [type]        [description]
	 */
	public function attrEdit(Store $store) {
		DB::enableQueryLog();
		$itemList = $store->items()->lists('name', 'id');
		$itemAttrs = $this->getStoreItemAttrArray($store);

		JavaScript::put(['itemAttrs' => $itemAttrs]);

		//dd($attrs);
		return view('home.menu.attrEdit', compact('store', 'itemList'));
	}

	/**
	 * 更新商品屬性
	 * @param  Store   $store   [description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function attrUpdate(Store $store, Request $request) {
		DB::enableQueryLog();
		//dd($request->all());
		$items = [];
		$itemAttrs = [];
		// 先把attr存起來 & item對應的attr存到$items
		if (is_array($request->get('attr'))) {
			foreach ($request->get('attr') as $data) {
				$data['item_id'] = isset($data['item_id']) ? $data['item_id'] : [];
				$attr = $store->itemAttrs()->find($data['attr_id']);
				if ($attr) {
					$attr->content = $this->attrContentJson($data);
					$attr->save();
					$attr_id = $attr->id;
				} else {
					$newAttr = \App\ItemAttr::create(['store_id' => $store->id, 'content' => $this->attrContentJson($data)]);
					$attr_id = $newAttr->id;
				}

				if (isset($data['item_id'])) {
					foreach ($data['item_id'] as $item_id) {
						$items[$item_id][] = $attr_id;
					}
				}
				$itemAttrs[] = $attr_id;
			}
		}

		// item sync attrs
		foreach ($store->items()->whereIn('id', array_keys($items))->with('itemAttrs')->get() as $item) {
			$item->itemAttrs()->sync($items[$item->id]);
		}

		// 清掉沒attr的item
		foreach ($store->items()->whereNotIn('id', array_keys($items))->with('itemAttrs')->get() as $item) {
			$item->itemAttrs()->sync([]);
		}

		// 清掉沒post的attr
		$store->itemAttrs()->whereNotIn('id', $itemAttrs)->delete();

		flash()->success('修改屬性成功');
		return redirect(route('menu.attr.edit', $store->id));
	}

	/**
	 * 建立訂單
	 * @param  Store   $store   [description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function submit(Store $store, Request $request) {
		$info = json_decode($request->input('info'), true);
		$chose = json_decode($request->input('chose'), true);
		$clear = $this->choseToClear($store, $chose);

		if (isset($clear['result']) && $clear['result'] == false) {
			flash()->error($clear['msg']);
			return redirect(route('menu.show', $store->slug))->with('errorChoseKey', $clear['errorChoseKey']);
		}

		// 檢查訂單
		$check = $this->checkOrderSubmit(['info' => $info, 'clear' => $clear]);
		if ($check['result'] == false) {
			flash()->error($check['msg']);
			return redirect(route('menu.show', $store->slug));
		}

		$request->merge(['info' => $info, 'chose' => $chose, 'clear' => $clear]);
		$order_id = intval($request->input('order_id'));
		$order = Order::findOrNew($order_id);

		if (Auth::check()) {
			// 確定修改訂單
			if ($order->store_id == $store->id && $order->user_id == Auth::user()->id) {
			} else {
				$order = new Order;
			}
			$order->user_id = Auth::user()->id;
		}

		$order->store_id = $store->id;
		$order->price = $info['price'];
		$order->content = json_encode(array_except($request->all(), ['_token']));
		$order->status = $order->step_status['create']['key'];

		$order->save();
		flash()->success('點菜成功');
		$store->fireBaseSync();

		$parameters = ['id' => $order->id, 'created_at' => $order->created_at->toDateTimeString()];
		//dd($order->created_at);

		return redirect(route('order.index', $parameters))->with('order_cookie_name', $store->order_cookie_name);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		//
	}

	/**
	 * 把itemAttr裡的content搬出來
	 * @param  Store  $store [description]
	 * @param  string $type  [description]
	 * @return [type]        [description]
	 */
	public function getStoreItemAttrArray(Store $store, $type = 'array') {
		$attrs = [];
		foreach ($store->itemAttrs()->get() as $attr) {
			$content = $attr->content_array;
			$content['id'] = $content['attr_id'] = $attr->id;

			if ($type == 'keyValue') {
				$options = [];
				foreach ($content['option'] as $option) {
					$options[$option['id']] = $option;
				}
				$content['option'] = $options;
				$attrs[$attr->id] = $content;
			} else {
				$attrs[] = $content;
			}
		}
		return $attrs;
	}

	/**
	 * content前置處理
	 * @param  Array  $data [description]
	 * @return [type]       [description]
	 */
	public function attrContentJson(array $data) {
		$content = [];
		$option = [];
		$ids = [];
		//dd($data);
		if (isset($data['option']) && is_array($data['option'])) {
			foreach ($data['option']['name'] as $key => $name) {
				$id = intval($data['option']['id'][$key]);
				$option[] = [
					'name' => $name,
					'price' => floatval($data['option']['price'][$key]),
					'id' => $id,
				];
				$ids[$id] = true;
			}

			foreach ($option as $key => $tmp) {
				if ($tmp['id'] <= 0) {
					while (isset($ids[$id])) {$id++;}
					$option[$key]['id'] = $id;
					$ids[$id] = true;
				}
			}
		}

		$content = [
			'name' => $data['name'],
			'item_id' => $data['item_id'],
			'max' => $data['max'],
			'option' => $option,
		];
		return json_encode($content);
	}

	/**
	 * 把chose陣列整理成clear
	 * @param  Store  $store [description]
	 * @param  Array  $chose [description]
	 * @return [type]        [description]
	 */
	public function choseToClear(Store $store, array $chose) {
		$tmp123 = [];
		$clear = [];
		$items = $store->items()->get();
		$itemAttrs = $this->getStoreItemAttrArray($store, 'keyValue');
		//dd($chose, $itemAttrs);

		foreach ($chose as $key => $data) {
			if (!is_array($data) || is_null($data['count']) || ($data['count'] = intval($data['count'])) <= 0) {
				continue;
			}

			$tmp = explode($this->demarcation, $key);
			$count = count($tmp);
			$one = [];

			$item = $items->find($data['id']);
			$item_price = $item->price; // 要另外存 不然會改到原價
			if (!$item || $item->status <= 0) {
				$clear = ['result' => false, 'msg' => '查無此商品:' . $data['name']];
				break;
			}

			$one['id'] = $data['id'];
			// 有屬性
			if ($count > 1 && ($count % 2) == 1) {
				for ($i = 1; $i < $count; $i += 2) {
					$attr_id = $tmp[$i];
					$attr = isset($itemAttrs[$attr_id]) ? $itemAttrs[$attr_id] : [];
					$option_id = $tmp[$i + 1];
					if (!$attr || !in_array($one['id'], $attr['item_id']) || is_null($attr['option'][$option_id])) {
						$clear = ['result' => false, 'msg' => '查無此商品:' . $data['name']];
						break 2;
					}

					$item_price += $attr['option'][$option_id]['price'];
					$tmp123[] = $item->toArray();
					$tmp123[] = $attr['option'][$option_id];
					$one['attr'][] = [$tmp[$i] => $option_id];
					$one['attr_count'][$tmp[$i]] = (isset($one['attr_count'][$tmp[$i]])) ? $one['attr_count'][$tmp[$i]] : 0;
					$one['attr_count'][$tmp[$i]]++;
				}

				// 檢察屬性的max
				foreach ($one['attr_count'] as $attr_id => $attr_count) {
					if ($itemAttrs[$attr_id]['max'] && $attr_count > $itemAttrs[$attr_id]['max']) {
						$clear = ['result' => false, 'msg' => implode(' ', array_keys($itemAttrs[$attr_id]['option'])) . '最多只能選' . $itemAttrs[$attr_id]['max']];
						break 2;
					}
				}
			}

			if ($item_price != $data['price']) {
				$clear = ['result' => false, 'msg' => '商品價錢不符合:' . $data['name'] . ' 正確單價:' . $item_price];
				//dd($tmp123 , $chose , $item->toArray());
				break;
			}

			$one['price'] = $data['price'];
			$one['count'] = $data['count'];
			$one['name'] = $data['name'];

			$clear[] = $one;
		}

		// 資料正確  開始排序
		if (!isset($clear['result'])) {
			$clear = array_sort($clear, function ($value) {
				$length = isset($value['attr']) ? count($value['attr']) : 0;
				return ($value['id']) + $length;
			});
		} else {
			$clear['errorChoseKey'] = $key;
		}
		//dd($clear, $chose, $items, $itemAttrs);
		return $clear;
	}

	/**
	 * 檢查總價錢跟前台顯示的一不一樣
	 * @param  Array  $array [description]
	 * @return [type]        [description]
	 */
	public function checkOrderSubmit(array $array) {
		$result = ['result' => true, 'msg' => ''];
		$info = ['count' => 0, 'price' => 0, 'kind' => count($array['clear'])];
		foreach ($array['clear'] as $key => $data) {
			$info['count'] += $data['count'];
			$info['price'] += $data['price'] * $data['count'];
		}

		$diff = array_diff($info, $array['info']);
		if ($diff !== []) {
			$result['result'] = false;
			$key_msg = ['count' => '總數量', 'price' => '總價錢', 'kind' => '種類'];
			foreach ($diff as $key => $value) {
				$result['msg'] .= $key_msg[$key] . '應為' . $value . ' ';
			}
		}
		//dd($array, $info );

		return $result;
	}
}
