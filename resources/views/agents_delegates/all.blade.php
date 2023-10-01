@extends('layouts.parent')
@section('title')
ربط المناديب
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

            <a href="{{ route('manage_add') }}" style="
                  position: absolute;
                  right: 2%;
                  width: auto;
                  bottom: 3px;
              " class="btn btn-primary btn-block">{{ __("connect_delegates_new") }}</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>{{ __("id")}}</th>
                  <th>{{ __("agent_name") }}</th>
                  <th>{{ __("delegate_name") }}</th>
                  <th>{{ __("connect_date") }}</th>
                  <th>{{ __("action") }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($persons as $person)
                <tr>
                  <td>{{ $person->id }}</td>
                  <td>{{ $person->agentname }}</td>
                  <td>{{ $person->name }}</td>
                  <td>{{ $person->created_at }}</td>
                  <td class="project-actions text-right">
                    <a class="btn btn-info btn-sm" href="{{ route('manage_edit',$person->id) }}">
                      <i class="fas fa-pencil-alt">
                      </i>
                      Edit
                    </a>
                    <form action="{{ route('manage_delete',$person->id) }}" method="post">
                      @method('DELETE')
                      @csrf
                      <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>{{ __("id")}}</th>
                  <th>{{ __("agent_name") }}</th>
                  <th>{{ __("delegate_name") }}</th>
                  <th>{{ __("connect_date") }}</th>
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
