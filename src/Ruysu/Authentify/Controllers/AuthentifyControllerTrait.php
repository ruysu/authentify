<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Controllers;

use Config;

trait AuthentifyControllerTrait {
	protected function valid($action, array $attributes, $merge = true)
	{
		return $this->users->getValidator()->valid($action, $attributes, $merge);
	}

	protected function errors()
	{
		return $this->users->getValidator()->getErrors();
	}

	protected function inputFor($action, $merge = true)
	{
		$rules = $this->users->getValidator()->rules($action, $merge);
		$fields = array_keys($rules);

		$confirmed_fields = array_where($rules, function($key, $rule)
		{
			if(is_array($rule)) {
				return in_array('confirmed', $rule);
			}
			else {
				return $rule !== false;
			}
		});

		foreach (array_keys($confirmed_fields) as $key)
		{
			$fields []= $key . '_confirmation';
		}

		return array_only($this->input(), $fields);
	}

	protected function config($key, $default = null)
	{
		return Config::get("authentify::{$key}", $default);
	}
}