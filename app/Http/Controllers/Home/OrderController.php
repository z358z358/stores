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
			$orders = $this->withToken($orders);
		} else if ($request->input('id') && $request->input('created_at')) {
			$where = array_only($request->all(), ['id', 'created_at']);
			$orders = Order::idAndCreated($where)->with('store')->get();
			$orders = $this->withToken($orders);
			//dd($orders);
		}
		$order_cookie_name = session('order_cookie_name');

		JavaScript::put(['orders' => $orders]);

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
			$orders = $store->orders()->unfinished()->orderByTime()->with('store')->get();
			$orders = $this->withToken($orders);

			JavaScript::put(['__act' => 'home.order.storeOrder', 'orders' => $orders]);

			return view('home.order.storeOrder', compact('orders'));
		}
	}

	/**
	 * 店家已完成的訂單頁面
	 * @param  Store  $store [description]
	 * @return [type]        [description]
	 */
	public function storeOrderFinish(Store $store) {
		if ($store->checkAuth()) {
			$user = Auth::user();
			$orders_page = $store->orders()->finished()->orderByTime()->with('store')->paginate($this->page_size);
			$orders_page->setPath(''); // 網址可能有全形字 分頁的網址會錯誤
			$orders = $this->withToken($orders_page);

			JavaScript::put(['__act' => 'home.order.storeOrder', 'orders' => $orders]);

			return view('home.order.storeOrderFinish', compact('user', 'orders_page'));
		}
	}

	/**
	 * 更新order
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request) {
		$order = Order::findOrfail($request->input('id'));
		$store = Store::findOrfail($request->input('store_id'));
		$step = $request->input('step');

		// 檢查權限
		if ($store->checkAuth()) {
			// 檢查token
			if ($order->checkToken($request->input('order_token')) && isset($order->step_status[$step])) {
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
	public function destroy(Request $request) {
		$order = Order::findOrfail($request->input('id'));
		$store = $order->store()->first();
		// 檢查本人 或是店家
		if ((null === $order->user_id) || (Auth::check() && Auth::user()->id == $order->user_id) || ($store->checkAuth())) {
			// 檢查token
			if ($order->checkToken($request->input('order_token'))) {
				//$order->status = $order->step_status['del']['key'];
				//$order->save();
				$order->delete();
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
	public function withToken($orders) {
		$result = [];
		foreach ($orders as $order) {
			$order->order_token = $order->token;
			$order->status_name = $order->step_status_num[$order->status]['name'];
			$order->content = $order->content_array;
			$result[] = $order;
		}
		return $result;
	}
}
