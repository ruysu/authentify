<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Repositories;

use anlutro\LaravelRepository\DatabaseRepository;
use Illuminate\Database\DatabaseManager;
use Config;
use Datetime;

class SocialProfileDatabaseRepository extends DatabaseRepository implements OwnedRepositoryInterface
{
	use OwnedRepositoryTrait;

	public function __construct (UserRepositoryInterface $users, DatabaseManager $db)
	{
		$this->users = $users;
		$this->table = Config::get('authentify::social.table');
		parent::__construct($db->connection());
	}

	public function create(array $attributes) {
		$attributes['updated_at'] = with(new Datetime)->format('Y-m-d H:i:s');
		$attributes['created_at'] = $attributes['updated_at'];

		return parent::create($attributes);
	}

	public function update($entity, array $attributes) {
		$attributes['updated_at'] = with(new Datetime)->format('Y-m-d H:i:s');

		return parent::create($entity, $attributes);
	}
}