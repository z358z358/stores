<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use Cookie;

class OrderController extends Controller {

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$user = New Auth;
		$orders = [];
		if(Auth::check())
		{
			$user = Auth::user();
			$orders = $user->orders()->unfinished()->get();
		}
		else if($request->input('id') && $request->input('created_at'))
		{
			$where = array_only($request->all(), ['id', 'created_at']);
			$orders = Order::idAndCreated($where)->get();
			//dd($orders);
		}

		$order_cookie_name = session('order_cookie_name');
		
		return view('home.order.index', compact('user', 'orders', 'order_cookie_name'));
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
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
