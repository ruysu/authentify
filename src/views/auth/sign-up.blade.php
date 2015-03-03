@extends('authentify::auth.layout')

@section('title')

	{{ trans('authentify::labels.sign-up') }}

@stop

@section('content')

		{{ Form::open(array('action' => $sign_up_action)) }} 

@if(Session::has('authentify.signup'))
			<div class="well well-sm">
				<div class="media">
					<a class="pull-left" href="{{ Session::get('authentify.signup.hybridauth.profileURL') }}">
						<img class="media-object" src="{{ Session::get('authentify.signup.hybridauth.photoURL') }}" alt="Image" width="50" height="50">
					</a>
					<div class="media-body">
						<h4 class="media-heading text-primary" style="margin-top: 3px">{{ Session::get('authentify.signup.hybridauth.displayName') }}</h4>
						<p style="margin-bottom: 0">{{ Session::get('authentify.signup.provider') }}</p>
					</div>
				</div>
			</div>
@endif

			<div class="form-group">
				{{ Form::label('authentify-email', trans('authentify::labels.email')) }} 
				{{ Form::email('email', null, array('class' => 'form-control', 'id' => 'authentify-email')) }} 
				{{ $errors->has('email') ? Form::label('authentify-email', $errors->first('email'), array('class' => 'error')) : '' }} 
			</div>

			<div class="form-group">
				{{ Form::label('authentify-password', trans('authentify::labels.password')) }} 
				{{ Form::password('password', array('class' => 'form-control', 'id' => 'authentify-password')) }} 
				{{ $errors->has('password') ? Form::label('authentify-password', $errors->first('password'), array('class' => 'error')) : '' }} 
			</div>

			<div class="form-group">
				{{ Form::label('authentify-password_confirmation', trans('authentify::labels.password_confirmation')) }} 
				{{ Form::password('password_confirmation', array('class' => 'form-control', 'id' => 'authentify-password_confirmation')) }} 
				{{ $errors->has('password_confirmation') ? Form::label('authentify-password_confirmation', $errors->first('password_confirmation'), array('class' => 'error')) : '' }} 
			</div>

			<div class="form-group">
				{{ Form::label('authentify-name', trans('authentify::labels.name')) }} 
				{{ Form::text('name', null, array('class' => 'form-control', 'id' => 'authentify-name')) }} 
				{{ $errors->has('name') ? Form::label('authentify-name', $errors->first('name'), array('class' => 'error')) : '' }} 
			</div>

			{{ Form::submit(trans('authentify::labels.send'), array('class' => 'btn btn-block btn-lg btn-primary')) }} 

		{{ Form::close() }}

@stop