@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::comment.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::comment.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($comment, [
      'route' => ['example-blog.comments.store'],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::comments.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
