<!DOCTYPE html>
<html>
<head>
  <title>custom</title>
</head>
<body>
  @php
    $headerCell = 'style="border: 1px solid #000000; background-color: #eeeeee;height: 30px; text-align:center; vertical-align: middle;"';
    $cell = 'style="border: 1px solid #000000"';
    $cell1 = 'style="border: 1px solid #000000; width: 5px;"';
    $cell2 = 'style="border: 1px solid #000000; width: 30px;"';
    $cell3 = 'style="border: 1px solid #000000; width: 10px;"';
  @endphp
  <table>
    <thead>
      <tr>
        <th colspan="3">
          <img src="{{ public_path('assets/images/150x150.png') }}" height="50px" alt="test">
        </th>
      </tr>
      <tr>
        <th colspan="3">
          &nbsp;
        </th>
      </tr>
    </thead>
    <thead>
      <tr>
        <th {!! $headerCell !!} valign="center">No</th>
        <th {!! $headerCell !!} valign="center">Name</th>
        <th {!! $headerCell !!} valign="center">Age</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1 @endphp
      @foreach ($users as $user)
        <tr>
          <td {!! $cell1 !!}>{{ $no }}</td>
          <td {!! $cell2 !!}>{{ $user['name'] }}</td>
          <td {!! $cell3 !!}>{{ $user['age'] }}</td>
        </tr>
        @php $no++ @endphp
      @endforeach
    </tbody>
  </table>
</body>
</html>
