<?php namespace Ruysu\Authentify;

use Illuminate\Support\ServiceProvider;
use Ruysu\Authentify\Commands\UsersTable;
use Ruysu\Authentify\Commands\SocialProfilesTable;
use Hybrid_Auth;
use Response;

class AuthentifyServiceProvider extends ServiceProvider {
	protected $defer = false;

	public function boot() {
		$this->package('ideaworksla/authentify', null, __DIR__ . '/../../../resources');
		$this->app['view']->share('user', $this->app['auth']->user());
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

		$this->registerSignInRoutes();
		$this->registerSignOutRoutes();
		$this->registerSignUpRoutes();
		$this->registerRemindRoutes();
		$this->registerEditRoutes();
		$this->registerPasswordRoutes();
	}

	protected function registerSignInRoutes() {
		$prefix = $this->routePrefix();
		$this->app['router']->get("{$prefix}sign-in", ['uses' => 'Authentify\SignInController@getIndex', 'as' => $this->routePrefix('.') . 'sign-in']);
		$this->app['router']->post("{$prefix}sign-in", ['uses' => 'Authentify\SignInController@postIndex']);
	}

	protected function registerSignOutRoutes() {
		$prefix = $this->routePrefix();
		$this->app['router']->delete("{$prefix}sign-out", ['uses' => 'Authentify\SignOutController@anyIndex', 'as' => $this->routePrefix('.') . 'sign-out']);
		$this->app['router']->get("{$prefix}sign-out", ['uses' => 'Authentify\SignOutController@anyIndex']);
	}

	protected function registerSignUpRoutes() {
		if (!$this->config('registerable')) return;
		$prefix = $this->routePrefix();
		$this->app['router']->get("{$prefix}sign-up", ['uses' => 'Authentify\SignUpController@getIndex', 'as' => $this->routePrefix('.') . 'sign-up']);
		$this->app['router']->post("{$prefix}sign-up", ['uses' => 'Authentify\SignUpController@postIndex']);
	}

	protected function registerRemindRoutes() {
		if (!$this->config('remindable')) return;
		$prefix = $this->routePrefix();
		$this->app['router']->get("{$prefix}remind", ['uses' => 'Authentify\RemindController@getIndex', 'as' => $this->routePrefix('.') . 'remind']);
		$this->app['router']->post("{$prefix}remind", ['uses' => 'Authentify\RemindController@postIndex']);
		$this->app['router']->get("{$prefix}reset/{token}", ['uses' => 'Authentify\RemindController@getReset', 'as' => $this->routePrefix('.') . 'reset']);
		$this->app['router']->post("{$prefix}reset", ['uses' => 'Authentify\RemindController@postReset']);
	}

	protected function registerEditRoutes() {
		if (!$this->config('editable.info')) return;
		$prefix = $this->routePrefix();
		$this->app['router']->get("{$prefix}edit", ['uses' => 'Authentify\EditController@getIndex', 'as' => $this->routePrefix('.') . 'edit']);
		$this->app['router']->post("{$prefix}edit", ['uses' => 'Authentify\EditController@postIndex']);
	}

	protected function registerPasswordRoutes() {
		if (!$this->config('editable.password')) return;
		$prefix = $this->routePrefix();
		$this->app['router']->get("{$prefix}password", ['uses' => 'Authentify\PasswordController@getIndex', 'as' => $this->routePrefix('.') . 'password']);
		$this->app['router']->post("{$prefix}password", ['uses' => 'Authentify\PasswordController@postIndex']);
	}

	protected function config($key, $default = null) {
		return $this->app['config']->get("authentify::{$key}", $default);
	}

	protected function routePrefix($delimiter = '/') {
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
