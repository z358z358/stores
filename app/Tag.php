<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $fillable = ['name', 'slug'];

	public function articles()
	{
		return $this->belongsToMany('App\Article');
	}

	public function setSlugAttribute($data)
	{
		if(str_slug_utf8($data) != '')
		{
			$this->attributes['slug']=str_slug_utf8($data);
		}
		else
		{
			$this->attributes['slug']=$data;
		}
	}

	public function scopeFindBySlug($query, $slug)
	{
		return $query->whereSlug($slug)->firstOrFail();
	}


}
