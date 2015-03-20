<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

	/**
	 * 對應的Store
	 * @return [type] [description]
	 */
	public function store()
	{
		return $this->belongsTo('App\Store');
	}

}
