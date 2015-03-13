<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Mailers;

use Config;
use Mail;

class SendWelcome
{
	public function fire($job, $data)
	{
		extract($data);

		$id = $user['id'];
		$class = '\\' . Config::get('auth.model');
		$user = new $class($user);
		$user->id = $id;
		$password = isset($password) ? $password : null;

		Mail::send('authentify::emails.welcome', compact('password', 'user'), function($message) use ($user, $password)
		{
			$message
				->to($user->email, $user->name)
				->subject(trans('authentify::emails.welcome.subject'));
		});

		$job->delete();
	}
}