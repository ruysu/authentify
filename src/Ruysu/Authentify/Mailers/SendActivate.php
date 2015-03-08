<?php namespace Ruysu\Authentify\Mailers;

use Config;
use Mail;

class SendActivate
{
	public function fire($job, $data)
	{
		extract($data);

		$id = $user['id'];
		$class = '\\' . Config::get('auth.model');
		$user = new $class($user);
		$user->id = $id;
		$password = isset($password) ? $password : null;

		Mail::send('authentify::emails.activate', compact('password', 'token', 'user'), function($message) use ($user, $password)
		{
			$message
				->to($user->email, $user->name)
				->subject(trans('authentify::emails.activate.subject'));
		});

		$job->delete();
	}
}