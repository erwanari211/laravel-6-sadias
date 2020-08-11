@if(isset($actionType) && ($actionType === 'create' || $actionType === 'show'))
  {!! Form::bsText('email', $teamMember->user->email ?? null, ['label' => __('exampleblog::team_member.attributes.email')]) !!}
@endif
@if(isset($actionType) && ($actionType === 'edit'))
  {!! Form::bsText('email', $teamMember->user->email ?? null, ['label' => __('exampleblog::team_member.attributes.email'), 'disabled']) !!}
@endif
{!! Form::bsSelect('role_name', $dropdown['roles'], null, ['label' => __('exampleblog::team_member.attributes.role_name')]) !!}
{!! Form::bsSelect('is_active', $dropdown['yes_no'], null, ['label' => __('exampleblog::team_member.attributes.is_active')]) !!}


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

  <a class="btn btn-outline-secondary" href="{{ route('example-blog.team-members.index', [$team->id]) }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
