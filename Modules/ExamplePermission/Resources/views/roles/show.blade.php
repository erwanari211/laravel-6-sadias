@php
  $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::role.crud.show'))

@section('content')
  <div class="container">
		<h1>{{ __('examplepermission::role.crud.show') }}</h1>

		<div class="card mb-4">
      <div class="card-header">{{ __('examplepermission::role.crud.show') }}</div>
      <div class="card-body">
				@include('examplepermission::includes.messages')

				{{ Form::model($role, [
					'url' => '#',
					'method' => 'POST',
					'files' => false,
					]) }}
					@include('examplepermission::roles.form_fields', ['actionType' => 'show'])
				{{ Form::close() }}
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">{{ __('examplepermission::role.crud.index') }}</div>
      <div class="card-body">

				{{ Form::model($role, [
					'route' => ['example-permission.roles.permissions.update', $role->id],
					'method' => 'PUT',
					'files' => false,
					]) }}

          @php
            $rolePermissionIds = [];
            if ($rolePermissions && count($rolePermissions)) {
              $rolePermissionIds = $rolePermissions->pluck('id')->toArray();
            }
          @endphp

          <div class="mb-4">
            @foreach ($permissions as $permission)
              @php
                $isChecked = in_array($permission->id, $rolePermissionIds);
              @endphp
              <div class="form-check">
                {{ Form::checkbox('permissions[]', $permission->id, $isChecked, [
                  'class' => "form-check-input",
                  'id' => "checkbox-". $permission->id,
                ]) }}
                <label class="form-check-label" for="checkbox-{{ $permission->id }}">
                  {{ $permission->name }}
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
