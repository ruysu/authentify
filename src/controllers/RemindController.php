<?php namespace Authentify;

use Config;
use DB;
use Password;

class RemindController extends AuthentifyController
{
	public function getIndex()
	{
		$remind_action = $this->action('postIndex');
		return $this->view('authentify::auth.remind', compact('remind_action'));
	}

	public function postIndex()
	{
		$input = $this->inputFor('remind');

		if ($this->valid('remind', $input, false)) {
			Config::set('auth.reminder.email', 'authentify::emails.reminder');

			$response = Password::remind($input, function ($message)
			{
				$message->subject('Password recovery');
			});

			switch($response) {
				case Password::INVALID_USER :
					return $this->redirect('getIndex')
						->with('authentify.notice', array('warning', trans('authentify::messages.remind.error')));
				break;
				case Password::REMINDER_SENT :
					return $this->redirect('Authentify\SignInController@getIndex')
						->with('authentify.notice', array('success', trans('authentify::messages.remind.success')));
				break;
			}
		}
		else {
			return $this->redirect('getIndex')
				->withErrors($validator);
		}
	}

	public function getReset($token)
	{
		$reset_action = $this->action('postReset');
		return $this->view('authentify::auth.reset', compact('token', 'reset_action'));
	}

	public function postReset()
	{
		$input = $this->inputFor('reset');

		if ($this->valid('reset', $input, false)) {
			$email = DB::table('password_reminders')->where('token', $input['token'])->first();
			$input['email'] = $email ? $email->email : '';
			$users = $this->users;
			$response = Password::reset($input, function ($user, $password) use ($users)
			{
				$users->changePassword($user, compact('password'));
			});

			switch($response) {
				case Password::INVALID_PASSWORD :
				case Password::INVALID_TOKEN :
				case Password::INVALID_USER :
					return $this->redirect('getReset', $input['token'])
						->with('authentify.notice', array('warning', trans('authentify::messages.reset.error')))
						->withErrors($this->errors())
						->withInput();
				break;
				case Password::PASSWORD_RESET :
					return $this->redirect('Authentify\SignInController@getIndex')
						->with('authentify.notice', array('success', trans('authentify::messages.reset.success')));
				break;
			}
		}
		else {
			return $this->redirect('getReset', $input['token'])
				->withErrors($this->errors())
				->withInput();
		}
	}
}
