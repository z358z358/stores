<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {

	//
	public function scopeType($query, $type)
	{
		$query->where('type', '=', $type);
	}

	public function scopeToken($query, $token)
	{
		$query->where('token', '=', $token);
	}

	public function scopeKey($query, $key)
	{
		$query->where('key', '=', $key);
	}


}
