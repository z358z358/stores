<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Store extends Model {

	protected $fillable = ['name', 'info', 'slug', 'address', 'lat', 'lng'];

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

	/**
	 * slug存到資料庫的時候 轉成slug
	 * @param [type] $data [description]
	 */
	public function setSlugAttribute($data)
	{
		$this->attributes['slug'] = str_slug_utf8($data);
	}

	/**
	 * select2使用
	 * @return [type] [description]
	 */
	public function getTagListAttribute()
	{
		return $this->tags->lists('id');
	}

	/**
	 * 把介紹的文字加入連結
	 * @return [type] [description]
	 */
	public function getInfoHtmlAttribute()
	{
		return nl2br(preg_replace(
              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
              "<a target=\"_blank\" href=\"\\0\">\\0</a>",
              $this->attributes['info']));
	}

	/**
	 * meta description用
	 * @return [type] [description]
	 */
	public function getInfoDescAttribute()
	{
		return str_limit($this->attributes['info'], 100, '...');
	}

	/**
	 * 有沒有設定item
	 * @return [type] [description]
	 */
	public function getHasItemAttribute()
	{
		return !is_null($this->items->first());
	}

	/**
	 * 檢查是不是建立者
	 * @return [type] [description]
	 */
	public function getOwnerAttribute()
	{
		return (Auth::user() && $this->attributes['user_id'] == Auth::user()->id);
	}

	/**
	 * 回傳cookie name
	 * @return [type] [description]
	 */
	public function getOrderCookieNameAttribute()
	{
		return 'order' . $this->id;
	}

	/**
	 * 檢查商店權限
	 * @return [type] [description]
	 */
	public function checkAuth()
	{
		$result = false;
		// 商店建立者
		if( $this->owner )
		{
			$result = true;
		}	
		return $result;
	}

	/**
	 * 對應到該使用者
	 * @return [type] [description]
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	/**
	 * 對應的Tag
	 * @return [type] [description]
	 */
	public function tags()
	{
		return $this->belongsToMany('App\Tag');
	}

	/**
	 * 對應的Item
	 * @return [type] [description]
	 */
	public function items()
	{
		return $this->hasMany('App\Item');
	}

	/**
	 * 對應的Order
	 * @return [type] [description]
	 */
	public function orders()
	{
		return $this->hasMany('App\Order');
	}

	/**
	 * 對應的Item
	 * @return [type] [description]
	 */
	public function itemAttrs()
	{
		return $this->hasMany('App\ItemAttr');
	}
}
