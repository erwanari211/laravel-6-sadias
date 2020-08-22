@extends('exampleblog::layouts.main')

@section('content')
<div class="container">
  <h1>{{ __('exampleblog::channel.crud.index') }}</h1>

  @include('exampleblog::includes.messages')

  <div class="mb-4">
    <a class="btn btn-primary" href="{{ route('example.blog.backend.channels.create') }}">
      {{ __('exampleblog::channel.crud.create') }}
    </a>
  </div>

  @include('exampleblog::channels.table')
</div>

@endsection
