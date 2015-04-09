<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * 認證的代表數字
	 * @var [type]
	 */
	protected $prove = ['email' => 1 ];

	/**
	 * 增加某個認證
	 * @param [type] $type [description]
	 */
	public function addProve($type)
	{
		$this->status = $this->status | ( $this->prove[$type] );
		return $this;
	}

	/**
	 * 移除某個認證
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function removeProve($type)
	{
		$this->status = $this->status & ~( $this->prove[$type] );
		return $this;
	}

	/**
	 * 回傳使用者有沒有某個認證
	 * @param  [type] $type [description]
	 * @return bool       [description]	
	 */
	public function checkProve($type)
	{
		return ($this->status & ( $this->prove[$type] ));
	}

	/**
	 * 對應到第三方登入
	 * @return [type] [description]
	 */
	public function oauth()
	{
		return $this->belongsTo('App\Oauth');
	}

	/**
	 * 對應到該使用者開的店
	 * @return [type] [description]
	 */
	public function store()
	{
		return $this->hasOne('App\Store');
	}

	/**
	 * 對應到該使用者的訂單
	 * @return [type] [description]
	 */
	public function orders()
	{
		return $this->hasMany('App\Order');
	}

}
