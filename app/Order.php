<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

	//
	

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
