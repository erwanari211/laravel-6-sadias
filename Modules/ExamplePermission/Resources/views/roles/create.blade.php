@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::role.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::role.crud.create') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::role.crud.create') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        {{ Form::model($role, [
          'route' => ['example-permission.roles.store'],
          'method' => 'POST',
          'files' => false,
          ]) }}
          @include('examplepermission::roles.form_fields', ['actionType' => 'create'])
        {{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
