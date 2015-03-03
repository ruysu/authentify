<?php namespace Ruysu\Authentify\Repositories;

trait SocialProfileRepositoryTrait {
	protected $user;

	public function setUser($user) {
		$this->user = $user;
	}

	protected function beforeQuery($query, $many) {
		if (isset($this->user)) {
			$query->where('user_id', '=', $this->user->id);
		}
	}

	public function beforeCreate($entity, array $attributes) {
		$this->user && $entity->user_id = $attributes['user_id'] = $this->user->id;
	}

	public function getForUser($user) {
		$query = $this->newQuery();
		$query->where('user_id', '=', $user->id);
		return $this->fetchMany($query);
	}

	public function getUser($entity, UserRepositoryInterface $users) {
		return $users->findByKey($entity->user_id);
	}
}