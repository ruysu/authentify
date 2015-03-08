<?php namespace Ruysu\Authentify\Repositories;

use anlutro\LaravelRepository\Criteria\SimpleCriteria;

interface OwnedRepositoryInterface {

	public function setUser($user);

	public function getForUser($user = null);

	public function getUser($entity);

}