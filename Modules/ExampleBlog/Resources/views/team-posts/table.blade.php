<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('my_app.table.columns.no') }}</th>
        <th>{{ __('my_app.table.columns.actions') }}</th>

                <th>{{ __('exampleblog::post.attributes.author_id') }}</th>
                <th>{{ __('exampleblog::post.attributes.unique_code') }}</th>
                <th>{{ __('exampleblog::post.attributes.title') }}</th>
                <th>{{ __('exampleblog::post.attributes.slug') }}</th>
                <th>{{ __('exampleblog::post.attributes.content') }}</th>
                <th>{{ __('exampleblog::post.attributes.status') }}</th>

      </tr>
    </thead>
    <tbody>
      @if(count($posts))
        @php $no = $posts->firstItem() @endphp
        @foreach($posts as $post)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example-blog.teams.posts.show', [$team->id, $post->id]) }}">
                {{ __('my_app.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example-blog.teams.posts.edit', [$team->id, $post->id]) }}">
                {{ __('my_app.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example-blog.teams.posts.destroy', $team->id, $post->id],
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

                        <td>{{ $post->author_id }}</td>
                        <td>{{ $post->unique_code }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->slug }}</td>
                        <td>{{ $post->content }}</td>
                        <td>{{ $post->status }}</td>

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

{{ $posts->appends(request()->only(['search']))->links() }}
