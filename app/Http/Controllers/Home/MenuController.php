<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Store;
use App\Item;
use Auth;
use DB;

class MenuController extends Controller {

	// 分隔用的keyword
	public $demarcation = '|';

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show', 'submit']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($slug)
	{
		$store = Store::findBySlug($slug);
		dd($store);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Store $store)
	{
		$items = $store->items()->orderBy('status', 'asc')->get();
		$itemAttrs = $this->getStoreItemAttrArray($store);
		$demarcation = $this->demarcation;

		return view('home.menu.show', compact('store', 'items', 'itemAttrs', 'demarcation'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Store $store)
	{
		$items = $store->items()->orderBy('status', 'asc')->get();

		return view('home.menu.edit', compact('store', 'items'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Store $store, Request $request)
	{
		//dd($request->all());
		$items = [];
		$count = 1;

		if($request->input('items')) 
		{
			foreach ($request->input('items') as $data)
			{
				$inc = ($data['status'] >= 0)? 1: -1;
				$data['status'] = $count*$inc;
				$item = $store->items()->findOrNew($data['id']);
				$item->fill($data);
				$item->save();
				$items[] = $item->id;
				$count++;
			}
		}

		$store->items()->whereNotIn('id', $items)->delete();

		flash()->success('修改菜單成功');
		return redirect( route('menu.edit', $store->id) );
	}

	/**
	 * 商品屬性編輯頁面
	 * @param  Store  $store [description]
	 * @return [type]        [description]
	 */
	public function attrEdit(Store $store)
	{
		DB::enableQueryLog();
		$item_list = $store->items()->lists('name', 'id');
		$attrs = $this->getStoreItemAttrArray($store);
		
		//dd($attrs);
		return view('home.menu.attrEdit', compact('store', 'item_list', 'attrs'));
	}

	/**
	 * 更新商品屬性
	 * @param  Store   $store   [description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function attrUpdate(Store $store, Request $request)
	{
		DB::enableQueryLog();
		//dd($request->all());
		$items = [];
		$itemAttrs = [];
		// 先把attr存起來 & item對應的attr存到$items
		if(is_array($request->get('attr')))
		{
			foreach($request->get('attr') as $data)
			{
				$data['item_id'] = isset($data['item_id'])? $data['item_id'] : [];
				$attr = $store->itemAttrs()->find($data['attr_id']);
				if($attr)
				{
					$attr->content = $this->attrContentJson($data);
					$attr->save();
					$attr_id = $attr->id;
				}
				else
				{
					$newAttr = \App\ItemAttr::create(['store_id' => $store->id, 'content' => $this->attrContentJson($data)]);
					$attr_id = $newAttr->id;
				}

				if(isset($data['item_id']))
				{
					foreach($data['item_id'] as $item_id)
					{
						$items[$item_id][] = $attr_id;
					}
				}
				$itemAttrs[] = $attr_id;
			}
		}

		// item sync attrs
		foreach($store->items()->whereIn('id', array_keys($items))->with('itemAttrs')->get() as $item)
		{
			$item->itemAttrs()->sync($items[$item->id]);
		}

		// 清掉沒attr的item
		foreach($store->items()->whereNotIn( 'id', array_keys($items) )->with('itemAttrs')->get() as $item)
		{
			$item->itemAttrs()->sync([]);
		}

		// 清掉沒post的attr
		$store->itemAttrs()->whereNotIn('id', $itemAttrs)->delete();

		flash()->success('修改菜單成功');
		return redirect( route('menu.attr.edit', $store->id) );

	}

	/**
	 * 建立訂單
	 * @param  Store   $store   [description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function submit(Store $store, Request $request)
	{
		$info = json_decode($request->input('info'), true);
		$chose = json_decode($request->input('chose'), true);
		$clear = $this->choseToClear($store, $chose);
		
		if(is_null($clear[0]) && $clear['result'] == false)
		{
			flash()->error($clear['msg']);
			return redirect( route('menu.show', $store->slug) );
		}

		// 檢查訂單
		$check = $this->checkOrderSubmit(['info' => $info, 'clear' => $clear]);
		if($check['result'] == false)
		{
			flash()->error($check['msg']);
			return redirect( route('menu.show', $store->slug) );
		}

		$request->merge(['info' => $info, 'chose' => $chose, 'clear' => $clear]);
		$order = New \App\Order;
		$order->store_id = $store->id;
		$order->content = json_encode( array_except($request->all(), ['_token']) );
		if(Auth::check()){
			$order->user_id = Auth::user()->id;
		}
	
		$order->save();
		flash()->success('點菜成功');

		$parameters = ['id' => $order->id, 'created_at' => $order->created_at->toDateTimeString()];
		//dd($order->created_at);

		return redirect( route('order.index', $parameters) )->with('order_cookie_name', $store->order_cookie_name);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * 把itemAttr裡的content搬出來
	 * @param  Store  $store [description]
	 * @param  string $type  [description]
	 * @return [type]        [description]
	 */
	public function getStoreItemAttrArray(Store $store, $type = 'array')
	{
		$attrs = [];
		foreach($store->itemAttrs()->get() as $attr)
		{
			$content = $attr->content_array;
			$content['id'] = $contentp['attr_id'] = $attr->id;

			if($type == 'keyValue')
			{
				$attrs[$attr->id] = $content;
			}
			else
			{
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
	public function attrContentJson(Array $data)
	{
		$content = [];
		$option = [];

		if(is_array($data['option']))
		{
			foreach ($data['option']['name'] as $key => $name) {
				$option[$name] = $data['option']['price'][$key];
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
	public function choseToClear(Store $store, Array $chose)
	{
		$clear = [];
		$items = $store->items()->get();
		$itemAttrs = $this->getStoreItemAttrArray($store, 'keyValue');

		foreach($chose as $key => $data)
		{
			if( !is_array($data) || is_null($data['count']) || ($data['count'] = intval($data['count'])) <= 0 )
			{
				continue;
			}

			$tmp = explode($this->demarcation, $key);
			$count = count($tmp);
			$one = [];

			$item = $items->find($data['id']);
			if(!$item)
			{
				$clear = ['result' => false, 'msg' => '查無此商品:' . $data['name']];
				break;
			}

			$one['id'] = $data['id'];
			// 有屬性
			if($count > 1 && ($count % 2) == 1)
			{
				for($i = 1; $i < $count; $i+=2)
				{
					$attr_id = $tmp[$i];
					$attr = $itemAttrs[$attr_id];
					if( !$attr || !in_array($one['id'], $attr['item_id']) || is_null($attr['option'][$tmp[$i+1]]) )
					{
						$clear = ['result' => false, 'msg' => '查無此商品:' . $data['name']];
						break;
					}

					$item->price += $attr['option'][$tmp[$i+1]];
					$one['attr'][] = [$tmp[$i] => $tmp[$i+1]];
				}
			}

			if($item->price != $data['price'])
			{
				$clear = ['result' => false, 'msg' => '商品價錢不符合:' . $data['name'] . ' 正確單價:' . $item->price];
				break;
			}

			$one['price'] = $data['price'];
			$one['count'] = $data['count'];
			$one['name'] = $data['name'];

			$clear[] = $one;

		}
		//dd($clear, $chose, $items, $itemAttrs);
		return $clear;
	}


	public function checkOrderSubmit(Array $array)
	{
		$info = [];
		foreach ($array['clear'] as $key => $data) 
		{
			
		}
		dd($array);
	}

}
