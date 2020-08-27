<!doctype html>
<html lang="en">
  <head>
    <title>Datatable</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
  </head>
  <body>

    <div class="container">
      <div class="row mb-4">
        <div class="col-sm-12">
          <h3>Filter</h3>
        </div>
        <div class="col-sm-6">
          <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date">
        </div>
        <div class="col-sm-6">
          <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date">
        </div>
      </div>


      <table class="table table-hover" id="example-table">
        <thead>
          <tr>
            <th>{{ __('my_app.table.columns.no') }}</th>
            <th>{{ __('my_app.table.columns.actions') }}</th>
            <th>{{ __('exampleblog::post.attributes.unique_code') }}</th>
            <th>{{ __('exampleblog::post.attributes.title') }}</th>
            <th>{{ __('exampleblog::post.attributes.slug') }}</th>
            <th>{{ __('exampleblog::post.attributes.content') }}</th>
            <th>{{ __('exampleblog::post.attributes.status') }}</th>
          </tr>
        </thead>
      </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script type="text/javascript">
      $(function() {
        var datatable = $('#example-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: '{{ route('example-blog.posts-dt.data') }}',
            data: function (d) {
              d.start_date = $('#start_date').val();
              d.end_date = $('#end_date').val();
            }
          },
          columns: [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'options', name: 'options'},
            {data: 'unique_code', name: 'unique_code'},
            {data: 'title', name: 'title'},
            {data: 'slug', name: 'slug'},
            {data: 'content', name: 'content'},
            {data: 'status', name: 'status'},
          ],
          "order": [
            [ 0, "desc" ]
          ],
          "columnDefs": [
            {
              "targets": 6, // completed
              "render": function ( data, type, row ) {
                return data == 'PUBLISHED' ? 'Published' : data;
              },
            },
            {
              "targets": 5, // description
              "render": function ( data, type, row ) {
                return data.length < 100 ? data : data.substring(0,100)+'...';
              },
              "data": function ( row, type, val, meta ) {
                if (type === 'set') {
                  row.content = val;
                  // Store the computed display and filter values for efficiency
                  row.content_display = val.length < 100 ? val : val.substring(0,100)+'...';
                  row.content_filter  = val;
                  return;
                }
                else if (type === 'display') {
                  return row.content_display;
                }
                else if (type === 'filter') {
                  return row.content_filter;
                }

                return row.content;
              }
            },
          ]
        });


        $('#start_date').on('change', function(){
          datatable.draw(true);
        })
        $('#end_date').on('change', function(){
          datatable.draw(true);
        })
      });
    </script>
  </body>
</html>
