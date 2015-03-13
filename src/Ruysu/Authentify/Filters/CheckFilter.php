<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Filters;

use Response;
use Illuminate\Container\Container;
use Ruysu\Authentify\Repositories\UserRepositoryInterface;

class CheckFilter
{
	protected $auth;
	protected $users;

	public function __construct(UserRepositoryInterface $users, Container $app)
	{
		$this->app = $app;
		$this->users = $users;
	}

	public function filter($route, $request)
	{
		if ($this->app['auth']->guest()) {
			if ($request->ajax()) {
				return Response::make('Unauthorized', 401);
			}
			else {
				return $this->app['redirect']->guest($this->app['url']->action('Authentify\SignInController@getIndex'));
			}
		}
	}
}