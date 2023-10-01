@extends('layouts.parent')
@section('title')
{{ __("users_all") }}
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
            <h3 class="card-title">{{ __("users_data") }}</h3>

            <a href="{{ route('user_add') }}" style="
                  position: absolute;
                  right: 2%;
                  width: auto;
                  bottom: 3px;
              " class="btn btn-primary btn-block">{{ __("users_add") }}</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>{{ __("id") }}</th>
                  <th>{{ __("name") }}</th>
                  <th>{{ __("username") }}</th>
                  <th>{{ __("gender") }}</th>
                  <th>{{ __("identy_number") }}</th>
                  <th>{{ __("driving_license") }}</th>
                  <th>{{ __("bike_license") }}</th>
                  <th>{{ __("rank") }}</th>
                  <th>{{ __("action") }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->username }}</td>
                  <td>@if ($user->gender =='m')
                    {{ __("male") }}
                    @endif
                    @if ($user->gender =='f')
                    {{ __("female") }}
                    @endif
                  </td>
                  <td>
                    @if ($user->identy_number!='*')
                    <a class="btn btn-info" href="{{ $user->identy_number }}"><i
                        class="fas fa-eye"></i>{{ __("view") }}</a>
                    @else
                    {{ __("not_found") }}
                    @endif
                  </td>
                  <td>
                    @if ($user->Driving_License!='*')
                    <a class="btn btn-info" href="{{ $user->Driving_License }}"><i
                        class="fas fa-eye"></i>{{ __("view") }}</a>
                    @else
                    {{ __("not_found") }}
                    @endif
                  </td>
                  <td>
                    @if ($user->bike_license!='*')
                    <a class="btn btn-info" href="{{ $user->bike_license }}"><i
                        class="fas fa-eye"></i>{{ __("view") }}</a>
                    @else
                    {{ __("not_found") }}
                    @endif
                  </td>
                  <td>
                    @switch($user->rank_id)
                    @case(1)
                    {{ __("admin") }}
                    @break
                    @case(2)
                    {{ __("customer_service") }}
                    @break
                    @case(3)
                    {{ __("operations") }}
                    @break
                    @case(4)
                    {{ __("data_entry") }}
                    @break
                    @case(5)
                    {{ __("acountant") }}
                    @break
                    @case(6)
                    {{ __("sales") }}
                    @break
                    @case(7)
                    {{ __("company") }}
                    @break
                    @case(8)
                    {{ __("agent") }}
                    @break
                    @case(9)
                    {{ __("delegate") }}
                    @break
                    @endswitch
                  </td>
                  <td class="project-actions text-right">
                    <a class="btn btn-info btn-sm" href="{{ route('user_edit',$user->id) }}">
                      <i class="fas fa-pencil-alt">
                      </i>
                      {{ __("edit") }}
                    </a>
                    <form action="{{ route('user_delete',$user->id) }}" method="post">
                      @method('DELETE')
                      @csrf
                      <button class="btn btn-danger btn-sm" href="#">
                        <i class="fas fa-trash">
                        </i>
                        {{ __("delete") }}
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>{{ __("id") }}</th>
                  <th>{{ __("name") }}</th>
                  <th>{{ __("username") }}</th>
                  <th>{{ __("gender") }}</th>
                  <th>{{ __("identy_number") }}</th>
                  <th>{{ __("driving_license") }}</th>
                  <th>{{ __("bike_license") }}</th>
                  <th>{{ __("rank") }}</th>
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
      "paging": false,
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