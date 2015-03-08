<?php namespace Authentify;

use App;
use Exception;
use Queue;
use Browser;
use Hybrid_Endpoint;
use Rhumsaa\Uuid\Uuid;
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
	}

	public function postSignIn() {
		$credentials = $this->inputFor('signIn');

		if ($this->valid('signIn', $credentials, false)) {
			// We login the user just for the current request, in order to validate that the account is active
			if ($this->auth->once($credentials)) {
				$user = $this->auth->user();

				if ($user->active) {
					$this->tokens->setUser($user);

					$browser = new Browser;
					$attributes = [
						'client' => implode(' ', [$browser->getBrowser(), $browser->getVersion(), 'on', $browser->getPlatform()])
					];

					if (!($token = $this->tokens->findByAttributes($attributes))) {
						$attributes['token'] = (string) Uuid::uuid1();
						$token = $this->tokens->create($attributes);
					}

					$this->response['token'] = $token->token;
					$this->response['user'] = $user;
					$this->response['success'] = true;
				}
				else {
					$this->response['status'] = 403;
					$this->response['message'] = 'Unauthorized, account not yet activated';
				}
			}
			else {
				$this->response['status'] = 403;
				$this->response['message'] = 'Unauthorized';
			}
		}
		else {
			$this->response['status'] = 400;
			return $this->response['errors'] = $this->errors();
		}

		return $this->respond();
	}

	public function postSignUp() {
		$input = $this->inputFor('signUp');
		$input['active'] = !$this->config('confirmable');

		if ($user = $this->users->signUp($input)) {
			$password = $this->input('password');

			if ($input['active']) {
				$this->config('welcomable') && Queue::push('Ruysu\Authentify\Mailers\SendWelcome', compact('password', 'user'));
				$this->login($user);

				return $this->intended('/');
			}
			else {
				$token = Crypt::encrypt($user->id);
				Queue::push('Ruysu\Authentify\Mailers\SendActivate', compact('password', 'token', 'user'));

				return $this->redirect('Authentify\SignInController@getIndex')
					->with('authentify.notice', array('warning', trans('authentify::messages.sign-up.success')));
			}
		}
		else {
			return $this->redirect('getIndex')
				->withErrors($this->errors())
				->withInput();
		} 
	}

	protected function respond() {
		return $this->jsonResponse($this->response, $this->response['status']);
	}
}