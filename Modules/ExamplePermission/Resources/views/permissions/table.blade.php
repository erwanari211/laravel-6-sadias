<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('my_app.table.columns.no') }}</th>
        <th>{{ __('my_app.table.columns.actions') }}</th>

        <th>{{ __('examplepermission::permission.attributes.name') }}</th>
        <th>{{ __('examplepermission::permission.attributes.guard_name') }}</th>

      </tr>
    </thead>
    <tbody>
      @if(count($permissions))
        @php $no = $permissions->firstItem() @endphp
        @foreach($permissions as $permission)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example-permission.permissions.show', [$permission->id]) }}">
                {{ __('my_app.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example-permission.permissions.edit', [$permission->id]) }}">
                {{ __('my_app.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example-permission.permissions.destroy', $permission->id],
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

            <td>{{ $permission->name }}</td>
            <td>{{ $permission->guard_name }}</td>

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

{{ $permissions->appends(request()->only(['search']))->links() }}
