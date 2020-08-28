<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Upload File</title>
</head>
<body>
  @if (session()->has('successMessage'))
    <div class="alert alert-success">
      {{ session('successMessage') }}
    </div>
  @endif

  {{ Form::open([
    'url' => route('example.documents.upload-file.store'),
    'method' => 'POST',
    'files' => true,
    ]) }}

    {!! Form::bsFile('file') !!}

    <div class="form-group">
      <button class="btn btn-primary" type="submit">
        {{ __('my_app.form.save') }}
      </button>

      <a href="{{ route('example.documents.upload-file.index') }}">
        {{ __('my_app.form.back') }}
      </a>
    </div>

  {{ Form::close() }}
</body>
</html>
