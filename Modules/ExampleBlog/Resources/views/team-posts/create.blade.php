@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::post.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::post.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($post, [
      'route' => ['example-blog.teams.posts.store', $team->id],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::team-posts.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
