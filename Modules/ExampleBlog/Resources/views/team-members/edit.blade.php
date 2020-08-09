@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team_member.crud.edit'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team_member.crud.edit') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($teamMember, [
      'route' => ['example-blog.team-members.update', $teamMember->id],
      'method' => 'PUT',
      'files' => false,
      ]) }}
      @include('exampleblog::team-members.form_fields', ['actionType' => 'edit'])
    {{ Form::close() }}
  </div>
@endsection
