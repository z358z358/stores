<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    protected $rule_no = '';

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
     * 拿登入失敗的訊息
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return 'E-Mail或密碼錯誤!';
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
        $fb = \SocialOAuth::consumer('Facebook');

        // if code is provided get user data and sign in
        if (!is_null($code)) {
            // This was a callback request from facebook, get the token
            $token = $fb->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($fb->request('/me'), true);

            // 找看有沒有註冊過
            $oauth = \App\Oauth::fbid($result['id'])->first();
            // 沒註冊
            if (!$oauth) {
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
            } else {
                $user = $oauth->user;
            }

            Auth::login($user, true);
            return redirect()->intended($this->redirectPath());
        }
        // if not ask for permission first
        else {
            // get fb authorization
            $url = $fb->getAuthorizationUri();

            // return to facebook login url
            return redirect((string) $url);
        }
    }

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
