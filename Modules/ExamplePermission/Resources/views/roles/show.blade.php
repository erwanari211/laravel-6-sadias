@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::role.crud.show'))

@section('content')
  <div class="container">
		<h1>{{ __('examplepermission::role.crud.show') }}</h1>

		<div class="card">
      <div class="card-header">{{ __('examplepermission::role.crud.show') }}</div>
      <div class="card-body">
				@include('examplepermission::includes.messages')

				{{ Form::model($role, [
					'url' => '#',
					'method' => 'POST',
					'files' => false,
					]) }}
					@include('examplepermission::roles.form_fields', ['actionType' => 'show'])
				{{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
