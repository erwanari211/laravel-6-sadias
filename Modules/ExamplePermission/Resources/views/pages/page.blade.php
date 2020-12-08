@php
    $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.index'))

@section('content')
  <div class="container">
    <h1>Pages</h1>

    <div class="card">
      <div class="card-header">Pages</div>
      <div class="card-body">
        <h3 class="mb-4">{{ $pageName ?? '' }}</h3>

        <a href="{{ route('example-permission.pages.home') }}" class="btn btn-secondary">
          Back
        </a>
      </div>
    </div>

  </div>

@endsection
