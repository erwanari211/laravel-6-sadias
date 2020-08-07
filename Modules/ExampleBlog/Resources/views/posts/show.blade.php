@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::post.crud.show'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::post.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($post, [
      'url' => '#',
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::posts.form_fields', ['actionType' => 'show'])
    {{ Form::close() }}
  </div>
@endsection
