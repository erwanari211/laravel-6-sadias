<!DOCTYPE html>
<html lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Excel</title>
  </head>
  <body>

    @if (session()->has('successMessage'))
      <div class="alert alert-success">
        {{ session('successMessage') }}
      </div>
    @endif

    @if (session()->has('importData'))
      @php
        $importData = session('importData');
        dump(collect($importData)->toArray());
      @endphp
    @endif

    {{ Form::open([
      'url' => route('example.documents.excel.imports.import'),
      'method' => 'post',
      'files' => true,
      ]) }}
      {!! Form::bsFile('excel') !!}
      {!! Form::bsSubmit('Save'); !!}
    {{ Form::close() }}

    <a href="{{ route('example.documents.excel.exports.export') }}">
      Example file
    </a>
  </body>
</html>
