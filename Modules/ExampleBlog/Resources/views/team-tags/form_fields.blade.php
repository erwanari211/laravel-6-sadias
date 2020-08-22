{!! Form::bsText('name', null, ['label' => __('exampleblog::tag.attributes.name')]) !!}
{!! Form::bsText('slug', null, ['label' => __('exampleblog::tag.attributes.slug')]) !!}
{!! Form::bsTextarea('description', null, ['label' => __('exampleblog::tag.attributes.description')]) !!}
{!! Form::bsSelect('is_active', $dropdown['yes_no'], null, ['label' => __('exampleblog::tag.attributes.is_active')]) !!}


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

  <a class="btn btn-outline-secondary" href="{{ route('example-blog.teams.tags.index', [$team->id]) }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
