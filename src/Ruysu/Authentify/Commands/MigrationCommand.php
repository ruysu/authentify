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

abstract class MigrationCommand extends Command
{

	protected $package = 'authentify';

	public function fire() {
		$name = "create_{$this->table}_table";

		$path = $this->laravel['path'] . '/database/migrations';

		$migration = $this->laravel['migration.creator']->create($name, $path);
		$content = $this->laravel['files']->get(__DIR__ . "/stubs/{$this->table}.stub");

		$this->laravel['files']->put($migration, $content);

		$this->info('Migration created successfully!');

		$this->call('dump-autoload');
		
	}

	protected function getArguments() {
		return array();
	}

	protected function getOptions() {
		return array();
	}

}