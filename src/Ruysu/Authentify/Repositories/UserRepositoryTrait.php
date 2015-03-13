<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Repositories;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UserRepositoryTrait
{

	/**
	 * Sign up a user with the given attributes.
	 *
	 * @param  array  $attributes
	 * @return object|false
	 */
	public function signUp(array $attributes)
	{
		return $this->perform('signUp', $this->getNew(), $attributes);
	}

	/**
	 * Perform the sign up action.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return object|false
	 */
	protected function performSignUp($entity, array $attributes)
	{
		$entity->active = (bool) array_get($attributes, 'active');

		return $this->perform('create', $entity, $attributes, false);
	}

	/**
	 * Sign up a user through a social profile.
	 *
	 * @param  array  $attributes
	 * @return object|false
	 */
	public function socialSignUp(array $attributes)
	{
		return $this->perform('socialSignUp', $this->getNew(), $attributes);
	}

	/**
	 * Perform the social sign up action.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return object|false
	 */
	protected function performSocialSignUp($entity, array $attributes)
	{
		$entity->active = 1;

		return $this->perform('create', $entity, $attributes, false);
	}

	/**
	 * Activate a user account.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function activate($entity)
	{
		return $this->perform('activate', $entity, ['active' => 1], false);
	}

	/**
	 * Perform the activate action.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	protected function performActivate($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	/**
	 * Edit a user account.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function edit($entity, array $attributes)
	{
		$this->validator->replace('key', $this->getEntityKey($entity));
		return $this->perform('edit', $entity, $attributes);
	}

	/**
	 * Perform the edit action.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	protected function performEdit($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	/**
	 * Update a user password.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function updatePassword($entity, array $attributes)
	{
		return $this->perform('updatePassword', $entity, $attributes);
	}

	/**
	 * Perform the update password action.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	protected function performUpdatePassword($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	/**
	 * Change the password for the given user.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	public function changePassword($entity, array $attributes)
	{
		return $this->perform('changePassword', $entity, $attributes);
	}

	/**
	 * Perform the change password action.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	protected function performChangePassword($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	/**
	 * Perform the update action, but first, handle file attributes.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return boolean
	 */
	protected function performUpdate($user, array $attributes)
	{
		if (isset($attributes['password']) && empty($attributes['password'])) {
			unset($attributes['password']);
		}

		$this->uploadFiles($attributes);

		return parent::performUpdate($user, $attributes);
	}

	/**
	 * Perform the create action, but first, handle file attributes.
	 *
	 * @param  object  $entity
	 * @param  array  $attributes
	 * @return object|false
	 */
	protected function performCreate($user, array $attributes)
	{
		$this->uploadFiles($attributes);

		return parent::performCreate($user, $attributes);
	}

	/**
	 * Handle file attributes.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	protected function uploadFiles(&$attributes)
	{
		$files = array_filter($attributes, function($file)
		{
			return $file instanceof UploadedFile || is_null($file);
		});

		foreach ($files as $key => $file) {
			if ($file && $file->isValid()) {
				$method = camel_case("upload_{$key}_file");

				if (method_exists($this, $method)) {
					$upload = $this->$method($file);
					$attributes[$key] = $upload;
				}
				else {
					$path = public_path('uploads/users');
					!is_dir($path) && mkdir($path, 0755, true);
					$file->move($path, $file->getClientOriginalName());
					$attributes[$key] = asset('uploads/users/' . $file->getClientOriginalName());
				}
			}
		}
		unset($key, $file);
	}

}