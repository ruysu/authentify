<?php namespace Authentify;

use App;
use Crypt;
use Queue;

class SignUpController extends AuthentifyController {
	public function getIndex() {
		$sign_up_action = $this->action('postIndex');
		return $this->view('authentify::auth.sign-up', compact('sign_up_action'));
	}

	public function postIndex() {
		$input = $this->inputFor('signUp');
		$input['active'] = !$this->config('confirmable');

		if ($user = $this->users->signUp($input)) {
			$password = $this->input('password');

			if ($input['active']) {
				$this->config('welcomable') && Queue::push('Ruysu\Authentify\Mailers\SendWelcome', compact('password', 'user'));
				$this->login($user);

				return $this->intended('/');
			}
			else {
				$token = Crypt::encrypt($user->id);
				Queue::push('Ruysu\Authentify\Mailers\SendActivate', compact('password', 'token', 'user'));

				return $this->redirect('Authentify\SignInController@getIndex')
					->with('authentify.notice', array('warning', trans('authentify::messages.sign-up.success')));
			}
		}
		else {
			return $this->redirect('getIndex')
				->withErrors($this->errors())
				->withInput();
		} 
	}

	public function getActivate($token) {
		$id = Crypt::decrypt($token);
		$user = $this->users->findByKey($id);

		if (!($user && !$user->active)) {
			App::abort(404);
		}

		$this->users->activate($user);
		$this->config('welcomable') && Queue::push('Ruysu\Authentify\Mailers\SendWelcome', compact('password', 'user'));

		return $this->redirect('Authentify\SignInController@getIndex')
			->with('authentify.notice', array('success', trans('authentify::messages.activate.success')));
	}
}
