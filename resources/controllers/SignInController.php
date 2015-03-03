<?php namespace Authentify;

class SignInController extends AuthentifyController {
	public function getIndex() {
		$sign_in_action = $this->action('postIndex');
		return $this->view('authentify::auth.sign-in', compact('sign_in_action'));
	}

	public function postIndex() {
		$credentials = $this->inputFor('signIn');

		if ($this->valid('signIn', $credentials, false)) {
			$remember = $this->input('remember') && $this->config('rememberable');

			if ($this->auth->attempt($credentials, $remember)) {
				return $this->intended('/');
			} 
			else {
				return $this->redirect('getIndex')
					->with('authentify.notice', array('danger', trans('authentify::messages.sign-in.error')))
					->withInput();
			}
		}
		else {
			return $this->redirect('getIndex')
				->withErrors($this->errors())
				->withInput();
		}
	}
}