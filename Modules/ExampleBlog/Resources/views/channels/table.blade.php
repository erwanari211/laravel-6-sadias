<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('exampleblog::channel.table.columns.no') }}</th>
        <th>{{ __('exampleblog::channel.table.columns.actions') }}</th>
        <th>{{ __('exampleblog::channel.table.columns.owner') }}</th>
        <th>{{ __('exampleblog::channel.table.columns.name') }}</th>
        <th>{{ __('exampleblog::channel.table.columns.slug') }}</th>
      </tr>
    </thead>
    <tbody>
      @if(count($channels))
        @php $no = $channels->firstItem() @endphp
        @foreach($channels as $channel)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example.blog.backend.channels.show', $channel->id) }}">
                {{ __('exampleblog::channel.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example.blog.backend.channels.edit', $channel->id) }}">
                {{ __('exampleblog::channel.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example.blog.backend.channels.destroy', $channel->id],
                'method' => 'DELETE',
                'files' => false,
                'style' => 'display: inline'
                ]) }}
                <button class="btn btn-danger" type="submit"
                  onclick="return confirm('{{ __('exampleblog::channel.table.delete_confirmation') }}')">
                  {{ __('exampleblog::channel.crud.delete') }}
                </button>
              {{ Form::close() }}
            </td>
            <td>{{ $channel->owner->name }}</td>
            <td>{{ $channel->name }}</td>
            <td>{{ $channel->slug }}</td>
          </tr>
          @php $no++ @endphp
        @endforeach
      @else
        <tr>
          <td colspan="99">No Data</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>

{{ $channels->links() }}
