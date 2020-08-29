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

  <a href="{{ route('example.documents.image-resize.create') }}">
    {{ __('my_app.crud.create') }}
  </a>
  <br>

  @php
    $publicPath = public_path();
  @endphp
  <table border=1>
    @if (count($files))
      @foreach ($files as $file)
        @php
          $filePathinfo = pathinfo($file);
          // dump($filePathinfo);
          $directory = str_replace($publicPath.'\\', '', $filePathinfo['dirname']);
          $basename = $filePathinfo['basename'];
          $filePath = $directory . '\\' . $basename;
          $filename = $filePathinfo['filename'];
        @endphp
        <tr>
          <td>
            {{ Form::open([
              'url' => route('example.documents.image-resize.destroy', ['delete', 'url' => $filePath]),
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
          <td>
            <a href="{{ asset($filePath) }}">
              {{ $filename }}
            </a>
          </td>

          @foreach ($dimensions as $dimension)
            <td>
              <a href="{{ asset($directory . '\\' . $dimension . '\\' . $basename) }}">
                {{ $dimension }}
              </a>
            </td>
          @endforeach

        </tr>
      @endforeach
    @else
      <tr>
        <td>No data</td>
      </tr>
    @endif
  </table>
</body>
</html>
