@extends('layouts.parent')
@section('title')
{{ __('print history') }}
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
                  <th>{{ __('order_id') }}</th>
                  <th>{{ __('employ_name') }}</th>
                  <th>{{ __('time printed') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $order)
                <tr>
                  <td>{{ $order->id }}</td>
                  <td>{{ $order->order_id }}</td>
                  <td>{{ $order->name }}</td>
                  <td>{{ $order->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                    <th>{{ __('id') }}</th>
                    <th>{{ __('order_id') }}</th>
                    <th>{{ __('employ_name') }}</th>
                    <th>{{ __('time printed') }}</th>
                </tr>
              </tfoot>
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
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
@endsection
