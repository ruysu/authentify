<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>@yield('page-title', 'Authenfify')</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
	<style type="text/css">
		body {
			background: #f0f0f0;
		}

		.authentify-container {
			margin: auto;
			padding: 20px;
			max-width: 440px;
		}

		.authentify-container h1 {
			margin: 0 0 20px;
		}

		.authentify-container .authentify-box {
			background: white;
			border: 1px solid #f0f0f0;
			border-radius: 6px;
			margin: 0;
			padding: 20px;
		}

		.authentify-container label.error {
			color: #c00;
			font-size: 12px;
			font-weight: normal;
		}
	</style>
</head>
<body>
	<div class="authentify-container">
		<h1 class="text-center">@yield('title', 'Authentify')</h1>
		<div class="authentify-box">
@if(Session::has('authentify.notice'))
			<div class="alert alert-{{ Session::get('authentify.notice.0') }}">
				{{ Session::get('authentify.notice.1') }}
			</div>
@endif
			@yield('content')
		</div>
	</div>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>