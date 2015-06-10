<?php namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Mail;

class SettingsController extends Controller
{

    use AuthenticatesAndRegistersUsers;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return redirect('settings/main/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($tab)
    {
        return redirect('settings/' . $tab . '/edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($tab)
    {
        $user = Auth::user();
        //dd($user->checkProve('email'));
        return view('home.user.settings', compact('user', 'tab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($tab, Request $request)
    {
        $user = Auth::user();
        switch ($tab) {
            // 修改使用者名稱
            case 'main':
                $validator = $this->registrar->setValidRule('only_name')->validator($request->all());
                if ($validator->fails()) {
                    $this->throwValidationException(
                        $request, $validator
                    );
                }

                $user->update($request->all());
                flash()->success('修改資料成功!');
                break;

            // 修改密碼
            case 'password':
                $credentials = [
                    'email' => $user->email,
                    'password' => $request->input('password_old'),
                ];

                if (!Auth::attempt($credentials, false, false)) {
                    flash()->error('目前的密碼不對!');
                    break;
                }

                $validator = $this->registrar->setValidRule('only_password')->validator($request->all());
                if ($validator->fails()) {
                    $this->throwValidationException(
                        $request, $validator
                    );
                }

                $user->update($request->all());
                flash()->success('修改密碼成功!');
                break;

            // 修改Email
            case 'email':
                $validator = $this->registrar->setValidRule('only_email')->validator($request->all());
                if ($validator->fails()) {
                    $this->throwValidationException(
                        $request, $validator
                    );
                }

                $user->removeProve('email')->update($request->all());
                flash()->success('修改E-mail成功!');
                break;

            default:
                # code...
                break;
        }
        return redirect('settings/' . $tab . '/edit');
    }

    /**
     * 寄認證信
     * @return [type] [description]
     */
    public function emailProveSend()
    {
        $user = Auth::user();

        if ($user->email) {
            $token = new \App\Token();
            $token->type = 'email';
            $token->user_id = $user->id;
            $token->key = $user->email;
            $token->token = str_random(40);
            $token->save();

            Mail::queue('emails.proveEmail', compact('user', 'token'), function ($message) use ($user) {
                $message->to($user->email, $user->name)->subject('認證信');
            });
            flash()->success('認證信已寄出，請前往信箱認證!');
        }

        return redirect('settings/email/edit');
    }

    /**
     * 檢查email認證碼
     * @param  [type] $email_token [description]
     * @return [type]              [description]
     */
    public function emailProveCheck($email_token)
    {
        $user = Auth::user();
        $token = \App\Token::type('email')->key($user->email)->token($email_token)->first();
        if (!$user->checkProve('email') && $token) {
            $user->addProve('email')->save();
            $token->delete();
            flash()->success($user->email . '認證成功!');
        }

        return redirect('settings/email/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
