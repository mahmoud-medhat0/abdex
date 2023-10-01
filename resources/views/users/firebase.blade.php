{{-- {{ dd($salaries) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("delegates_devices_all") }}
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
                        <h3 class="card-title">{{ __("control_app_delegates") }}</h3>

                        <a onclick="active()" style="
                  position: absolute;
                  right: 15%;
                  width: auto;
                  bottom: px;" class="btn btn-primary btn-block">{{ __("active") }}</a>
                        <br>
                        <a onclick="deactive()" style="
                          position: absolute;
                          right: 2%;
                          width: auto;
                          bottom: -3px;
            " class="btn btn-primary btn-block">{{ __("deactive") }}</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('firebase_store') }}" method="post" id="checks">
                            @csrf
                            <input type="hidden" name="method" id="active" value="">
                            <input class="btn btn-primary" type="button" onclick="selects();" value="Select All">
                            <input class="btn btn-info" type="button" onclick="deSelect();" value="deSelect All">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>-</th>
                                        <th>uid</th>
                                        <th>{{ __("username") }}</th>
                                        <th>{{ __("active_status") }}</th>
                                    </tr>
                                </thead>
                                <tbody>@isset($users)
                                    @foreach ($users as $user)
                                    <tr>
                                        <td><input type="checkbox" name="{{'checkbox-'. $user['uid']}}"
                                                value="{{$user['uid']}}"></td>
                                        <td>{{ $user['uid'] }}</td>
                                        <td>{{ $user['username'] }}</td>
                                        <td>
                                            @if ($user['active']=='1')
                                            {{ '✅' }}
                                            @elseif ($user['active']=='0')
                                            {{ '❌' }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>-</th>
                                        <th>uid</th>
                                        <th>{{ __("username") }}</th>
                                        <th>{{ __("active_status") }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
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
<script>
    function active() {
    document.getElementById("active").value='1';
    document.getElementById("checks").submit();
  }
  function deactive() {
    document.getElementById("active").value='0';
    document.getElementById("checks").submit();
  }
</script>
<script>
    function selects() {
    @isset($users)
    @foreach ($users as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $user['uid'] }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = true;
          }
      @endforeach
    @endisset

  }

  function deSelect() {
    @isset($users)
    @foreach ($users as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $user['uid'] }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = false;
          }
      @endforeach

    @endisset
  }
</script>
@endsection
