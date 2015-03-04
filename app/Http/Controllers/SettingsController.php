<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class SettingsController extends Controller {

	use AuthenticatesAndRegistersUsers;

	/**
	 * 初始化 設定需要登入
	 *
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('auth');
	}

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
		return view('user.settings' , compact('user', 'tab'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($tab, Request $request)
	{
		switch ($tab) {
			// 修改使用者名稱
			case 'main':
				$validator = $this->registrar->setValidRule('only_name')->validator($request->all());
				if ($validator->fails())
				{
					$this->throwValidationException(
						$request, $validator
					);
				}

				Auth::user()->update($request->all());
				flash()->success('修改資料成功!');
				break;

			// 修改密碼
			case 'password':
				$credentials = [
					'email' => Auth::user()->email,
					'password' => $request->input('password_old'),
				];

				if( !Auth::attempt($credentials, false, false))
				{
					flash()->error('目前的密碼不對!');
					break;
				}

				$validator = $this->registrar->setValidRule('only_password')->validator($request->all());
				if ($validator->fails())
				{
					$this->throwValidationException(
						$request, $validator
					);
				}

				Auth::user()->update($request->all());
				flash()->success('修改密碼成功!');
				break;

			default:
				# code...
				break;
		}
		return redirect('settings/' . $tab . '/edit');
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
