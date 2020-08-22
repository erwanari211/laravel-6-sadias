@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::comment.crud.index'))

@section('content')
<div class="container">
  <h1>{{ __('exampleblog::comment.crud.index') }}</h1>

  @include('exampleblog::includes.messages')

  <div class="mb-4">
    <a class="btn btn-primary" href="{{ route('example-blog.comments.create') }}">
      {{ __('my_app.crud.create') }}
    </a>
  </div>

  @include('exampleblog::comments.table')
</div>

@endsection
