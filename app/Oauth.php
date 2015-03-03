<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Oauth extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'oauths';

	/**
	 * Scope queries to articles thas have been published.
	 *
	 * @param  $query
	 */
	public function scopeFbid($query, $fb_id)
	{
		$query->where('from', '=', 'facebook')->where('oauth_id', '=', $fb_id);
	}

	public function scopePublished($query)
	{
		$query->where('published_at', '<=', Carbon::now());
	}

	/**
	 * [articles description]
	 * @return [type] [description]
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

}
