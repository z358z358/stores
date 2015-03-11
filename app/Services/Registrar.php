<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	protected $rule_no = '';

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		$messages = [
			'email.required' => 'E-Mail 為必填.',
			'email.unique' => '此E-Mail已註冊. 請更換一個',
			'email.email' => 'E-Mail格式錯誤.',

			'name.required' => '使用者名稱 為必填.',

			'password.required' => '密碼 為必填.',
			'password.min' => '密碼至少要六個字.',
			'password.confirmed' => '確認密碼與密碼不同 請重新輸入.',
		];

		return Validator::make($data, $this->getValidRule(), $messages);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
	}

	public function getValidRule()
	{
		$rule = [];
		$rules = [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		];

		switch ($this->rule_no) {
			case 'only_name':
				$rule['name'] = $rules['name'];
				break;

			case 'only_password':
				$rule['password'] = $rules['password'];
				break;

			case 'only_email':
				$rule['email'] = $rules['email'];
				break;

			default:
				$rule = $rules;
				break;
		}

		return $rule;
	}

	public function setValidRule($rule_no)
	{
		$this->rule_no = $rule_no;
		return $this;
	}

}
