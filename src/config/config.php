<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Rote auto registering
	|--------------------------------------------------------------------------
	|
	| Here you can set routes to register automatically and the prefix that
	| should be used, if you would like to extend the controllers behavior
	| set to false and declare the routes and controllers manually.
	|
	*/

	'routes' => array(
		'auto' => true,
		'prefix' => 'auth'
	),

	/*
	|--------------------------------------------------------------------------
	| Allow user registration
	|--------------------------------------------------------------------------
	|
	| Set to false if you would like to prevent users from registering.
	|
	*/

	'registerable' => true,

	/*
	|--------------------------------------------------------------------------
	| Require email confirmation before allowing then to login
	|--------------------------------------------------------------------------
	|
	| When set to true, an email is dispatched after registering, with an
	| additional step to activate a user account.
	|
	*/

	'confirmable' => true,

	/*
	|--------------------------------------------------------------------------
	| Allow users to edit their information
	|--------------------------------------------------------------------------
	|
	| You can allow users to edit their account information or their password,
	| or none.
	|
	*/	

	'editable' => array(
		'info' => true,
		'password' => true,
	),

	/*
	|--------------------------------------------------------------------------
	| Allow users to reset their passwords
	|--------------------------------------------------------------------------
	|
	| When set to true users may request an e-mail with steps to reset their
	| password.
	|
	*/

	'remindable' => true,

	/*
	|--------------------------------------------------------------------------
	| Send a welcome e-mail to users when they register/activate their account
	|--------------------------------------------------------------------------
	|
	| An email is sent after successful registration or activation depending
	| on your confirmable configuration value.
	|
	*/

	'welcomable' => true,

	/*
	|--------------------------------------------------------------------------
	| Allow users to remember their login
	|--------------------------------------------------------------------------
	|
	| Set to false if you would like to prevent users from registering.
	|
	*/

	'rememberable' => true,

	/*
	|--------------------------------------------------------------------------
	| Enable and configure 3rd party login
	|--------------------------------------------------------------------------
	|
	| Here you can define whether you'd like to enable users to log in to your
	| application using Hybrid_Auth, set enabled to false to prevent this and
	| prevent route auto registering. The Hybrid_Auth configuration is passed
	| as is to its constructor, please refer to their documentation to learn
	| more: http://hybridauth.sourceforge.net/userguide/Configuration.html
	|
	*/

	'social' => array(
		'table' => 'social_profiles',
		'enabled' => true,
		'hybridauth' => array(
			'Twitter' => array(
				'enabled' => true,
				'keys' => array(
					'key' => '',
					'secret' => ''
				)
			),
			'Facebook' => array(
				'enabled' => true,
				'keys' => array(
					'id' => '446992558755793', 
					'secret' => 'e114d6f31587f6fab5c993fe1fa8fa7c'
				),
				'scope' => 'email'
			)
		)
	),

	'auth_tokens' => array(
		'enabled' => true,
		'table' => 'auth_tokens'
	),
);
