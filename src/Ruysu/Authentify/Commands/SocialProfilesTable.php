<?php namespace Ruysu\Authentify\Commands;

class SocialProfilesTable extends MigrationCommand {

	protected $table = 'social_profiles';
	protected $name = 'authentify:social_profiles-table';
	protected $description = 'Generates migration for social profiles table';

}