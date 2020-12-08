@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::role.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::role.crud.edit') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::role.crud.edit') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        {{ Form::model($role, [
          'route' => ['example-permission.roles.update', $role->id],
          'method' => 'PUT',
          'files' => false,
          ]) }}
          @include('examplepermission::roles.form_fields', ['actionType' => 'edit'])
        {{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
