@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::permission.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::permission.crud.edit') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::permission.crud.edit') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        {{ Form::model($permission, [
          'route' => ['example-permission.permissions.update', $permission->id],
          'method' => 'PUT',
          'files' => false,
          ]) }}
          @include('examplepermission::permissions.form_fields', ['actionType' => 'edit'])
        {{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
