@extends('authentify::auth.layout')

@section('title')

	{{ trans('authentify::labels.my-account') }}

@stop

@section('content')

		{{ Form::model(Auth::user(), array('action' => $password_action)) }} 
			<fieldset>
				<legend>{{ trans('authentify::labels.change-password') }}</legend>

				<div class="form-group">
					{{ Form::label('authentify-current_password', trans('authentify::labels.current-password')) }} 
					{{ Form::password('current_password', array('class' => 'form-control', 'id' => 'authentify-current_password')) }} 
					{{ $errors->has('current_password') ? Form::label('authentify-current_password', $errors->first('current_password'), array('class' => 'error')) : '' }} 
				</div>

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

				{{ Form::submit(trans('authentify::labels.update'), array('class' => 'btn btn-block btn-lg btn-primary')) }} 
			</fieldset>
		{{ Form::close() }} 

@stop