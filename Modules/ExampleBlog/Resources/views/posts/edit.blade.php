@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::post.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::post.crud.edit') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($post, [
      'route' => ['example-blog.posts.update', $post->id],
      'method' => 'PUT',
      'files' => false,
      ]) }}
      @include('exampleblog::posts.form_fields', ['actionType' => 'edit'])
    {{ Form::close() }}
  </div>
@endsection
