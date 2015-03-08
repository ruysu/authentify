<?php namespace Authentify;

class SignInController extends AuthentifyController
{
	public function getIndex()
	{
		$sign_in_action = $this->action('postIndex');
		return $this->view('authentify::auth.sign-in', compact('sign_in_action'));
	}

	public function postIndex()
	{
		$credentials = $this->inputFor('signIn');

		if ($this->valid('signIn', $credentials, false)) {
			$remember = $this->input('remember') && $this->config('rememberable');

			// We login the user just for the current request, in order to validate that the account is active
			if ($this->auth->once($credentials)) {
				$user = $this->auth->user();

				// User is active, so we can log it in
				if ($user->active) {
					$this->auth->login($user, $remember);

					return $this->intended('/');
				}
				else {
					return $this->redirect('getIndex')
						->with('authentify.notice', array('danger', trans('authentify::messages.sign-in.activate')))
						->withInput();
				}
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