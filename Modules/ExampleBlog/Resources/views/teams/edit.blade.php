@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team.crud.edit') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($team, [
      'route' => ['example-blog.teams.update', $team->id],
      'method' => 'PUT',
      'files' => false,
      ]) }}
      @include('exampleblog::teams.form_fields', ['actionType' => 'edit'])
    {{ Form::close() }}
  </div>
@endsection
