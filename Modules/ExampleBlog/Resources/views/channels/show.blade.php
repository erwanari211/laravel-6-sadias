@extends('exampleblog::layouts.main')

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::channel.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($channel, [
      'url' => '#',
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::channels.form_fields')
    {{ Form::close() }}
  </div>
@endsection
