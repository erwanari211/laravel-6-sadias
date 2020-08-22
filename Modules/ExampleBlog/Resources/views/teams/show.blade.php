@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team.crud.show'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    <div class="mb-4">
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('example-blog.teams.index') }}">
        {{ __('my_app.form.back') }}
      </a>
    </div>

    <div class="card">
      <div class="card-header">
        {{ $team->name }}
      </div>
      <div class="card-body">
        <h4 class="card-title">{{ $team->name }}</h4>
        <p class="card-text">{{ $team->description }}</p>
      </div>
    </div>
  </div>
@endsection
