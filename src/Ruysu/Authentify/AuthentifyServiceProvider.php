<?php
/**
 * Laravel 4 Authentication with an abstraction layer and 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify;

use Illuminate\Support\ServiceProvider;
use Ruysu\Authentify\Commands\UsersTable;
use Ruysu\Authentify\Commands\SocialProfilesTable;
use Ruysu\Authentify\Commands\TokensTable;
use Hybrid_Auth;
use Response;

class AuthentifyServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Run on application boot.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ruysu/authentify');

		$this->app['router']->before(function()
		{
			$this->app['view']->composer('user', $this->app['auth']->user());
		});

		$this->registerEvents();
		$this->registerFilters();
		$this->registerRoutes();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerCommands();
		$this->registerHybridAuth();
	}

	/**
	 * Register the package commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$this->app->bind('authentify.users-table', function()
		{
			return new UsersTable;
		});

		$this->app->bind('authentify.social_profiles-table', function ()
		{
			return new SocialProfilesTable;
		});

		$this->app->bind('authentify.tokens-table', function ()
		{
			return new TokensTable;
		});

		$this->commands('authentify.users-table', 'authentify.tokens-table', 'authentify.social_profiles-table');
	}

	/**
	 * Bind Hybrid Auth to the IoC Container.
	 *
	 * @return void
	 */
	protected function registerHybridAuth()
	{
		$this->app->bind('authentify.hybridauth', function($app, $url)
		{
			return new Hybrid_Auth(array(
				'base_url' => $url,
				'providers' => $app['config']->get('authentify::social.hybridauth')
			));         
		});
	}

	/**
	 * Handle events.
	 *
	 * @return void
	 */
	protected function registerEvents()
	{
		$this->app['events']->listen('auth.login', 'Ruysu\Authentify\Events\SignInHandler');
	}

	/**
	 * Register route filters.
	 *
	 * @return void
	 */
	protected function registerFilters()
	{
		$app = $this->app;

		$this->app['router']->filter('authentify.check', function() use ($app)
		{
			if ($app['auth']->guest()) {
				if ($app['request']->ajax()) {
					return Response::make('Unauthorized', 401);
				}
				else {
					return $app['redirect']->guest($app['url']->action('Authentify\SignInController@getIndex'));
				}
			}
		});

		$this->app['router']->filter('authentify.guest', function() use ($app)
		{
			if ($app['auth']->check()) {
				return $app['redirect']->to('/');
			}
		});
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	protected function registerRoutes()
	{
		// No need to register anything if routes are set to configure manually
		if (!$this->config('routes.auto')) {
			return;
		}

		$router = $this->app['router'];
		$prefix = $this->routePrefix();
		$name_prefix = $this->routePrefix('.');

		$router->group(['namespace' => 'Authentify', 'prefix' => $prefix], function () use ($router, $name_prefix)
		{
			$router->group(['before' => 'authentify.guest'], function() use ($router, $name_prefix)
			{
				$this->registerSignInRoutes($name_prefix, $router);
				$this->registerSignUpRoutes($name_prefix, $router);
				$this->registerRemindRoutes($name_prefix, $router);
				$this->registerSocialRoutes($name_prefix, $router);
			});

			$router->group(['before' => 'authentify.check'], function() use ($router, $name_prefix)
			{
				$this->registerSignOutRoutes($name_prefix, $router);
				$this->registerEditRoutes($name_prefix, $router);
				$this->registerPasswordRoutes($name_prefix, $router);
			});
		});
	}

	/**
	 * Register the sign in routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerSignInRoutes($prefix, $router)
	{
		$router->get("sign-in", ['uses' => 'SignInController@getIndex', 'as' => $prefix . 'sign-in']);
		$router->post("sign-in", ['uses' => 'SignInController@postIndex']);
	}

	/**
	 * Register the sign out routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerSignOutRoutes($prefix, $router)
	{
		$router->delete("sign-out", ['uses' => 'SignOutController@anyIndex', 'as' => $prefix . 'sign-out']);
		$router->get("sign-out", ['uses' => 'SignOutController@anyIndex']);
	}

	/**
	 * Register the sign up routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerSignUpRoutes($prefix, $router)
	{
		if (!$this->config('registerable')) {
			return;
		}

		$router->get("sign-up", ['uses' => 'SignUpController@getIndex', 'as' => $prefix . 'sign-up']);
		$router->post("sign-up", ['uses' => 'SignUpController@postIndex']);
		$router->get("activate/{token}", ['uses' => 'SignUpController@getActivate', 'as' => $prefix . 'activate']);
	}

	/**
	 * Register the remind and reset password routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerRemindRoutes($prefix, $router)
	{
		if (!$this->config('remindable')) {
			return;
		}

		$router->get("remind", ['uses' => 'RemindController@getIndex', 'as' => $prefix . 'remind']);
		$router->post("remind", ['uses' => 'RemindController@postIndex']);
		$router->get("reset/{token}", ['uses' => 'RemindController@getReset', 'as' => $prefix . 'reset']);
		$router->post("reset", ['uses' => 'RemindController@postReset']);
	}

	/**
	 * Register the edit account information routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerEditRoutes($prefix, $router)
	{
		if (!$this->config('editable.info')) {
			return;
		}

		$router->get("edit", ['uses' => 'EditController@getIndex', 'as' => $prefix . 'edit']);
		$router->post("edit", ['uses' => 'EditController@postIndex']);
	}

	/**
	 * Register the edit password routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerPasswordRoutes($prefix, $router)
	{
		if (!$this->config('editable.password')) {
			return;
		}

		$router->get("password", ['uses' => 'PasswordController@getIndex', 'as' => $prefix . 'password']);
		$router->post("password", ['uses' => 'PasswordController@postIndex']);
	}

	/**
	 * Register the social sign up / sign in routes.
	 *
	 * @param  string  $prefix
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function registerSocialRoutes($prefix, $router)
	{
		if (!$this->config('social.enabled')) {
			return;
		}

		$router->get("social/login/{network}", ['uses' => 'SocialController@getIndex', 'as' => $prefix . 'social']);
		$router->get("social/do", ['uses' => 'SocialController@getDo', 'as' => $prefix . 'social.login']);
	}

	/**
	 * Get a package config value
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	protected function config($key, $default = null)
	{
		return $this->app['config']->get("authentify::{$key}", $default);
	}

	/**
	 * Get a package config value
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	protected function routePrefix($delimiter = '')
	{
		return $this->config('routes.prefix') ? $this->config('routes.prefix') . $delimiter : '';
	}

	/**
	 * Return an array of the bindings provided by this package.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
			'authentify.users-table', 'authentify.social_profiles-table',
			'authentify.check', 'authentify.guest',
			'authentify.hybridauth'
		);
	}
}
