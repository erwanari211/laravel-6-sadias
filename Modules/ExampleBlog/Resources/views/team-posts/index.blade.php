@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::post.crud.index'))

@section('content')
<div class="container">
  <h1>{{ __('exampleblog::post.crud.index') }}</h1>

  @include('exampleblog::includes.messages')

  <div class="mb-4">
    <a class="btn btn-primary" href="{{ route('example-blog.teams.posts.create', [$team->id]) }}">
      {{ __('my_app.crud.create') }}
    </a>
  </div>

  @include('exampleblog::team-posts.table')
</div>

@endsection
