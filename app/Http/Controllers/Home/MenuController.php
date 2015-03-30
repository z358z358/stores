<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Store;
use App\Item;

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
	public function show()
	{

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
		$store->items()->delete();
		$items = [];
		$count = 1;

		if($request->input('items')) 
		{
			foreach ($request->input('items') as $item)
			{
				$inc = ($item['status'] >= 0)? 1: -1;
				$item['status'] = $count*$inc;
				$items[] = new Item($item);
				$count++;
			}
		}

		//Item::where('store_id', $store->id)->whereNotIn('id', $audiochannel_ids)->delete();
		$store->items()->saveMany($items);

		flash()->success('修改菜單成功');
		return redirect( route('menu.edit', $store->id) );
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

}
