@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::comment.crud.show'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::comment.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($comment, [
      'url' => '#',
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::comments.form_fields', ['actionType' => 'show'])
    {{ Form::close() }}
  </div>
@endsection
