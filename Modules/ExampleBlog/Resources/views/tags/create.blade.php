@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::tag.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::tag.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($tag, [
      'route' => ['example-blog.tags.store'],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::tags.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
