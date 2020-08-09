@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team.crud.show'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($team, [
      'url' => '#',
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::teams.form_fields', ['actionType' => 'show'])
    {{ Form::close() }}
  </div>
@endsection
