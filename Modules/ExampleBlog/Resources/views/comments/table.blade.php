<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('my_app.table.columns.no') }}</th>
        <th>{{ __('my_app.table.columns.actions') }}</th>

                <th>{{ __('exampleblog::comment.attributes.author_id') }}</th>
                <th>{{ __('exampleblog::comment.attributes.post_id') }}</th>
                <th>{{ __('exampleblog::comment.attributes.parent_id') }}</th>
                <th>{{ __('exampleblog::comment.attributes.content') }}</th>
                <th>{{ __('exampleblog::comment.attributes.is_approved') }}</th>
                <th>{{ __('exampleblog::comment.attributes.status') }}</th>

      </tr>
    </thead>
    <tbody>
      @if(count($comments))
        @php $no = $comments->firstItem() @endphp
        @foreach($comments as $comment)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example-blog.comments.show', [$comment->id]) }}">
                {{ __('my_app.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example-blog.comments.edit', [$comment->id]) }}">
                {{ __('my_app.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example-blog.comments.destroy', $comment->id],
                'method' => 'DELETE',
                'files' => false,
                'style' => 'display: inline'
                ]) }}
                <button class="btn btn-danger" type="submit"
                  onclick="return confirm('{{ __('my_app.table.delete_confirmation') }}')">
                  {{ __('my_app.crud.delete') }}
                </button>
              {{ Form::close() }}
            </td>

                        <td>{{ $comment->author_id }}</td>
                        <td>{{ $comment->post_id }}</td>
                        <td>{{ $comment->parent_id }}</td>
                        <td>{{ $comment->content }}</td>
                        <td>{{ $comment->is_approved }}</td>
                        <td>{{ $comment->status }}</td>

          </tr>
          @php $no++ @endphp
        @endforeach
      @else
        <tr>
          <td colspan="99">{{ __('my_app.table.no_data') }}</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>

{{ $comments->appends(request()->only(['search']))->links() }}
