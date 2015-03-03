@extends('authentify::auth.layout')

@section('title')

	{{ trans('authentify::labels.my-account') }}

@stop

@section('content')

		{{ Form::model(Auth::user(), array('action' => $edit_action)) }} 
			<fieldset>
				<legend>{{ trans('authentify::labels.my-account') }}</legend>

				<div class="form-group">
					{{ Form::label('authentify-email', trans('authentify::labels.email')) }} 
					{{ Form::email('email', null, array('class' => 'form-control', 'id' => 'authentify-email')) }} 
					{{ $errors->has('email') ? Form::label('authentify-email', $errors->first('email'), array('class' => 'error')) : '' }} 
				</div>

				<div class="form-group">
					{{ Form::label('authentify-name', trans('authentify::labels.name')) }} 
					{{ Form::text('name', null, array('class' => 'form-control', 'id' => 'authentify-name')) }} 
					{{ $errors->has('name') ? Form::label('authentify-name', $errors->first('name'), array('class' => 'error')) : '' }} 
				</div>

				{{ Form::submit(trans('authentify::labels.update'), array('class' => 'btn btn-block btn-lg btn-primary')) }} 
			</fieldset>
		{{ Form::close() }} 


@stop