<?php namespace Ruysu\Authentify\Repositories;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UserRepositoryTrait
{

	public function signUp(array $attributes)
	{
		return $this->perform('signUp', $this->getNew(), $attributes);
	}

	protected function performSignUp($entity, array $attributes)
	{
		return $this->perform('create', $entity, $attributes, false);
	}

	public function socialSignUp(array $attributes)
	{
		return $this->perform('socialSignUp', $this->getNew(), $attributes);
	}

	protected function performSocialSignUp($entity, array $attributes)
	{
		$entity->active = 1;

		return $this->perform('create', $entity, $attributes, false);
	}

	public function activate($entity)
	{
		return $this->perform('activate', $entity, ['active' => 1], false);
	}

	protected function performActivate($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	public function edit($entity, array $attributes)
	{
		$this->validator->replace('key', $this->getEntityKey($entity));
		return $this->perform('edit', $entity, $attributes);
	}

	protected function performEdit($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	public function updatePassword($entity, array $attributes)
	{
		return $this->perform('updatePassword', $entity, $attributes);
	}

	protected function performUpdatePassword($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	public function changePassword($entity, array $attributes)
	{
		return $this->perform('changePassword', $entity, $attributes);
	}

	protected function performChangePassword($entity, array $attributes)
	{
		return $this->perform('update', $entity, $attributes, false);
	}

	protected function performUpdate($user, array $attributes)
	{
		if (isset($attributes['password']) && empty($attributes['password']))
		{
			unset($attributes['password']);
		}

		$this->uploadFiles($attributes);

		return parent::performUpdate($user, $attributes);
	}

	protected function performCreate($user, array $attributes)
	{
		$this->uploadFiles($attributes);

		return parent::performCreate($user, $attributes);
	}

	protected function uploadFiles(&$attributes)
	{
		$files = array_filter($attributes, function($file)
		{
			return $file instanceof UploadedFile;
		});

		foreach ($files as $key => $file)
		{
			$method = camel_case("upload_{$key}_file");

			if (method_exists($this, $method))
			{
				$attributes[$key] = $this->$method($file);
			}
			else
			{
				$path = public_path('uploads/users');
				!is_dir($path) && mkdir($path, 0755, true);
				$file->move($path);
				$attributes[$key] = asset('uploads/users/' . $file->getClientOriginalName());
			}
		}
		unset($key, $file);
	}

}