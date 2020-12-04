@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.show'))

@section('content')
  <div class="container">
		<h1>{{ __('examplepermission::user.crud.show') }}</h1>

		<div class="card">
      <div class="card-header">{{ __('examplepermission::user.crud.show') }}</div>
      <div class="card-body">
				@include('examplepermission::includes.messages')

				{{ Form::model($user, [
					'url' => '#',
					'method' => 'POST',
					'files' => false,
					]) }}
					@include('examplepermission::users.form_fields', ['actionType' => 'show'])
				{{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
