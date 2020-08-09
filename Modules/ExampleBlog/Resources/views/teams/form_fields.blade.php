{!! Form::bsText('owner_id', null, ['label' => __('exampleblog::team.attributes.owner_id')]) !!}
{!! Form::bsText('name', null, ['label' => __('exampleblog::team.attributes.name')]) !!}
{!! Form::bsText('slug', null, ['label' => __('exampleblog::team.attributes.slug')]) !!}
{!! Form::bsTextarea('description', null, ['label' => __('exampleblog::team.attributes.description')]) !!}
{!! Form::bsNumber('is_active', null, ['label' => __('exampleblog::team.attributes.is_active')]) !!}


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

  <a class="btn btn-outline-secondary" href="{{ route('example-blog.teams.index') }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
