<?php namespace Ruysu\Authentify\Repositories;

trait UserRepositoryTrait {

	public function signUp(array $attributes) {
		return $this->perform('signUp', $this->getNew(), $attributes);
	}

	protected function performSignUp($entity, array $attributes) {
		return $this->perform('create', $entity, $attributes, false);
	}

	public function socialSignUp(array $attributes) {
		return $this->perform('socialSignUp', $this->getNew(), $attributes);
	}

	protected function performSocialSignUp($entity, array $attributes) {
		return $this->perform('create', $entity, $attributes, false);
	}

	public function activate($entity) {
		return $this->perform('activate', $entity, ['active' => 1], false);
	}

	protected function performActivate($entity, array $attributes) {
		return $this->perform('update', $entity, $attributes, false);
	}

	public function edit($entity, array $attributes) {
		$this->validator->replace('key', $this->getEntityKey($entity));
		return $this->perform('edit', $entity, $attributes);
	}

	protected function performEdit($entity, array $attributes) {
		return $this->perform('update', $entity, $attributes, false);
	}

	public function updatePassword($entity, array $attributes) {
		return $this->perform('updatePassword', $entity, $attributes);
	}

	protected function performUpdatePassword($entity, array $attributes) {
		return $this->perform('update', $entity, $attributes, false);
	}

	public function changePassword($entity, array $attributes) {
		return $this->perform('changePassword', $entity, $attributes);
	}

	protected function performChangePassword($entity, array $attributes) {
		return $this->perform('update', $entity, $attributes, false);
	}

}