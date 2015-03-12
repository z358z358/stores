<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {

	protected $fillable = ['name', 'info', 'slug'];

	/**
	 * 從slug找商店
	 * @param  [type] $query [description]
	 * @param  [type] $slug  [description]
	 * @return [type]        [description]
	 */
	public function scopeFindBySlug($query, $slug)
	{
		return $query->whereSlug($slug)->firstOrFail();
	}

	public function setSlugAttribute($data)
	{
		$this->attributes['slug'] = str_slug($data);
	}

	/**
	 * 對應到該使用者
	 * @return [type] [description]
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
