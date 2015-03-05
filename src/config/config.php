<?php

return array(
	'social' => array(
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
					'id' => '',
					'secret' => ''
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
