@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::tag.crud.show'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::tag.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($tag, [
      'url' => '#',
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::tags.form_fields', ['actionType' => 'show'])
    {{ Form::close() }}
  </div>
@endsection
