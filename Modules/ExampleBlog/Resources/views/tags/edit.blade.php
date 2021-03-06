@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::tag.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::tag.crud.edit') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($tag, [
      'route' => ['example-blog.tags.update', $tag->id],
      'method' => 'PUT',
      'files' => false,
      ]) }}
      @include('exampleblog::tags.form_fields', ['actionType' => 'edit'])
    {{ Form::close() }}
  </div>
@endsection
