<?php namespace Ruysu\Authentify\Models;

trait AuthentifyUserTrait {

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = \Hash::make($password);
	}

}