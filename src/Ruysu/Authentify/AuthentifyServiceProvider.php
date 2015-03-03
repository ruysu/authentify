<?php namespace Ruysu\Authentify;

use Illuminate\Support\ServiceProvider;
use Ruysu\Authentify\Commands\UsersTable;
use Ruysu\Authentify\Commands\SocialProfilesTable;
use Hybrid_Auth;
use Response;

class AuthentifyServiceProvider extends ServiceProvider {
	protected $defer = false;

	public function boot() {
		$this->package('ruysu/authentify');

		$this->app['router']->before(function() {
			$this->app['view']->composer('user', $this->app['auth']->user());
		});

		$this->registerRoutes();
	}

	public function register() {
		$this->registerCommands();
		$this->registerHybridAuth();
		$this->registerFilters();
	}

	protected function registerCommands() {
		$this->app->bind('authentify.users-table', function() {
			return new UsersTable;
		});

		$this->app->bind('authentify.social_profiles-table', function () {
			return new SocialProfilesTable;
		});

		$this->commands('authentify.users-table', 'authentify.social_profiles-table');
	}

	protected function registerHybridAuth() {
		$this->app->bind('authentify.hybridauth', function($app, $url) {
			return new Hybrid_Auth(array(
				'base_url' => $url,
				'providers' => $app['config']->get('authentify::social.hybridauth')
			));			
		});
	}

	protected function registerFilters() {
		$app = $this->app;

		$this->app['router']->filter('authentify.check', function() use ($app) {
			if ($app['auth']->guest()) {
				if ($app['request']->ajax()) {
					return Response::make('Unauthorized', 401);
				}
				else {
					return $app['redirect']->guest($app['url']->action('Authentify\SignInController@getIndex'));
				}
			}
		});

		$this->app['router']->filter('authentify.guest', function() use ($app) {
			if ($app['auth']->check()) {
				return $app['redirect']->to('/');
			}
		});
	}

	protected function registerRoutes() {
		if (!$this->config('routes.auto')) return;

		$prefix = $this->routePrefix();
		$router = $this->app['router'];
		$name_prefix = $this->routePrefix('.');

		$router->group(['namespace' => 'Authentify', 'prefix' => $prefix], function () use ($router, $name_prefix) {
			$router->group(['before' => 'authentify.guest'], function() use ($router, $name_prefix) {
				$this->registerSignInRoutes($name_prefix, $router);
				$this->registerSignUpRoutes($name_prefix, $router);
				$this->registerRemindRoutes($name_prefix, $router);
				$this->registerSocialRoutes($name_prefix, $router);
			});

			$router->group(['before' => 'authentify.check'], function() use ($router, $name_prefix) {
				$this->registerSignOutRoutes($name_prefix, $router);
				$this->registerEditRoutes($name_prefix, $router);
				$this->registerPasswordRoutes($name_prefix, $router);
			});
		});
	}

	protected function registerSignInRoutes($prefix, $router) {
		$router->get("sign-in", ['uses' => 'SignInController@getIndex', 'as' => $prefix . 'sign-in']);
		$router->post("sign-in", ['uses' => 'SignInController@postIndex']);
	}

	protected function registerSignOutRoutes($prefix, $router) {
		$router->delete("sign-out", ['uses' => 'SignOutController@anyIndex', 'as' => $prefix . 'sign-out']);
		$router->get("sign-out", ['uses' => 'SignOutController@anyIndex']);
	}

	protected function registerSignUpRoutes($prefix, $router) {
		if (!$this->config('registerable')) return;
		$router->get("sign-up", ['uses' => 'SignUpController@getIndex', 'as' => $prefix . 'sign-up']);
		$router->post("sign-up", ['uses' => 'SignUpController@postIndex']);
		$router->get("activate/{token}", ['uses' => 'SignUpController@getActivate', 'as' => $prefix . 'activate']);
	}

	protected function registerRemindRoutes($prefix, $router) {
		if (!$this->config('remindable')) return;
		$router->get("remind", ['uses' => 'RemindController@getIndex', 'as' => $prefix . 'remind']);
		$router->post("remind", ['uses' => 'RemindController@postIndex']);
		$router->get("reset/{token}", ['uses' => 'RemindController@getReset', 'as' => $prefix . 'reset']);
		$router->post("reset", ['uses' => 'RemindController@postReset']);
	}

	protected function registerEditRoutes($prefix, $router) {
		if (!$this->config('editable.info')) return;
		$router->get("edit", ['uses' => 'EditController@getIndex', 'as' => $prefix . 'edit']);
		$router->post("edit", ['uses' => 'EditController@postIndex']);
	}

	protected function registerPasswordRoutes($prefix, $router) {
		if (!$this->config('editable.password')) return;
		$router->get("password", ['uses' => 'PasswordController@getIndex', 'as' => $prefix . 'password']);
		$router->post("password", ['uses' => 'PasswordController@postIndex']);
	}

	protected function registerSocialRoutes($prefix, $router) {
		if (!$this->config('social.enabled')) return;
		$router->get("social/login/{network}", ['uses' => 'SocialController@getIndex', 'as' => $prefix . 'social']);
		$router->get("social/do", ['uses' => 'SocialController@getDo', 'as' => $prefix . 'social.login']);
	}

	protected function config($key, $default = null) {
		return $this->app['config']->get("authentify::{$key}", $default);
	}

	protected function routePrefix($delimiter = '') {
		return $this->config('routes.prefix') ? $this->config('routes.prefix') . $delimiter : '';
	}

	public function provides() {
		return array(
			'authentify.users-table', 'authentify.social_profiles-table',
			'authentify.check', 'authentify.guest',
			'authentify.hybridauth'
		);
	}
}
