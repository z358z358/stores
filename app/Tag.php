<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $fillable = ['name', 'slug'];

	public function stores()
	{
		return $this->belongsToMany('App\Store');
	}

	public function setSlugAttribute($data)
	{
		$slug = str_slug_utf8($data);
		if($slug != '')
		{
			$this->attributes['slug'] = $slug;
		}
		else
		{
			$this->attributes['slug'] = $data;
		}
	}

	public function scopeFindBySlug($query, $slug)
	{
		return $query->whereSlug($slug)->firstOrFail();
	}


}
