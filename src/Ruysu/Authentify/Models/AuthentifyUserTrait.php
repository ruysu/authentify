<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Models;

trait AuthentifyUserTrait {

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = \Hash::make($password);
	}

}