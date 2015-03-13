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
use Ruysu\Authentify\Repositories\AuthTokenDatabaseRepository;

class TokenCheckFilter
{
	protected $auth;
	protected $users;
	protected $tokens;

	public function __construct(UserRepositoryInterface $users, AuthTokenDatabaseRepository $tokens, Container $app)
	{
		$this->app = $app;
		$this->tokens = $tokens;
		$this->users = $users;
	}

	public function filter($route, $request)
	{
		$payload = $request->header('X-Auth-Token');

		if(empty($payload)) {
			$payload = $request->input('auth_token');
		}

		if(!($user = $this->tokens->attempt($payload))) {
			return Response::make('Unauthorized', 401);
		}

		$this->app['auth']->setUser($user);
	}
}