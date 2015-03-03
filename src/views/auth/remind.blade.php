@extends('authentify::auth.layout')

@section('title')

	{{ trans('authentify::labels.reset-password') }}

@stop

@section('content')

		{{ Form::open(array('action' => $remind_action)) }} 

			<div class="form-group">
				{{ Form::label('authentify-email', trans('authentify::labels.email')) }} 
				{{ Form::email('email', null, array('class' => 'form-control', 'id' => 'authentify-email')) }} 
				{{ $errors->has('email') ? Form::label('authentify-email', $errors->first('email'), array('class' => 'error')) : '' }} 
			</div>

			{{ Form::submit(trans('authentify::labels.send'), array('class' => 'btn btn-block btn-lg btn-primary')) }}  

		{{ Form::close() }}

@stop