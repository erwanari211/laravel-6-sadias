@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team_member.crud.index'))

@section('content')
<div class="container">
  <h1>{{ __('exampleblog::team_member.crud.index') }}</h1>

  @include('exampleblog::includes.messages')

  <div class="mb-4">
    <a class="btn btn-primary" href="{{ route('example-blog.team-members.create', [$team->id]) }}">
      {{ __('my_app.crud.create') }}
    </a>
  </div>

  @include('exampleblog::team-members.table')
</div>

@endsection
