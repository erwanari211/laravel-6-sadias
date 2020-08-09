@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($team, [
      'route' => ['example-blog.teams.store'],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::teams.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
