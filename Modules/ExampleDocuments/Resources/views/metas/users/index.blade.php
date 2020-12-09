<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Meta Users</title>
</head>
<body>
  @if (session()->has('successMessage'))
    <div class="alert alert-success">
      {{ session('successMessage') }}
    </div>
  @endif

  {{ Form::open([
    'url' => route('example.documents.users.index'),
    'method' => 'GET',
    'files' => true,
    ]) }}

    {!! Form::bsText('color', request('color')) !!}
    {!! Form::bsText('size', request('size')) !!}
    {!! Form::bsNumber('min_age', request('min_age')) !!}
    {!! Form::bsNumber('max_age', request('max_age')) !!}

    <div class="form-group">
      <button class="btn btn-primary" type="submit">
        Filter
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
      @if (count($users))

        @foreach ($users as $user)
          <tr>
            <td>
              <a href="{{ route('example.documents.users.show', $user->id) }}">View</a>
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="3">No data</td>
        </tr>
      @endif
    </tbody>
  </table>
</body>
</html>
