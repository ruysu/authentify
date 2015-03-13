<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryCommand extends MigrationCommand
{

	protected $table = 'users';
	protected $name = 'authentify:repository';
	protected $description = 'Creates the repository with the minimum set up required for Authentify to work.';

	public function fire() {
		$this->laravel['files']->makeDirectory(app_path('models/Users'), 0755, true, true);
		$this->info('Repository directory created successfully!');

		$content = $this->laravel['files']->get(__DIR__ . "/stubs/UserRepository.stub");
		$this->laravel['files']->put(app_path('models/Users/UserRepository.php'), $content);
		$this->info('UserRepository created successfully!');

		$content = $this->laravel['files']->get(__DIR__ . "/stubs/UserValidator.stub");
		$this->laravel['files']->put(app_path('models/Users/UserValidator.php'), $content);
		$this->info('UserValidator created successfully!');

		$content = $this->laravel['files']->get(__DIR__ . "/stubs/User.stub");
		$this->laravel['files']->put(app_path('models/User.php'), $content);
		$this->info('User model created successfully!');
	}

	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}

}