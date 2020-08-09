@extends('exampleblog::layouts.main')

@section('title', __('exampleblog::team_member.crud.show'))

@section('content')
  <div class="container">
    <h1>{{ __('exampleblog::team_member.crud.show') }}</h1>

    @include('exampleblog::includes.messages')

    {{ Form::model($teamMember, [
      'url' => '#',
      'method' => 'POST',
      'files' => false,
      ]) }}
      @include('exampleblog::team-members.form_fields', ['actionType' => 'show'])
    {{ Form::close() }}
  </div>
@endsection
