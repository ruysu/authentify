<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Validators;

use anlutro\LaravelValidation\Validator;

abstract class UserValidator extends Validator
{
	/**
	 * Rules used in every validation call.
	 *
	 * @return array
	 */
	protected function getCommonRules()
	{
		$identifier = $this->getIdentifierKey();

		return array(
			$identifier => $this->identifierRule(),
			'name' => ['required'],
		);
	}

	/**
	 * Rules used on creation validation call.
	 *
	 * @return array
	 */
	public function getCreateRules()
	{
		return $this->getSignUpRules();
	}

	/**
	 * Rules used on update validation call.
	 *
	 * @return array
	 */
	public function getUpdateRules()
	{
		return [
			'password' => $this->passwordRule(false)
		];
	}

	/**
	 * Rules used on sign in validation call.
	 *
	 * @return array
	 */
	public function getSignInRules()
	{
		$this->merge = false;
		$identifier = $this->getIdentifierKey();

		return [
			$identifier => $this->identifierRule(true, false),
			'password' => $this->passwordRule(true, false),
		];
	}

	/**
	 * Rules used on social sign up validation call.
	 *
	 * @return array
	 */
	public function getSocialSignUpRules()
	{
		return [
			'picture' => 'url',
			'password' => $this->passwordRule(true, false),
		];
	}

	/**
	 * Rules used on sign up validation call.
	 *
	 * @return array
	 */
	public function getSignUpRules()
	{
		return [
			'password' => $this->passwordRule()
		];
	}

	/**
	 * Rules used on activation validation call.
	 *
	 * @return array
	 */
	public function getActivateRules()
	{
		$this->merge = false;

		return [
			'active' => 'required|boolean'
		];
	}

	/**
	 * Rules used on account edit validation call.
	 *
	 * @return array
	 */
	public function getEditRules()
	{
		return [
			'picture' => 'image'
		];
	}

	/**
	 * Rules used on password update validation call.
	 *
	 * @return array
	 */
	public function getUpdatePasswordRules()
	{
		$this->merge = false;

		return [
			'current_password' => $this->passwordRule(true, false),
			'password' => $this->passwordRule()
		];
	}

	/**
	 * Rules used on change password validation call.
	 *
	 * @return array
	 */
	public function getChangePasswordRules()
	{
		$this->merge = false;

		return [
			'password' => $this->passwordRule(true, false)
		];
	}

	/**
	 * Rules used on password remind validation call.
	 *
	 * @return array
	 */
	public function getRemindRules()
	{
		$this->merge = false;
		$identifier = $this->getIdentifierKey();

		return [
			$identifier => $this->identifierRule(true, false),
		];
	}

	/**
	 * Rules used on password reset validation call.
	 *
	 * @return array
	 */
	public function getResetRules()
	{
		$this->merge = false;

		return [
			'token' => 'required',
			'password' => $this->passwordRule()
		];
	}

	/**
	 * Return the validation rules for any given action.
	 *
	 * @param  string  $action
	 * @param  boolean|null  $merge
	 * @return array
	 */
	public function rules($action, $merge = null)
	{
		return $this->getRules($action, $merge);
	}

	/**
	 * Construct the validation rule for the identifier field.
	 *
	 * @param  boolean  $required
	 * @param  boolean|null  $merge
	 * @return array
	 */
	protected function identifierRule($required = true, $unique = true)
	{
		$rules = $this->validateIdentifier();

		$required && $rules []= 'required';
		$unique && $rules []= $this->unique('email');

		return $rules;
	}

	/**
	 * Get the validation rule for the identifier field, should not include required nor unique.
	 *
	 * @return array
	 */
	protected function validateIdentifier()
	{
		return ['email'];
	}

	/**
	 * Get the identifier field name.
	 *
	 * @return string
	 */
	protected function getIdentifierKey()
	{
		return 'email';
	}

	/**
	 * Construct the validation rule for the password field.
	 *
	 * @param  boolean  $required
	 * @param  boolean  $confirmed
	 * @return array
	 */
	protected function passwordRule($required = true, $confirmed = true)
	{
		$rules = $this->validatePassword();

		$required && $rules []= 'required';
		$confirmed && $rules []= 'confirmed';

		return $rules;
	}

	/**
	 * Get the validation rule for the password field, should not include, confirmed nor required.
	 *
	 * @return array
	 */
	protected function validatePassword()
	{
		return ['min:6'];
	}

	/**
	 * Construct a unique validation statement.
	 *
	 * @return string
	 */
	protected function unique($column, $softDelete = false)
	{
		return 'unique:<table>,' . $column . ',<key>' . ($softDelete ? ',id,deleted_at,NULL' : '');
	}
}