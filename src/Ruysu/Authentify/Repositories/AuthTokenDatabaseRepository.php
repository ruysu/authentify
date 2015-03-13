<?php
/**
 * Laravel 4 Authentication with an abstraction layer. 
 *
 * @author   Gerardo Gómez <code@gerardo.im>
 * @license  http://opensource.org/licenses/MIT
 * @package  authentify
 */

namespace Ruysu\Authentify\Repositories;

use Browser;
use Config;
use Datetime;
use Rhumsaa\Uuid\Uuid;
use anlutro\LaravelRepository\DatabaseRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;

class AuthTokenDatabaseRepository extends DatabaseRepository implements OwnedRepositoryInterface
{
	use OwnedRepositoryTrait;

	protected $browser;
	protected $encrypter;

	public function __construct (UserRepositoryInterface $users, DatabaseManager $db, Encrypter $encrypter, Browser $browser)
	{
		$this->browser = $browser;
		$this->users = $users;
		$this->encrypter = $encrypter;
		$this->table = Config::get('authentify::auth_tokens.table');
		parent::__construct($db->connection());
	}

	public function getToken($user)
	{
		if(!$user) {
			return false;
		}

		$this->setUser($user);

		$attributes = [
			'client' => $this->browser()
		];

		if (!($token = $this->findByAttributes($attributes))) {
			$attributes['token'] = (string) Uuid::uuid1();
			$attributes['client'] = $this->browser();

			$token = $this->create($attributes);
		}

		return $this->serializeToken($token);
	}

	public function attempt($token)
	{
		$this->setUser(null);

		if (!($token instanceof Fluent)) {
			$token = $this->deserializeToken($token);
		}

		if (!($token && $token->client == $this->browser())) {
			return false;
		}

		$attributes = [
			'token' => $token->token,
			'client' => $this->browser()
		];

		if ($token = $this->findByAttributes($attributes)) {
			return $this->getUser($token);
		}

		return false;
	}

	public function create(array $attributes)
	{
		$attributes['updated_at'] = with(new Datetime)->format('Y-m-d H:i:s');
		$attributes['created_at'] = $attributes['updated_at'];

		return parent::create($attributes);
	}

	public function update($entity, array $attributes)
	{
		$attributes['updated_at'] = with(new Datetime)->format('Y-m-d H:i:s');

		return parent::create($entity, $attributes);
	}

	protected function browser()
	{
		return implode(' ', [$this->browser->getBrowser(), $this->browser->getVersion(), 'on', $this->browser->getPlatform()]);
	}

	public function serializeToken($token)
	{
		$payload = $this->encrypter->encrypt(array(
			'user_id' => $token->user_id,
			'token' => $token->token,
			'client' => $token->client,
		));

		$payload = str_replace(array('+', '/', '\r', '\n', '='), array('-', '_'), $payload);

		return $payload;
	}

	public function deserializeToken($payload)
	{
		try {
			$payload = str_replace(array('-', '_'), array('+', '/'), $payload);
			$data = $this->encrypter->decrypt($payload);
		}
		catch (DecryptException $e) {
			return null;
		}

		if (empty($data['user_id']) || empty($data['token']) || empty($data['client'])) {
			return null;
		}

		$token = $this->findByAttributes($data);

		return $token;
	}
}