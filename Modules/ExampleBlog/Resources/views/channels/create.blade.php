@extends('exampleblog::layouts.main')

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::channel.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($channel, [
      'route' => ['example.blog.backend.channels.store'],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::channels.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
