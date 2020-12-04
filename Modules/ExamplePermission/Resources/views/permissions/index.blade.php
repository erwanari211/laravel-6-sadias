@php
    $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::permission.crud.index'))

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::permission.crud.index') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::permission.crud.index') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        <div class="mb-4">
          <a class="btn btn-primary" href="{{ route('example-permission.permissions.create') }}">
            {{ __('my_app.crud.create') }}
          </a>
        </div>

        @include('examplepermission::permissions.table')
      </div>
    </div>

  </div>

@endsection
