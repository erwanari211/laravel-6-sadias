@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team.crud.index'))

@section('content')
<div class="container">
  <h1>{{ __('exampleblog::team.crud.index') }}</h1>

  @include('exampleblog::includes.messages')

  <div class="mb-4">
    <a class="btn btn-primary" href="{{ route('example-blog.teams.create') }}">
      {{ __('my_app.crud.create') }}
    </a>
  </div>

  @include('exampleblog::teams.table')
</div>

@endsection
