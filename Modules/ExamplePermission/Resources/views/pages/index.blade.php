@php
    $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.index'))

@section('content')
  <div class="container">
    <h1>Pages</h1>

    <div class="card mb-4">
      <div class="card-header">Master</div>
      <div class="card-body">
        <div class="list-group">
          <a href="{{ route('example-permission.users.index') }}" class="list-group-item list-group-item-action">Users</a>
          <a href="{{ route('example-permission.permissions.index') }}" class="list-group-item list-group-item-action">Permissions</a>
          <a href="{{ route('example-permission.roles.index') }}" class="list-group-item list-group-item-action">Roles</a>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">Roles</div>
      <div class="card-body">
        <div class="list-group">
          <a href="{{ route('example-permission.pages.super-admin') }}" class="list-group-item list-group-item-action">Super Admin</a>
          <a href="{{ route('example-permission.pages.admin') }}" class="list-group-item list-group-item-action">Admin</a>
          <a href="{{ route('example-permission.pages.normal') }}" class="list-group-item list-group-item-action">Normal</a>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">Permissions</div>
      <div class="card-body">
        <div class="list-group">
          @foreach ($permissions as $permission)
            <a href="{{ route('example-permission.pages.permission.show', $permission->id) }}" class="list-group-item list-group-item-action">{{ $permission->name }}</a>
          @endforeach
        </div>
      </div>
    </div>

  </div>

@endsection
