@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::post.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::post.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($post, [
      'route' => ['example-blog.posts.store'],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::posts.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
