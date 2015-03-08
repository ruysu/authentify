<?php namespace Authentify;

use Session;

class SignOutController extends AuthentifyController
{
	public function anyIndex()
	{
		$this->auth->logout();

		$redirect = $this->input('redirect', '/');
		Session::forget('url.intended');

		if (!is_string($redirect)) {
			$redirect = '/';
		}

		return $this->redirect($redirect);
	}
}