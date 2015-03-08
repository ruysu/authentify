<?php namespace Ruysu\Authentify\Repositories;

use anlutro\LaravelRepository\DatabaseRepository;
use Illuminate\Database\DatabaseManager;
use Config;

class SocialProfileDatabaseRepository extends DatabaseRepository implements OwnedRepositoryInterface
{
	use OwnedRepositoryTrait;

	public function __construct (UserRepositoryInterface $users, DatabaseManager $db)
	{
		$this->users = $users;
		$this->table = Config::get('authentify::social.table');
		parent::__construct($db->connection());
	}
}