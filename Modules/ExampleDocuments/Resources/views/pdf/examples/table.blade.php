<h3>Users</h3>

<p><strong>No Class</strong></p>
<table class="">
  <thead>
    <tr>
      <th width="20px">No.</th>
      <th>Name</th>
      <th>Age</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1 ;?>
    @foreach ($users as $user)
      <tr>
        <td>{{ $no }}</td>
        <td>{{ $user['name'] }}</td>
        <td class="text-center">{{ rand(25,40) }}</td>
      </tr>
      <?php $no++ ;?>
    @endforeach
  </tbody>
</table>

<p><strong>.table</strong></p>
<table class="table">
  <thead>
    <tr>
      <th width="20px">No.</th>
      <th>Name</th>
      <th>Age</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1 ;?>
    @foreach ($users as $user)
      <tr>
        <td>{{ $no }}</td>
        <td>{{ $user['name'] }}</td>
        <td class="text-center">{{ rand(25,40) }}</td>
      </tr>
      <?php $no++ ;?>
    @endforeach
  </tbody>
</table>

<p><strong>.table.table-bordered</strong></p>
<table class="table table-bordered">
  <thead>
    <tr>
      <th width="20px">No.</th>
      <th>Name</th>
      <th>Age</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1 ;?>
    @foreach ($users as $user)
      <tr>
        <td>{{ $no }}</td>
        <td>{{ $user['name'] }}</td>
        <td class="text-center">{{ rand(25,40) }}</td>
      </tr>
      <?php $no++ ;?>
    @endforeach
  </tbody>
</table>

<p><strong>.table.table-borderless</strong></p>
<table class="table table-borderless">
  <thead>
    <tr>
      <th width="20px">No.</th>
      <th>Name</th>
      <th>Age</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1 ;?>
    @foreach ($users as $user)
      <tr>
        <td>{{ $no }}</td>
        <td>{{ $user['name'] }}</td>
        <td class="text-center">{{ rand(25,40) }}</td>
      </tr>
      <?php $no++ ;?>
    @endforeach
  </tbody>
</table>
