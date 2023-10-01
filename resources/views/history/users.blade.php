@extends('layouts.parent')
@section('title')
سجل تغييرات المستخدمين
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
                  <th>id</th>
                  <th>الحدث</th>
                  <th>القيم القديمه</th>
                  <th>القيم الجديده</th>
                  <th>الشخص</th>
                  <th>الوقت</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $order)
                <tr>
                  <td>{{ $order->sid }}</td>
                  <td>{{ $order->action }}</td>
                  <td>@foreach($oldvalues[$order->sid] as $key =>$value )
                    <br>
                    {{ $key.' : '.$value[0] }}
                  @endforeach</td>
                  <td>@foreach($newvalues[$order->sid] as $key=>$value)
                    <br>
                    {{ $key.' : '.$value[0]}}
                  @endforeach</td>
                  <td>{{ $order->name }}</td>
                  <td>{{ $order->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                    <th>id</th>
                    <th>الحدث</th>
                    <th>القيم القديمه</th>
                    <th>القيم الجديده</th>
                    <th>الشخص</th>
                    <th>الوقت</th>
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
