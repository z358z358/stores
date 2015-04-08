<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

	//
	

	public function scopeUnfinished($query)
	{
		return $query->where('status', '!=', '100');
	}

}
