{!! Form::bsText('name', null, ['label' => __('examplepermission::user.attributes.name')]) !!}
{!! Form::bsText('email', null, ['label' => __('examplepermission::user.attributes.email')]) !!}
{!! Form::bsPassword('password', ['label' => __('examplepermission::user.attributes.password')]) !!}
{!! Form::bsPassword('password_confirmation', ['label' => __('examplepermission::user.attributes.password_confirmation')]) !!}


<div class="form-group">
  @if(isset($actionType) && $actionType === 'create')
    <button class="btn btn-primary" type="submit">
      {{ __('my_app.form.save') }}
    </button>
  @endif

  @if(isset($actionType) && $actionType === 'edit')
    <button class="btn btn-success" type="submit">
      {{ __('my_app.form.edit') }}
    </button>
  @endif

  <a class="btn btn-outline-secondary" href="{{ route('example-permission.users.index') }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
