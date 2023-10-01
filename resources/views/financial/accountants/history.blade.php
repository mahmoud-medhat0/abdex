@extends('layouts.parent')
@section('title')
{{__('Account_Statements_for').' '.$name }}
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
            <h3 class="card-title">{{__('Account_Statements_for').' '.$name }}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>{{ _('statement_id')}}</th>
                  <th>{{ __('statement_person') }}</th>
                  <th>{{ __('in') }}</th>
                  <th>{{ __('statement_person') }}</th>
                  <th>{{ __('cause') }}</th>
                  <th>{{ __('statement_created') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($accountants as $accountant)
                <tr>
                  <td>{{ $accountant->id }}</td>
                  <td>{{ $name }}</td>
                  <td>{{ $accountant->creditor }}</td>
                  <td>{{ $accountant->debtor }}</td>
                  <td>{{ $accountant->cause }}</td>
                  <td>{{ $accountant->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                    <th>{{ _('statement_id')}}</th>
                    <th>{{ __('statement_person') }}</th>
                    <th>{{ __('in') }}</th>
                    <th>{{ __('statement_person') }}</th>
                    <th>{{ __('cause') }}</th>
                    <th>{{ __('statement_created') }}</th>
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
