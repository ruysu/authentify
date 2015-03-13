<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Repositories;

use anlutro\LaravelRepository\Criteria\SimpleCriteria;

interface OwnedRepositoryInterface {

	/**
	 * Set the owner of the repository.
	 *
	 * @param  object  $user
	 * @return void
	 */
	public function setUser($user);

	/**
	 * Get all entries by a given user.
	 *
	 * @param  object|null  $user
	 * @return Illuminate\Support\Collection
	 */
	public function getForUser($user = null);

	/**
	 * Get the owner of a given entry.
	 *
	 * @param  object  $entity
	 * @return object|null
	 */
	public function getUser($entity);

}