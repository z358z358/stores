<?php namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Order;
use Cookie;
use DB;
use Hash;
use App\Store;

class OrderController extends Controller {

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		DB::enableQueryLog();
		$user = New Auth;
		$orders = [];
		if(Auth::check())
		{
			$user = Auth::user();
			$orders = $user->orders()->unfinished()->orderByTime()->with('store')->get();
			$orders = $this->withToken($orders);
		}
		else if($request->input('id') && $request->input('created_at'))
		{
			$where = array_only($request->all(), ['id', 'created_at']);
			$orders = Order::idAndCreated($where)->with('store')->get();
			$orders = $this->withToken($orders);
			//dd($orders);
		}

		$order_cookie_name = session('order_cookie_name');
		
		return view('home.order.index', compact('user', 'orders', 'order_cookie_name'));
	}

	/**
	 * 店家管理未完成訂單頁面
	 * @param  Store   $store   [description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function storeOrder(Store $store, Request $request)
	{
		DB::enableQueryLog();
		$user = Auth::user();
		if( $store->checkAuth() )
		{
			$orders = $store->orders()->unfinished()->orderByTime()->with('store')->get();
			$orders = $this->withToken($orders);
			return view('home.order.storeOrder', compact('user', 'orders'));
		}
			
	}

	/**
	 * 更新order
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request)
	{
		$order = Order::findOrfail($request->input('id'));
		$store = Store::findOrfail($request->input('store_id'));
		$step = $request->input('step');

		// 檢查權限
		if( $store->checkAuth() )
		{	
			// 檢查token
			if( $order->checkToken( $request->input('order_token') ) && isset($order->step_status[$step]) )
			{
				$order->status = $order->step_status[$step]['key'];
				$order->save();
				flash()->success('更新訂單編號' . $order->id . ' 成功');
			}
		}
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		$order = Order::findOrfail($request->input('id'));
		// 檢查本人
		if( (null === $order->user_id) || (Auth::check() && Auth::user()->id == $order->user_id) )
		{	
			// 檢查token
			if( $order->checkToken( $request->input('order_token') ) )
			{
				$order->status = $order->step_status['del']['key'];
				$order->save();
				flash()->success('刪除訂單編號' . $order->id . ' 成功');
			}
		}
		return redirect()->back();
	}	

	/**
	 * 將model的token變成變數
	 * @param  [type] $orders [description]
	 * @return [type]         [description]
	 */
	public function withToken($orders)
	{
		$result = [];
		foreach($orders as $order)
		{
			$order->order_token = $order->token;
			$order->status_name = $order->step_status_num[$order->status]['name'];
			$result[] = $order;
		}
		return $result;
	}
}
