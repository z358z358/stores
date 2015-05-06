<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Order;

class CornController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($type)
	{
		switch ($type) {
			case 'delete_old_order':
				dd($this->delete_old_order());
				break;
			
			default:
				# code...
				break;
		}
		return $type;
	}

	// 刪掉幾天前未完成的訂單
	public function delete_old_order()
	{
		return Order::unfinished()->dayAgo(7)->delete();
	}
}
