<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

	protected $fillable = ['name', 'price', 'status'];

	public function scopeStatusOn($query) {
		$query->where('status', '>=', '1');
	}

	/**
	 * 對應的Store
	 * @return [type] [description]
	 */
	public function store() {
		return $this->belongsTo('App\Store');
	}

	/**
	 * 有許多attrs
	 * @return [type] [description]
	 */
	public function itemAttrs() {
		return $this->belongsToMany('App\ItemAttr', 'item_attr_item');
	}

}
