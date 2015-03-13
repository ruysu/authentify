<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Commands;

class SocialProfilesTable extends MigrationCommand
{

	protected $table = 'social_profiles';
	protected $name = 'authentify:social_profiles-table';
	protected $description = 'Generates migration for social profiles table';

}