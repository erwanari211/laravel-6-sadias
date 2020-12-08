@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::user.crud.show'))

@section('content')
  <div class="container">
		<h1>{{ __('examplepermission::user.crud.show') }}</h1>

		<div class="card mb-4">
      <div class="card-header">{{ __('examplepermission::user.crud.show') }}</div>
      <div class="card-body">
				@include('examplepermission::includes.messages')

				{{ Form::model($user, [
					'url' => '#',
					'method' => 'POST',
					'files' => false,
					]) }}
					@include('examplepermission::users.form_fields', ['actionType' => 'show'])
				{{ Form::close() }}
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">{{ __('examplepermission::role.crud.index') }}</div>
      <div class="card-body">

				{{ Form::model($user, [
					'route' => ['example-permission.users.roles.update', $user->id],
					'method' => 'PUT',
					'files' => false,
					]) }}

          @php
            $userRoleIds = $userRoles->pluck('id')->toArray();
          @endphp

          <div class="mb-4">
            @foreach ($roles as $role)
              @php
                $isChecked = in_array($role->id, $userRoleIds);
              @endphp
              <div class="form-check">
                {{ Form::checkbox('roles[]', $role->id, $isChecked, [
                  'class' => "form-check-input",
                  'id' => "checkbox-". $role->id,
                ]) }}
                <label class="form-check-label" for="checkbox-{{ $role->id }}">
                  {{ $role->name }}
                </label>
              </div>
            @endforeach
          </div>

          <button class="btn btn-primary" type="submit">
            {{ __('my_app.form.save') }}
          </button>

				{{ Form::close() }}
      </div>
    </div>

  </div>
@endsection
