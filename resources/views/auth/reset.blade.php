@extends('authentify::auth.layout')

@section('title')

	{{ trans('authentify::labels.reset-password') }}

@stop

@section('content')

		{{ Form::open(array('action' => $reset_action)) }} 
			{{ Form::hidden('token', $token) }} 

			<div class="form-group">
				{{ Form::label('authentify-password', trans('authentify::labels.new-password')) }} 
				{{ Form::password('password', array('class' => 'form-control', 'id' => 'authentify-password')) }} 
				{{ $errors->has('password') ? Form::label('authentify-password', $errors->first('password'), array('class' => 'error')) : '' }} 
			</div>

			<div class="form-group">
				{{ Form::label('authentify-password_confirmation', trans('authentify::labels.new-password_confirmation')) }} 
				{{ Form::password('password_confirmation', array('class' => 'form-control', 'id' => 'authentify-password_confirmation')) }} 
				{{ $errors->has('password_confirmation') ? Form::label('authentify-password_confirmation', $errors->first('password_confirmation'), array('class' => 'error')) : '' }} 
			</div> 

			{{ Form::submit(trans('authentify::labels.send'), array('class' => 'btn btn-block btn-lg btn-primary')) }} 
		{{ Form::close() }}

@stop