<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Store;
use App\Item;
use Auth;
use DB;

class MenuController extends Controller {

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show']]);
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
		$itemAttrs = $attrs = $this->getStoreItemAttrArray($store);

		return view('home.menu.show', compact('store', 'items', 'itemAttrs'));
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
		$order = New \App\Order;
		$order->content = json_encode( array_except($request->all(), ['_token']) );
		$order->store_id = $store->id;
		$order->user_id = Auth::user()->id;
		$order->save();
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

	public function getStoreItemAttrArray(Store $store)
	{
		$attrs = [];
		foreach($store->itemAttrs()->get() as $attr)
		{
			$content = $attr->content_array;
			$content['id'] = $contentp['attr_id'] = $attr->id;

			$attrs[] = $content;
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

}
