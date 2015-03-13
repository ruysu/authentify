<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Commands;

class UsersTable extends MigrationCommand
{

	protected $table = 'users';
	protected $name = 'authentify:users-table';
	protected $description = 'Generates migration for users table';

}