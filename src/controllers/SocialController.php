<?php namespace Authentify;

use App;
use Exception;
use Queue;
use Hybrid_Endpoint;
use Illuminate\Auth\AuthManager;
use Ruysu\Authentify\Repositories\UserRepositoryInterface;
use Ruysu\Authentify\Repositories\SocialProfileDatabaseRepository;

class SocialController extends AuthentifyController
{
	protected $social_profiles;

	public function __construct(UserRepositoryInterface $users, SocialProfileDatabaseRepository $social_profiles, AuthManager $auth)
	{
		$this->social_profiles = $social_profiles;
		parent::__construct($users, $auth);
	}

	public function getIndex($network)
	{
		$hybridauth = App::make('authentify.hybridauth', $this->url('getDo'));

		$provider = $hybridauth->authenticate($network);
		$profile = $provider->getUserProfile();

		$attributes = array(
			'network' => $network,
			'network_id' => $profile->identifier
		);

		if ($social = $this->social_profiles->findByAttributes($attributes)) {
			if ($user = $this->social_profiles->getUser($social)) {
				$this->auth->login($user);
				return $this->intended('/');
			}
			else {
				return $this->redirect('Authentify\SignInController@getIndex')
					->with('authentify.notice', ['danger', 'Could not login with ' . $network]);
			}
		}
		else {
			$access_token = $provider->getAccessToken();

			$attributes['access_token'] = $access_token['access_token'];
			$attributes['secret'] = $access_token['access_token_secret'];

			if ($this->auth->check()) {
				throw new \Ruysu\Authentify\Exceptions\AuthentifySocialException('You are already login, you cannot login with a social network');
			}
			else {
				$input = array(
					'email' => (isset($profile->emailVerified) && $profile->emailVerified ? $profile->emailVerified : $profile->email) ?: '',
					'username' => isset($profile->username) && $profile->username ? $profile->username : $profile->displayName,
					'name' => $profile->firstName . ' ' . $profile->lastName,
					'picture' => isset($profile->photoURL) && $profile->photoURL ? $profile->photoURL : '',
					'password' => str_random(8),
				);

				if (!($user = $this->users->socialSignUp($input))) {
					return $this->redirect('Authentify\SignUpController@getIndex')
						->withErrors($this->errors())
						->withInput($input);
				}

				if($user->email && $this->config('welcomable')) {
					$password = $input['password'];
					Queue::push('Ruysu\Authentify\Mailers\SendWelcome', compact('password', 'user'));
				}

				$this->social_profiles->setUser($user);
				$this->social_profiles->create($attributes);
				$this->auth->login($user);

				return $this->intended('/');
			}
		}
	}

	public function getDo()
	{
		try {
			Hybrid_Endpoint::process();
		}
		catch(Exception $e) {
			echo "Error at Hybrid_Endpoint process (SteamController@login): $e";
		}
	}
}