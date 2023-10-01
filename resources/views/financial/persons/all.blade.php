@extends('layouts.parent')
@section('title')
{{ __('Account_Statements_persons') }}
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

                        <a href="{{ route('add_personfin') }}" style="
                  position: absolute;
                  right: 2%;
                  width: auto;
                  bottom: 3px;
              " class="btn btn-primary btn-block">{{ __('Account_Statements_person_add') }}</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('id') }}</th>
                                    <th>{{ __('statement_person') }}</th>
                                    <th>{{ __('rank') }}</th>
                                    <th>{{ __('in') }}</th>
                                    <th>{{ __('out') }}</th>
                                    <th>{{ __('total') }}</th>
                                    <th>{{ __('Account_Statement') }}</th>
                                    <th>{{ __('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($persons as $person)
                                <tr>
                                    <td>{{ $person->id }}</td>
                                    <td>{{ $person->name }}</td>
                                    <td>{{ $person->rank }}</td>
                                    <td>{{ $dues[$person->id] }}</td>
                                    <td>{{ $payed[$person->id] }}</td>
                                    <td>{{ $payed[$person->id]-$dues[$person->id] }}</td>
                                    <td><a href="{{ route('person_acontant',$person->id)}}">{{ __('view') }}</a></td>
                                    <td class="project-actions text-right">
                                        <a class="btn btn-info btn-sm" href="{{ route('edit_personfin',$person->id) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            {{ __('edit') }}
                                        </a>
                                        <form action="{{ route('destroy_personfin',$person->id) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>{{ __('delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{ __('id') }}</th>
                                    <th>{{ __('statement_person') }}</th>
                                    <th>{{ __('rank') }}</th>
                                    <th>{{ __('in') }}</th>
                                    <th>{{ __('out') }}</th>
                                    <th>{{ __('total') }}</th>
                                    <th>{{ __('Account_Statement') }}</th>
                                    <th>{{ __('action') }}</th>
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
