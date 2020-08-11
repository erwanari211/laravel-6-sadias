<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{ __('my_app.table.columns.no') }}</th>
        <th>{{ __('my_app.table.columns.actions') }}</th>

                <th>{{ __('exampleblog::team_member.attributes.email') }}</th>
                <th>{{ __('exampleblog::team_member.attributes.role_name') }}</th>
                <th>{{ __('exampleblog::team_member.attributes.is_active') }}</th>

      </tr>
    </thead>
    <tbody>
      @if(count($teamMembers))
        @php $no = $teamMembers->firstItem() @endphp
        @foreach($teamMembers as $teamMember)
          <tr>
            <td>{{ $no }}</td>
            <td>
              <a class="btn btn-secondary"
                href="{{ route('example-blog.team-members.show', [$team->id, $teamMember->id]) }}">
                {{ __('my_app.crud.show') }}
              </a>
              <a class="btn btn-success"
                href="{{ route('example-blog.team-members.edit', [$team->id, $teamMember->id]) }}">
                {{ __('my_app.crud.edit') }}
              </a>
              {{ Form::open([
                'route' => ['example-blog.team-members.destroy', $team->id, $teamMember->id],
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

                        <td>{{ $teamMember->user->email }}</td>
                        <td>{{ $teamMember->role_name }}</td>
                        <td>{{ $teamMember->is_active }}</td>

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

{{ $teamMembers->appends(request()->only(['search']))->links() }}
