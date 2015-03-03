<?php namespace Authentify;

use Config;
use Redirect;
use URL;
use anlutro\LaravelController\Controller;
use Illuminate\Auth\AuthManager;
use Ruysu\Authentify\Repositories\UserRepositoryInterface;

abstract class AuthentifyController extends Controller {
	protected $auth;
	protected $config;
	protected $users;

	public function __construct(UserRepositoryInterface $users, AuthManager $auth) {
		$this->users = $users;
		$this->auth = $auth;
		$class = get_class($this);

		if (in_array($class, ['EditController', 'PasswordController', 'SignOutController'])) {
			$this->beforeFilter('authentify.check');
		}
		else {
			$this->beforeFilter('authentify.guest');
		}
	}

	protected function valid($action, array $attributes, $merge = true) {
		return $this->users->getValidator()->valid($action, $attributes, $merge);
	}

	protected function errors() {
		return $this->users->getValidator()->getErrors();
	}

	protected function inputFor($action, $merge = true) {
		$rules = $this->users->getValidator()->rules($action, $merge);
		$fields = array_keys($rules);

		$confirmed_fields = array_where($rules, function($key, $rule) {
			if(is_array($rule)) {
				return in_array('confirmed', $rule);
			}
			else {
				return $rule !== false;
			}
		});

		foreach (array_keys($confirmed_fields) as $key) {
			$fields []= $key . '_confirmation';
		}

		return array_only($this->input(), $fields);
	}

	protected function config($key, $default = null) {
		return Config::get("authentify::{$key}", $default);
	}

	protected function url($action, $params = array()) {
		if ($action == '/' || (preg_match('/^\/?[\w]+(\/?[\w]+)*\/?(\.[\w]+)?$/', $action) && strpos($action, '/'))) {
			$url = URL::to($action);
		}
		elseif (starts_with($action, 'https://') || starts_with($action, 'http://')) {
			$url = $action;
		}
		else {
			$url = parent::url($action, $params);
		}

		return $url;
	}

	protected function intended($action = '/', array $params = array()) {
		return Redirect::intended($this->url($action, $params));
	}
}
