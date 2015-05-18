<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon ;

class Order extends Model {

	public $step_status = [
		'create' => ['key' => 50, 'name' => '剛建立訂單'],
		'accept' => ['key' => 75, 'name' => '店家已接受'],
		'done'   => ['key' => 100,'name' => '訂單完成'],
	];

	// 幾天前的
	public function scopeDayAgo($query, $day)
	{
		$day = intval($day);
		return $query->where('updated_at', '<=', Carbon::now()->subDays($day));
	}

	// 未完成
	public function scopeUnfinished($query)
	{
		return $query->where('status', '!=', '100');
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

	public function store()
	{
		return $this->belongsTo('App\Store');
	}

}
