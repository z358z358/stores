<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * 預設重導的路徑
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * 拿登入失敗的訊息
	 *
	 * @return string
	 */
	protected function getFailedLoginMesssage()
	{
		return '帳號或密碼錯誤!';
	}

	/**
	 * 從fb登入
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function getLoginFb(Request $request)
	{
		 // get data from request
	    $code = $request->get('code');

	    // get fb service
	    $fb = \OAuth::consumer('Facebook');

	    // if code is provided get user data and sign in
	    if ( ! is_null($code))
	    {
	        // This was a callback request from facebook, get the token
	        $token = $fb->requestAccessToken($code);

	        // Send a request with it
	        $result = json_decode($fb->request('/me'), true);

	        // 找看有沒有註冊過
	        $oauth = \App\Oauth::fbid($result['id'])->first();
	        // 沒註冊
	        if( !$oauth )
	        {
	        	$user = new \App\User;
	        	$user->name = $result['name'];
	        	$user->email = null;
	        	$user->from = 'facebook';
	        	$user->save();

	        	$oauth = new \App\Oauth;
	        	$oauth->from = 'facebook';
	        	$oauth->oauth_id = $result['id'];
	        	$oauth->user_id = $user->id;
	        	$oauth->save();
	        }
	        else
	        {
	        	$user = $oauth->user;
	        }

	        Auth::login($user);
	        return redirect()->intended($this->redirectPath());
	    }
	    // if not ask for permission first
	    else
	    {
	        // get fb authorization
	        $url = $fb->getAuthorizationUri();

	        // return to facebook login url
	        return redirect((string)$url);
	    }
	}

}
