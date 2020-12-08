@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::user.crud.edit') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::user.crud.edit') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        {{ Form::model($user, [
          'route' => ['example-permission.users.update', $user->id],
          'method' => 'PUT',
          'files' => false,
          ]) }}
          @include('examplepermission::users.form_fields', ['actionType' => 'edit'])
        {{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
