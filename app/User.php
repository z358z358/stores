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

	protected $prove = ['email' => 1<<0];

	public function addProve($type)
	{
		$this->status = $this->status | ( $this->prove[$type] );
		return $this;
	}

	public function removeProve($type)
	{
		$this->status = $this->status & ~( $this->prove[$type] );
		return $this;
	}

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
		return $this->belongsTo('App\Oauth', 'id', 'user_id');
	}

}
