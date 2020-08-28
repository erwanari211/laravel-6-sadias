<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Users</title>


  @include('exampledocuments::pdf.styles')

  <style>
    .box {
      border: 1px solid black;
      background-color: #ccc;
      padding: 4px;
    }
  </style>
</head>
<body>

  @include('exampledocuments::pdf.examples.custom-font')
  @include('exampledocuments::pdf.examples.grid')
  @include('exampledocuments::pdf.examples.image')
  @include('exampledocuments::pdf.examples.table')
  @include('exampledocuments::pdf.examples.page-break')
  @include('exampledocuments::pdf.examples.page-break-avoid')

  @include('exampledocuments::pdf.examples.page-number-script')
</body>
</html>
