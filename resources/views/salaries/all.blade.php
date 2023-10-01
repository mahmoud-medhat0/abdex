@extends('layouts.parent')
@section('title')
{{ __("salaries_all") }}
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
                        <h3 class="card-title">{{ __("salaries_data") }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __("salary_id") }}</th>
                                    <th>{{ __("employ_name") }}</th>
                                    <th>{{ __("salary_cost") }}</th>
                                    <th>{{ __("salary_discount_total") }}</th>
                                    <th>{{ __("notes") }}</th>
                                    <th>{{ __("salary_done") }}</th>
                                    <th>{{ __("salary_total") }}</th>
                                    <th>{{ __("action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaries as $salary)
                                <tr>
                                    <td>{{ $salary->id }}</td>
                                    <td>{{ $salary->name }}</td>
                                    <td>{{ $salary->salary }}</td>
                                    <td>{{ $salary->discount }}</td>
                                    <td>{{ $salary->notes }}</td>
                                    <td>
                                        @if ($salary->done=='1')
                                        {{ '✅' }}
                                        @elseif ($salary->done=='0')
                                        {{ '❌' }}
                                        @endif
                                    </td>
                                    <td>{{ $salary->salary - $salary->discount }}</td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm" href="{{ route('editsalary',$salary->id) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            {{ __("edit") }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{ __("salary_id") }}</th>
                                    <th>{{ __("employ_name") }}</th>
                                    <th>{{ __("salary_cost") }}</th>
                                    <th>{{ __("salary_discount_total") }}</th>
                                    <th>{{ __("notes") }}</th>
                                    <th>{{ __("salary_done") }}</th>
                                    <th>{{ __("salary_total") }}</th>
                                    <th>{{ __("action") }}</th>
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
