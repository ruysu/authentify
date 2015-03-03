<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{{ Lang::get('authentify::emails.activate.title') }}}</h2>

		<div>
			{{{ Lang::get('authentify::emails.activate.body') }}} {{ URL::action('Authentify\SignUpController@getActivate', array($token)) }}.
		</div>
	</body>
</html>
