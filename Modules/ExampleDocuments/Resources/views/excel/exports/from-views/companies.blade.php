<!DOCTYPE html>
<html>
<head>
  <title>companies</title>
</head>
<body>
  <table border="1">
    <thead>
      <tr>
        <th>No</th>
        <th>Company</th>
        <th>CatchPhrase</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1 @endphp
      @foreach ($companies as $company)
        <tr>
          <td>{{ $no }}</td>
          <td>{{ $company['company'] }}</td>
          <td>{{ $company['catchPhrase'] }}</td>
        </tr>
        @php $no++ @endphp
      @endforeach
    </tbody>
  </table>
</body>
</html>
