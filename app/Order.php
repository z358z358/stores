<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Hash;

class Order extends Model {

	public $step_status = [
		'del'	 => ['key' => 0,  'name' => '待刪除'],
		'create' => ['key' => 50, 'name' => '剛建立訂單，等待訂單接受'],
		'accept' => ['key' => 75, 'name' => '店家已接受，等待確認付款'],
		'done'   => ['key' => 100,'name' => '訂單完成'],
	];

	public $step_status_num = []; // __construct

	public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        // 把數字key存在另一個變數
        foreach($this->step_status as $key => $data)
        {
        	$tmp = $data['key'];
        	$data['key'] = $key;
			$this->step_status_num[$tmp] = $data;
        }        
    }

	// 幾天前的
	public function scopeDayAgo($query, $day)
	{
		$day = intval($day);
		return $query->where('updated_at', '<=', Carbon::now()->subDays($day));
	}

	// 未完成的
	public function scopeUnfinished($query)
	{
		return $query->where('status', '!=', '100');
	}

	// 完成的
	public function scopeFinished($query)
	{
		return $query->where('status', '=', '100');
	}

	// 從id和建立時間去找訂單
	public function scopeIdAndCreated($query, Array $array)
	{
		return $query->where('id', $array['id'])->where('created_at', $array['created_at']);
	}

	// 照更新時間排序
	public function scopeOrderByTime($query)
	{
		return $query->OrderBy('updated_at', 'DESC');
	}

	// Array的content
	public function getContentArrayAttribute()
	{
		return json_decode($this->content, true);
	}

	// order的token
	public function getTokenAttribute()
	{
		return bcrypt($this->created_at);
	}

	public function checkToken($token)
	{
		return Hash::check($this->created_at, $token);
	}

	public function store()
	{
		return $this->belongsTo('App\Store');
	}

}
