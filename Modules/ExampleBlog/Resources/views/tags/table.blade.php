<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('my_app.table.columns.no') }}</th>
        <th>{{ __('my_app.table.columns.actions') }}</th>

                <th>{{ __('exampleblog::tag.attributes.name') }}</th>
                <th>{{ __('exampleblog::tag.attributes.slug') }}</th>
                <th>{{ __('exampleblog::tag.attributes.description') }}</th>
                <th>{{ __('exampleblog::tag.attributes.is_active') }}</th>

      </tr>
    </thead>
    <tbody>
      @if(count($tags))
        @php $no = $tags->firstItem() @endphp
        @foreach($tags as $tag)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example-blog.tags.show', [$tag->id]) }}">
                {{ __('my_app.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example-blog.tags.edit', [$tag->id]) }}">
                {{ __('my_app.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example-blog.tags.destroy', $tag->id],
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

                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->slug }}</td>
                        <td>{{ $tag->description }}</td>
                        <td>{{ $tag->is_active }}</td>

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

{{ $tags->appends(request()->only(['search']))->links() }}
