<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{{ Lang::get('authentify::emails.welcome.title') }}}</h2>

		<div>
			<p>{{{ Lang::get('authentify::emails.welcome.body') }}} {{ URL::action('Authentify\SignInController@getIndex') }}.</p>
@if(isset($password) && $password)
			<p>{{{ Lang::get('authentify::emails.welcome.password') }}}: {{ $password }}.</p>
@endif
		</div>
	</body>
</html>
