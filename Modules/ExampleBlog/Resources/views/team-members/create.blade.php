@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team_member.crud.create'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team_member.crud.create') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($teamMember, [
      'route' => ['example-blog.team-members.store'],
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::team-members.form_fields', ['actionType' => 'create'])
    {{ Form::close() }}
  </div>
@endsection
