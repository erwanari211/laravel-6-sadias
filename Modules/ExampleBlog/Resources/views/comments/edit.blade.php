@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::comment.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::comment.crud.edit') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($comment, [
      'route' => ['example-blog.comments.update', $comment->id],
      'method' => 'PUT',
      'files' => false,
      ]) }}
      @include('exampleblog::comments.form_fields', ['actionType' => 'edit'])
    {{ Form::close() }}
  </div>
@endsection
