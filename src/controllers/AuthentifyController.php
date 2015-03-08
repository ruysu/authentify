<?php namespace Authentify;

use Config;
use Redirect;
use anlutro\LaravelController\Controller;
use Illuminate\Auth\AuthManager;
use Ruysu\Authentify\Repositories\UserRepositoryInterface;
use Ruysu\Authentify\Controllers\AuthentifyControllerTrait;

abstract class AuthentifyController extends Controller
{
	use AuthentifyControllerTrait;

	protected $auth;
	protected $config;
	protected $users;

	public function __construct(UserRepositoryInterface $users, AuthManager $auth)
	{
		$this->users = $users;
		$this->auth = $auth;
	}

	protected function url($action, $params = array())
	{
		if ($action == '/' || (preg_match('/^\/?[\w]+(\/?[\w]+)*\/?(\.[\w]+)?$/', $action) && strpos($action, '/'))) {
			$url = url($action);
		}
		elseif (starts_with($action, 'https://') || starts_with($action, 'http://')) {
			$url = $action;
		}
		else {
			$url = parent::url($action, $params);
		}

		return $url;
	}

	protected function intended($action = '/', array $params = array())
	{
		return Redirect::intended($this->url($action, $params));
	}
}
