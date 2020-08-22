{!! Form::bsText('title', null, ['label' => __('exampleblog::post.attributes.title')]) !!}
{!! Form::bsText('slug', null, ['label' => __('exampleblog::post.attributes.slug')]) !!}
{!! Form::bsTextarea('content', null, ['label' => __('exampleblog::post.attributes.content')]) !!}
{!! Form::bsSelect('status', $dropdown['statuses'], null, ['label' => __('exampleblog::post.attributes.status')]) !!}
{!! Form::bsSelect('tags[]', $tags, null, ['label' => __('exampleblog::post.attributes.status'), 'multiple']) !!}

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

  <a class="btn btn-outline-secondary" href="{{ route('example-blog.teams.posts.index', [$team->id]) }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
