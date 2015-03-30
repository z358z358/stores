<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

	protected $fillable = ['name', 'price', 'item_id', 'status'];

	/**
	 * 對應的Store
	 * @return [type] [description]
	 */
	public function store()
	{
		return $this->belongsTo('App\Store');
	}

}
