<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAttr extends Model {

	protected $fillable = ['store_id', 'content'];

	public function getContentArrayAttribute()
	{
		return json_decode($this->content, true);
	}

	//
	public function items()
	{
		return $this->belongsToMany('App\Item', 'item_attr_item');
	}

}
