<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Repositories;

interface UserRepositoryInterface {

	/**
	 * Sign up a user with the given attributes.
	 *
	 * @param  array  $attributes
	 * @return object|false
	 */
	public function signUp(array $attributes);

	/**
	 * Sign up a user through a social profile.
	 *
	 * @param  array  $attributes
	 * @return object|false
	 */
	public function socialSignUp(array $attributes);

	/**
	 * Activate a user account.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function activate($entity);

	/**
	 * Edit a user account.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function edit($entity, array $attributes);

	/**
	 * Update a user password.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function updatePassword($entity, array $attributes);

	/**
	 * Change the password for the given user.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function changePassword($entity, array $attributes);

}