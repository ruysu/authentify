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

trait OwnedRepositoryTrait {

	protected $user;
	protected $users;

	/**
	 * Set the owner of the repository.
	 *
	 * @param  object  $user
	 * @return void
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

	protected function beforeQuery($query, $many)
	{
		if (isset($this->user) && $this->user) {
			with($criteria = new SimpleCriteria)->where('user_id', $this->users->getEntityKey($this->user));
			$this->pushCriteria($criteria);
		}
	}

	protected function beforeCreate($entity, array $attributes)
	{
		$this->user && $entity->user_id = $attributes['user_id'] = $this->users->getEntityKey($this->user);
	}

	/**
	 * Get all entries by a given user.
	 *
	 * @param  object|null  $user
	 * @return Illuminate\Support\Collection
	 */
	public function getForUser($user = null)
	{
		if ($user) {
			$this->setUser($user);
		}

		$query = $this->newQuery();

		return $this->fetchMany($query);
	}

	/**
	 * Get the owner of a given entry.
	 *
	 * @param  object  $entity
	 * @return object|null
	 */
	public function getUser($entity)
	{
		return $this->users->findByKey($entity->user_id);
	}

}