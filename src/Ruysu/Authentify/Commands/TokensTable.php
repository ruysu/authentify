<?php namespace Ruysu\Authentify\Commands;

class TokensTable extends MigrationCommand
{

	protected $table = 'auth_tokens';
	protected $name = 'authentify:auth_tokens-table';
	protected $description = 'Generates migration for auth_tokens table';

}