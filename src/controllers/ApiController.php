<?php namespace Authentify;

use App;
use Exception;
use Queue;
use Hash;
use Hybrid_Endpoint;
use Illuminate\Auth\AuthManager;
use anlutro\LaravelController\ApiController as Controller;
use Ruysu\Authentify\Controllers\AuthentifyControllerTrait;
use Ruysu\Authentify\Repositories\UserRepositoryInterface;
use Ruysu\Authentify\Repositories\AuthTokenDatabaseRepository;
use Ruysu\Authentify\Repositories\SocialProfileDatabaseRepository;

class ApiController extends Controller
{
	use AuthentifyControllerTrait;

	protected $social_profiles;
	protected $tokens;
	protected $response = ['success' => false, 'errors' => false, 'message' => false, 'status' => 200];

	public function __construct(UserRepositoryInterface $users, SocialProfileDatabaseRepository $social_profiles, AuthTokenDatabaseRepository $tokens, AuthManager $auth)
	{
		$this->auth = $auth;
		$this->social_profiles = $social_profiles;
		$this->tokens = $tokens;
		$this->users = $users;

		$this->beforeFilter('authentify.token.check', ['except' => ['postSignIn', 'postSignUp']]);
	}

	public function postSignIn()
	{
		$credentials = $this->inputFor('signIn');

		if ($this->valid('signIn', $credentials, false)) {
			// We login the user just for the current request, in order to validate that the account is active
			if ($this->auth->once($credentials)) {
				$user = $this->auth->user();

				if ($user->active) {
					$this->response['token'] = $this->tokens->getToken($user);
					$this->response['user'] = $user;
					$this->response['success'] = true;
				}
				else {
					$this->response['status'] = 401;
					$this->response['message'] = 'Unauthorized, account not yet activated';
				}
			}
			else {
				$this->response['status'] = 401;
				$this->response['message'] = 'Unauthorized';
			}
		}
		else {
			$this->response['status'] = 400;
			return $this->response['errors'] = $this->errors();
		}

		return $this->respond();
	}

	public function postSignUp()
	{
		$input = $this->inputFor('signUp');
		$input['active'] = 1;

		if ($user = $this->users->signUp($input)) {
			$password = $this->input('password');

			$this->config('welcomable') && Queue::push('Ruysu\Authentify\Mailers\SendWelcome', compact('password', 'user'));

			$this->response['token'] = $this->tokens->getToken($user);
			$this->response['user'] = $user;
			$this->response['success'] = true;
		}
		else {
			$this->response['status'] = 400;
			return $this->response['errors'] = $this->errors();
		}

		return $this->respond();
	}

	public function getInfo()
	{
		$user = $this->auth->user();

		$this->response['token'] = $this->tokens->getToken($user);
		$this->response['user'] = $user;
		$this->response['success'] = true;

		return $this->respond();
	}

	public function postEdit()
	{
		$user = $this->auth->user();
		$input = $this->inputFor('edit');

		if($this->users->edit($user, $input)) {
			$this->response['token'] = $this->tokens->getToken($user);
			$this->response['user'] = $user;
			$this->response['success'] = true;
		}
		else {
			$this->response['status'] = 400;
			return $this->response['errors'] = $this->errors();
		}

		return $this->respond();
	}

	public function postPassword()
	{
		$user = $this->auth->user();
		$input = $this->inputFor('updatePassword', false);

		if ($this->valid('updatePassword', $input, false)) {
			if (Hash::check($input['current_password'], $user->password) && $this->users->updatePassword($user, $input)) {
				$this->response['token'] = $this->tokens->getToken($user);
				$this->response['user'] = $user;
				$this->response['success'] = true;
			} 
			else {
				$this->response['status'] = 400;
				return $this->response['errors'] = $this->errors();
			}
		}
		else {
			$this->response['status'] = 400;
			return $this->response['errors'] = $this->errors();
		}

		return $this->respond();
	}

	protected function respond()
	{
		return $this->jsonResponse($this->response, $this->response['status']);
	}

	public function missingMethod($params = array())
	{
		$this->response['status'] = 404;
		$this->response['message'] = 'Not found';
		return $this->respond();
	}
}