<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Store;
use Auth;
use Share;

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
		$tags = \App\Tag::lists('name', 'id');

		if($store)
		{
			return redirect( route('store.edit', $store->id) );
		}
		return view('home.store.create' , compact('tags'));
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
		$this->syncTags($store, $request->input('tag_list'));

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
		$share = Share::load(route('store.showById', $store->id), $store->info_desc)->services('facebook', 'gplus', 'twitter');

		return view('home.store.show', compact('store', 'share'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Store $store)
	{
		$tags = \App\Tag::lists('name', 'id');

		return view('home.store.edit', compact('store', 'tags'));
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
		$this->syncTags($store, $request->input('tag_list'));

		flash()->success('修改商店成功');
		return redirect( route('store.edit', $store->id) );
	}

	/**
	 * 同步tag  若沒有 則新增
	 * @param  Store  $store [description]
	 * @param  array  $tags  [description]
	 * @return [type]        [description]
	 */
	public function syncTags(Store $store, $tags)
	{
		$tags = is_array($tags) ? $tags : [];
		foreach ($tags as $key => $tag) {
			if(is_numeric($tag) && \App\Tag::find($tag))
			{
				continue;
			}
			$newTag = \App\Tag::create(['name' => $tag, 'slug' => $tag]);
			$tags[$key] = $newTag->id;
		}

		$store->tags()->sync($tags);
	}

	public function showById(Store $store)
	{
		return redirect( route('store.slug', $store->slug) );
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
