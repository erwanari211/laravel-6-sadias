<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Meta Users</title>
</head>
<body>
  <a href="{{ route('example.documents.users.index') }}">
    {{ __('my_app.form.back') }}
  </a>

  <table border=1>
    <thead>
      <tr>
        <th>Options</th>
        <th>Name</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <a href="{{ route('example.documents.users.show', $user->id) }}">View</a>
        </td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
      </tr>
    </tbody>
  </table>

  <hr>

  @if (session()->has('successMessage'))
    <div class="alert alert-success">
      {{ session('successMessage') }}
    </div>
  @endif

  {{ Form::open([
    'url' => route('example.documents.users.metas.store', $user->id ),
    'method' => 'POST',
    'files' => true,
    ]) }}

    {!! Form::bsText('name') !!}
    {!! Form::bsTextarea('value') !!}

    <div class="form-group">
      <button class="btn btn-primary" type="submit">
        {{ __('my_app.form.save') }}
      </button>

      <a href="{{ route('example.documents.users.index') }}">
        {{ __('my_app.form.back') }}
      </a>
    </div>

  {{ Form::close() }}

  <hr>

  <table border=1>
    <thead>
      <tr>
        <th>Options</th>
        <th>Name</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      @if (count($meta))
        @foreach ($meta as $metaKey => $metaValue)
          <tr>
            <td>
              {{ Form::open([
                'url' => route('example.documents.users.metas.destroy', [$user->id]),
                'method' => 'DELETE',
                'files' => false,
                'style' => 'display: inline'
                ]) }}
                {!! Form::hidden('key', $metaKey) !!}
                <button class="btn btn-danger" type="submit"
                  onclick="return confirm('{{ __('my_app.table.delete_confirmation') }}')">
                  {{ __('my_app.crud.delete') }}
                </button>
              {{ Form::close() }}
            </td>
            <td>{{ $metaKey }}</td>
            <td>{{ $metaValue }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="3">{{ __('my_app.table.no_data') }}</td>
        </tr>
      @endif
    </tbody>
  </table>
</body>
</html>
