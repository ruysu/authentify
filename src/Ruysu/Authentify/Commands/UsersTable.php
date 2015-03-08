<?php namespace Ruysu\Authentify\Commands;

class UsersTable extends MigrationCommand
{

	protected $table = 'users';
	protected $name = 'authentify:users-table';
	protected $description = 'Generates migration for users table';

}