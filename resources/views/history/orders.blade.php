@extends('layouts.parent')
@section('title')
{{ __('orders_history') }}
@endsection
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          @if (session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
          @endif
          <div class="card-header">
            <h3 class="card-title">@yield('title')</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>{{ __('id') }}</th>
                  <th>{{ __('action') }}</th>
                  <th>{{ __('order_id') }}</th>
                  <th>{{ __('old_values') }}</th>
                  <th>{{ __('new_values') }}</th>
                  <th>{{ __('person_changed') }}</th>
                  <th>{{ __('time_changed') }}</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        var table = $('#example1').DataTable({
            responsive: false,
            lengthChange: true,
            autoWidth: false,
            orderCellsTop: true,
            processing: true,
            serverSide: true,
            paging:true,
            buttons: ["excel", "copy", "colvis"],
            dom: 'Bfrtip',
            ajax: {
                url :'{{ route('history_ajax') }}',
                data: function (data) {
                    // Add filters to the DataTables request data
                    {{--  data.id = $('#filterid').val();
                    data.printed = $('#filterprinted').val();
                    data.companyname = $('#filtercompanyname').val();
                    data.statename = $('#filterstatename').val();
                    data.id_police = $('#filterid_police').val();
                    data.name_client = $('#filtername_client').val();
                    data.delegatename = $('#filterdelegate_name').val();
                    data.phone = $('#filterphone').val();
                    data.phone2 = $('#filterphone2').val();
                    data.date = $('#date').val();
                    data.createdat = $('#createdat').val();
                    data.address = $('#filteraddress').val();
                    data.cost = $('#filtercost').val();
                    data.notes = $('#filternotes').val();  --}}
                }
            },
            columns: [
                { data: 'idmain', name: 'id'},
                {data:'action' ,name:'action'},
                {data:'order_id', name:'order_id'},
                {data:null,name:'old',
                render: function (data, type, row) {
                    var dataproc = data.oldmain;
                    var formattedOldData = '';
                    for (var key in dataproc) {
                        formattedOldData += key + ': ' + dataproc[key] + '<br>';
                    }
                    return formattedOldData;
                }},
                {data:null,name:'new',
                render: function (data, type, row) {
                    var dataproc = data.newmain;
                    var formattedOldData = '';
                    for (var key in dataproc) {
                        formattedOldData += key + ': ' + dataproc[key] + '<br>';
                    }
                    return formattedOldData;
                }},
                {data:'changername',name:'changername'},
                {data:'created_atca', name: 'created_atca' },
            ],
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            initComplete: function () {
                // Customize the action buttons here
                table.buttons().container()
                    .appendTo('#example1 .col-md-6:eq(0)');
            }
        });
    });
</script>
@endsection
