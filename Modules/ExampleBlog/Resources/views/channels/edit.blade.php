@extends('exampleblog::layouts.main')

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::channel.crud.edit') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($channel, [
      'route' => ['example.blog.backend.channels.update', $channel->id],
      'method' => 'PUT',
      'files' => false,
      ]) }}
      @include('exampleblog::channels.form_fields', ['actionType' => 'edit'])
    {{ Form::close() }}
  </div>
@endsection
