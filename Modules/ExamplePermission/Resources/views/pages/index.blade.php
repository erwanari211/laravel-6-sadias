@php
    $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.index'))

@section('content')
  <div class="container">
    <h1>Pages</h1>

    <div class="card">
      <div class="card-header">Pages</div>
      <div class="card-body">
        <div class="list-group">
          <a href="{{ route('example-permission.pages.super-admin') }}" class="list-group-item list-group-item-action">Super Admin</a>
          <a href="{{ route('example-permission.pages.admin') }}" class="list-group-item list-group-item-action">Admin</a>
          <a href="{{ route('example-permission.pages.normal') }}" class="list-group-item list-group-item-action">Normal</a>
        </div>
      </div>
    </div>

  </div>

@endsection
