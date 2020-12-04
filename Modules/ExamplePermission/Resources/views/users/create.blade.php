@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::user.crud.create') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::user.crud.create') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        {{ Form::model($user, [
          'route' => ['example-permission.users.store'],
          'method' => 'POST',
          'files' => false,
          ]) }}
          @include('examplepermission::users.form_fields', ['actionType' => 'create'])
        {{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
