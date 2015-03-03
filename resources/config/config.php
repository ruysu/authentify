<?php

return array(
	'social' => array(
		'enabled' => true,
		'hybridauth' => array(
			'Twitter' => array(
				'enabled' => true,
				'keys' => array(
					'key' => 'eyka8ekAOD9XNxvgjK6PdQ', 
					'secret' => 'STRYTIuS9Sdfw2wjoXb9PZdtaZMU8afTYy3wTIM'
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
	'routes' => array(
		'auto' => true,
		'prefix' => 'auth'
	),
	'registerable' => true,
	'confirmable' => true,
	'editable' => array(
		'info' => true,
		'password' => true,
	),
	'remindable' => true,
	'welcomable' => true,
	'rememberable' => true
);
