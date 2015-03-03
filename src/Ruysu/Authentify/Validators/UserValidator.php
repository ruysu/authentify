<?php namespace Ruysu\Authentify\Validators;

use anlutro\LaravelValidation\Validator;

abstract class UserValidator extends Validator {
	protected function getCommonRules() {
		$identifier = $this->getIdentifierKey();

		return array(
			$identifier => $this->identifierRule(),
			'name' => ['required'],
		);
	}

	public function getCreateRules() {
		return $this->getSignUpRules();
	}

	public function getUpdateRules() {
		return [
			'password' => $this->passwordRule(false)
		];
	}

	public function getSignInRules() {
		$this->merge = false;
		$identifier = $this->getIdentifierKey();

		return [
			$identifier => $this->identifierRule(true, false),
			'password' => $this->passwordRule(true, false),
		];
	}

	public function getSocialSignUpRules() {
		return [
			'picture' => 'url',
			'password' => $this->passwordRule(true, false),
		];
	}

	public function getSignUpRules() {
		return [
			'password' => $this->passwordRule()
		];
	}

	public function getActivateRules() {
		$this->merge = false;

		return [
			'active' => 'required|boolean'
		];
	}

	public function getEditRules() {
		$this->merge = false;

		return [
			'picture' => 'image'
		];
	}

	public function getUpdatePasswordRules() {
		$this->merge = false;

		return [
			'current_password' => $this->passwordRule(true, false),
			'password' => $this->passwordRule()
		];
	}

	public function getChangePasswordRules() {
		$this->merge = false;

		return [
			'password' => $this->passwordRule(true, false)
		];
	}

	public function getRemindRules() {
		$this->merge = false;
		$identifier = $this->getIdentifierKey();

		return [
			$identifier => $this->identifierRule(true, false),
		];
	}

	public function getResetRules() {
		$this->merge = false;

		return [
			'token' => 'required',
			'password' => $this->passwordRule()
		];
	}

	public function rules($action, $merge = null) {
		return $this->getRules($action, $merge);
	}

	protected function identifierRule($required = true, $unique = true) {
		$rules = $this->validateIdentifier();

		$required && $rules []= 'required';
		$unique && $rules []= $this->unique('email');

		return $rules;
	}

	protected function validateIdentifier() {
		return ['email'];
	}

	protected function getIdentifierKey() {
		return 'email';
	}

	protected function passwordRule($required = true, $confirmed = true) {
		$rules = $this->validatePassword();

		$required && $rules []= 'required';
		$confirmed && $rules []= 'confirmed';

		return $rules;
	}

	protected function validatePassword() {
		return ['min:6'];
	}

	protected function unique($column, $softDelete = false) {
		return 'unique:<table>,' . $column . ',<key>' . ($softDelete ? ',id,deleted_at,NULL' : '');
	}
}