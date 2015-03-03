<?php namespace Ruysu\Authentify\Mailers;

class SendWelcome {
	public function fire($job, $data) {
		extract($data);

		$id = $user['id'];
		$class = '\\' . \Config::get('auth.model');
		$user = new $class($user);
		$user->id = $id;
		$password = isset($password) ? $password : null;

		\Mail::send('authentify::emails.welcome', compact('password', 'user'), function($message) use ($user, $password) {
			$message
				->to($user->email, $user->name)
				->subject(\Lang::get('authentify::emails.welcome.subject'));
		});

		$job->delete();
	}
}