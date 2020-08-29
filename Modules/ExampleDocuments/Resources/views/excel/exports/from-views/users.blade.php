<!DOCTYPE html>
<html>
<head>
  <title>users</title>
</head>
<body>
  <table border="1">
    <thead>
      <tr>
        <th>No</th>
        <th>Name</th>
        <th>Age</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1 @endphp
      @foreach ($users as $user)
        <tr>
          <td>{{ $no }}</td>
          <td>{{ $user['name'] }}</td>
          <td>{{ $user['age'] }}</td>
        </tr>
        @php $no++ @endphp
      @endforeach
    </tbody>
  </table>
</body>
</html>
