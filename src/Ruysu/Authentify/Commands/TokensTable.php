<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Commands;

class TokensTable extends MigrationCommand
{

	protected $table = 'auth_tokens';
	protected $name = 'authentify:auth_tokens-table';
	protected $description = 'Generates migration for auth_tokens table';

}