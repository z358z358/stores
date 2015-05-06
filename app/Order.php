<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon ;

class Order extends Model {

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

	public function scopeIdAndCreated($query, Array $array)
	{
		return $query->where('id', $array['id'])->where('created_at', $array['created_at']);
	}

	public function getContentArrayAttribute()
	{
		return json_decode($this->content, true);
	}

	public function store()
	{
		return $this->belongsTo('App\Store');
	}

}
