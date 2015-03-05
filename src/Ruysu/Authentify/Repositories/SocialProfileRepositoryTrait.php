<?php namespace Ruysu\Authentify\Repositories;

use anlutro\LaravelRepository\Criteria\SimpleCriteria;

trait SocialProfileRepositoryTrait {
	protected $user;
	protected $users;

	public function setUser($user) {
		$this->user = $user;
	}

	protected function beforeQuery($query, $many) {
		if (isset($this->user)) {
			with($criteria = new SimpleCriteria)->where('user_id', $this->users->getEntityKey($this->user));
			$this->pushCriteria($criteria);
		}
	}

	public function beforeCreate($entity, array $attributes) {
		$this->user && $entity->user_id = $attributes['user_id'] = $this->users->getEntityKey($this->user);
	}

	public function getForUser($user = null) {
		with($criteria = new SimpleCriteria)->where('user_id', $this->users->getEntityKey($this->user ?: $user));
		$this->pushCriteria($criteria);
		$query = $this->newQuery();
		return $this->fetchMany($query);
	}

	public function getUser($entity) {
		return $this->users->findByKey($entity->user_id);
	}
}