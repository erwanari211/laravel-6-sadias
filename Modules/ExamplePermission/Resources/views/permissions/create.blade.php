@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::permission.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::permission.crud.create') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::permission.crud.create') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        {{ Form::model($permission, [
          'route' => ['example-permission.permissions.store'],
          'method' => 'POST',
          'files' => false,
          ]) }}
          @include('examplepermission::permissions.form_fields', ['actionType' => 'create'])
        {{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
