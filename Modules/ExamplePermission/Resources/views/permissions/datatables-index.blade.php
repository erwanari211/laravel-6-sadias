@php
    $viewLayout = $viewLayout ?? 'examplepermission::layouts.main';
@endphp
@extends($viewLayout)

@section('title', __('examplepermission::permission.crud.index'))

@push('css')
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
@endpush

@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

  <script type="text/javascript">
    $(function() {
      var datatable = $('#main-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('example-permission.permissions.datatables.index') }}',
          data: function (d) {

          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'options', name: 'options'},

          {data: 'name', name: 'name'},
          {data: 'guard_name', name: 'guard_name'},

        ],
        "order": [
          [ 0, "desc" ]
        ],
      });
    });
  </script>
@endpush

@section('content')
  <div class="container">
    <h1>{{ __('examplepermission::permission.crud.index') }}</h1>

    <div class="card">
      <div class="card-header">{{ __('examplepermission::permission.crud.index') }}</div>
      <div class="card-body">
        @include('examplepermission::includes.messages')

        <div class="mb-4">
          <a class="btn btn-primary" href="{{ route('example-permission.permissions.create') }}">
            {{ __('my_app.crud.create') }}
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered" id="main-table">
            <thead>
              <tr>
                <th>{{ __('my_app.table.columns.no') }}</th>
                <th>{{ __('my_app.table.columns.actions') }}</th>

                
        <th>{{ __('examplepermission::permission.attributes.name') }}</th>
        <th>{{ __('examplepermission::permission.attributes.guard_name') }}</th>


              </tr>
            </thead>
          <table>
        </div>

      </div>
    </div>

  </div>

@endsection
