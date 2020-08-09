<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('my_app.table.columns.no') }}</th>
        <th>{{ __('my_app.table.columns.actions') }}</th>

                <th>{{ __('exampleblog::team.attributes.owner_id') }}</th>
                <th>{{ __('exampleblog::team.attributes.name') }}</th>
                <th>{{ __('exampleblog::team.attributes.slug') }}</th>
                <th>{{ __('exampleblog::team.attributes.description') }}</th>
                <th>{{ __('exampleblog::team.attributes.is_active') }}</th>

      </tr>
    </thead>
    <tbody>
      @if(count($teams))
        @php $no = $teams->firstItem() @endphp
        @foreach($teams as $team)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example-blog.teams.show', [$team->id]) }}">
                {{ __('my_app.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example-blog.teams.edit', [$team->id]) }}">
                {{ __('my_app.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example-blog.teams.destroy', $team->id],
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

                        <td>{{ $team->owner_id }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->slug }}</td>
                        <td>{{ $team->description }}</td>
                        <td>{{ $team->is_active }}</td>

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

{{ $teams->appends(request()->only(['search']))->links() }}
