<?php namespace Ruysu\Authentify\Repositories;

interface SocialProfileRepositoryInterface {

	public function setUser($user);

	public function getForUser($user);

	public function getUser($entity, UserRepositoryInterface $users);

}