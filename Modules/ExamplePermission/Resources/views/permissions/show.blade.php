@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::permission.crud.show'))

@section('content')
  <div class="container">
		<h1>{{ __('examplepermission::permission.crud.show') }}</h1>

		<div class="card">
      <div class="card-header">{{ __('examplepermission::permission.crud.show') }}</div>
      <div class="card-body">
				@include('examplepermission::includes.messages')

				{{ Form::model($permission, [
					'url' => '#',
					'method' => 'POST',
					'files' => false,
					]) }}
					@include('examplepermission::permissions.form_fields', ['actionType' => 'show'])
				{{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
