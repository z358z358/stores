<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Store;
use Auth;

class StoreController extends Controller {

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$store = Auth::user()->store;
		if($store)
		{
			return redirect( route('store.edit', $store->id) );
		}
		return view('home.store.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreRequest $request)
	{
		$store = Auth::user()->store;
		if($store)
		{
			return redirect( route('store.edit', $store->id) );
		}

		$store = Auth::user()->store()->create($request->all());

		flash()->overlay('商店已建立!', 'Good Job');
		return redirect(route('store.slug', $store->slug));

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($slug)
	{
		$store = Store::findBySlug($slug);

		dd($store->toArray());
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Store $store)
	{
		return view('home.store.edit', compact('store'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Store $store, StoreRequest $request)
	{
		$store->update($request->all());

		flash()->success('修改商店成功');
		return redirect( route('store.edit', $store->id) );
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
