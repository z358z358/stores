<?php namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Order;
use App\Store;
use Auth;
use Illuminate\Http\Request;
use JavaScript;

class OrderController extends Controller {

	protected $page_size = 15;

	public function __construct() {
		$this->middleware('auth', ['except' => ['index', 'destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$user = New Auth;
		$orders = [];
		if (Auth::check()) {
			$user = Auth::user();
			$orders = $user->orders()->unfinished()->orderByTime()->with('store')->get();
		} else if ($request->input('id') && $request->input('created_at')) {
			$where = array_only($request->all(), ['id', 'created_at']);
			$orders = Order::idAndCreated($where)->with('store')->get();
			//dd($orders);
		}
		$order_cookie_name = session('order_cookie_name');

		JavaScript::put(['orders' => $orders, 'order_cookie_name' => $order_cookie_name]);

		return view('home.order.index');
	}

	/**
	 * 店家管理未完成訂單頁面
	 * @param  Store   $store   [description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function storeOrder(Store $store) {
		if ($store->checkAuth()) {
			$title = '未完成的訂單';
			//$orders = $store->orders()->unfinished()->orderByTime()->with('store')->get();

			$orders_page = null;
			$useFirebase = true;

			JavaScript::put(['store' => $store]);

			return view('home.order.storeOrder', compact('orders_page', 'title', 'useFirebase'));
		}
	}

	/**
	 * 店家已完成的訂單頁面
	 * @param  Store  $store [description]
	 * @return [type]        [description]
	 */
	public function storeOrderFinish(Store $store) {
		if ($store->checkAuth()) {
			$title = '完成的訂單';
			$user = Auth::user();
			$orders_page = $store->orders()->finished()->orderByTime()->with('store')->paginate($this->page_size);
			$orders_page->setPath(''); // 網址可能有全形字 分頁的網址會錯誤

			JavaScript::put(['orders' => $orders]);

			return view('home.order.storeOrder', compact('orders_page', 'title'));
		}
	}

	/**
	 * 更新order
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request) {
		//dd($request->all());
		$order = Order::findOrfail($request->input('id'));
		$store = Store::findOrfail($request->input('store_id'));
		$step = $request->input('step');

		// 檢查權限
		if ($store->checkAuth()) {
			// 檢查token
			if ($order->checkToken($request->input('token')) && isset($order->step_status[$step])) {
				$order->status = $order->step_status[$step]['key'];
				$order->save();
				$store->fireBaseSync();
				if ($request->ajax()) {
					return ['msg' => [['type' => 'success', 'content' => '更新訂單編號' . $order->id . ' 成功']]];
				} else {
					flash()->success('更新訂單編號' . $order->id . ' 成功');
				}
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
	public function destroy(Request $request) {
		$order = Order::findOrfail($request->input('id'));
		$store = $order->store()->first();
		// 檢查本人 或是店家
		if ((null === $order->user_id) || (Auth::check() && Auth::user()->id == $order->user_id) || ($store->checkAuth())) {
			// 檢查token
			if ($order->checkToken($request->input('token'))) {
				//$order->status = $order->step_status['del']['key'];
				//$order->save();
				$order->delete();
				flash()->success('刪除訂單編號' . $order->id . ' 成功');
				$store->fireBaseSync();
			}
		}
		return redirect()->back();
	}

	/**
	 * 轉址編輯商品
	 * @param  Request $request    [description]
	 * @param  Order   $order      [description]
	 * @param  string  $created_at [description]
	 * @return [type]              [description]
	 */
	public function editById(Request $request, Order $order, $created_at = '') {
		//dd($order->toArray(), $order_token);
		return redirect()->route('menu.show', [$order->store()->first()->slug, $order->id, $created_at]);
	}
}
