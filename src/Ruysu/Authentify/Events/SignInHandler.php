<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Events;

use Ruysu\Authentify\Repositories\UserRepositoryInterface;
use DateTime;

class SignInHandler
{
	protected $users;

	public function __construct(UserRepositoryInterface $users)
	{
		$this->users = $users;
	}

	public function handle ($user)
	{
		$user->last_login_at = $user->login_at instanceof DateTime ? $user->login_at->format('Y-m-d H:i:s') : $user->login_at;
		$user->login_at = with(new DateTime)->format('Y-m-d H:i:s');
		$this->users->update($user, $user->toArray());
	}
}