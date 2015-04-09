<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Attr extends Model {

	protected $fillable = ['content'];

	//
	public function items()
	{
		return $this->belongsToMany('App\Item');
	}
}
