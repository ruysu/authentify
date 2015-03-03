<?php namespace Ruysu\Authentify\Repositories;

interface UserRepositoryInterface {

	public function signUp(array $attributes);

	public function socialSignUp(array $attributes);

	public function activate($entity);

	public function edit($entity, array $attributes);

	public function updatePassword($entity, array $attributes);

	public function changePassword($entity, array $attributes);

}