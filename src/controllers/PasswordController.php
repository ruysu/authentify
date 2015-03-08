<?php namespace Authentify;

use Hash;

class PasswordController extends AuthentifyController
{
	public function getIndex()
	{
		$password_action = $this->action('postIndex');
		return $this->view('authentify::auth.password', compact('password_action'));
	}

	public function postIndex()
	{
		$user = $this->auth->user();
		$input = $this->inputFor('updatePassword', false);

		if ($this->valid('updatePassword', $input, false)) {
			if (Hash::check($input['current_password'], $user->password) && $this->users->updatePassword($user, $input)) {
				return $this->redirect('getIndex')
					->with('authentify.notice', array('success', trans('authentify::messages.password.success')));
			} 
			else {
				return $this->redirect('getIndex')
					->withErrors($this->errors())
					->with('authentify.notice', array('danger', trans('authentify::messages.password.error')));
			}
		} 
		else {
			return $this->redirect('getIndex')
				->withErrors($this->errors());
		}
	}
}
