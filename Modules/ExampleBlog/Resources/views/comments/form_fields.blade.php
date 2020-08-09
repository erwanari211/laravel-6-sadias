{!! Form::bsText('author_id', null, ['label' => __('exampleblog::comment.attributes.author_id')]) !!}
{!! Form::bsText('post_id', null, ['label' => __('exampleblog::comment.attributes.post_id')]) !!}
{!! Form::bsText('parent_id', null, ['label' => __('exampleblog::comment.attributes.parent_id')]) !!}
{!! Form::bsTextarea('content', null, ['label' => __('exampleblog::comment.attributes.content')]) !!}
{!! Form::bsNumber('is_approved', null, ['label' => __('exampleblog::comment.attributes.is_approved')]) !!}
{!! Form::bsText('status', null, ['label' => __('exampleblog::comment.attributes.status')]) !!}


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

  <a class="btn btn-outline-secondary" href="{{ route('example-blog.comments.index') }}">
    {{ __('my_app.form.back') }}
  </a>
</div>
