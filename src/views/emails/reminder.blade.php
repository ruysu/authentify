<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{{ Lang::get('authentify::emails.remind.title') }}}</h2>

		<div>
			{{{ Lang::get('authentify::emails.remind.body') }}} {{ URL::action('Authentify\RemindController@getReset', array($token)) }}.
		</div>
	</body>
</html>
