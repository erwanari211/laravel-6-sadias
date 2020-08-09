{!! Form::bsText('user_id', null, ['label' => __('exampleblog::team_member.attributes.user_id')]) !!}
{!! Form::bsText('team_id', null, ['label' => __('exampleblog::team_member.attributes.team_id')]) !!}
{!! Form::bsText('role_name', null, ['label' => __('exampleblog::team_member.attributes.role_name')]) !!}
{!! Form::bsNumber('is_active', null, ['label' => __('exampleblog::team_member.attributes.is_active')]) !!}


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

  <a class="btn btn-outline-secondary" href="{{ route('example-blog.team-members.index') }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
