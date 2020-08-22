{!! Form::bsText('name', null, ['label' => __('exampleblog::channel.form.name')]) !!}
{!! Form::bsText('slug', null, ['label' => __('exampleblog::channel.form.slug')]) !!}
{!! Form::bsTextarea('description', null, ['label' => __('exampleblog::channel.form.description')]) !!}

<div class="form-group">
  @if(isset($actionType) && $actionType === 'create')
    <button class="btn btn-primary" type="submit">
      {{ __('exampleblog::channel.form.save') }}
    </button>
  @endif

  @if(isset($actionType) && $actionType === 'edit')
    <button class="btn btn-success" type="submit">
      {{ __('exampleblog::channel.form.edit') }}
    </button>
  @endif

  <a class="btn btn-outline-secondary" href="{{ route('example.blog.backend.channels.index') }}">
    {{ __('exampleblog::channel.form.back') }}
  </a>
</div>
