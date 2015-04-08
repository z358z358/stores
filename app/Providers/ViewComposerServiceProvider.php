<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->composeNavigation();
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Compose the navigation
	 */
	private function composeNavigation()
	{
		view()->composer('partials.nav', function($view)
		{
			if(Auth::check())
			{
				$store = Auth::user()->store;
				$user_status = '';
				if($store)
				{
					$user_status = '<li><a href="' . route('store.slug', $store->slug) . '">店的頁面</a></li>';
				}
				$user_status .= '
				<li><a href="' . url('/store/create') . '">開店/修改店</a></li>
				<li><a href="' . url('/settings/main') . '">HI!' . Auth::user()->name . '</a></li>
				<li><a href="' . url('/auth/logout') . '">登出</a></li>';
			}
			else
			{
				$user_status = '
				<li>
                    <a href="' . url('/auth/login') . '">登入</a>
                </li>
                <li>
                    <a href="' . url('/auth/register'). '">註冊</a>
                </li>';
			}
			$view->with('user_status', $user_status);
		});
	}

}
