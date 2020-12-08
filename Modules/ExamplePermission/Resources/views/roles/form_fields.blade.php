{!! Form::bsText('name', null, ['label' => __('examplepermission::role.attributes.name')]) !!}
{!! Form::bsText('guard_name', null, ['label' => __('examplepermission::role.attributes.guard_name')]) !!}


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

  <a class="btn btn-outline-secondary" href="{{ route('example-permission.roles.index') }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
