@extends('layouts.parent')
@section('title')
{{ __('supplies_hold_all') }}
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
            <h3 class="card-title">{{ __('supplies_hold_data') }}</h3>

            <a onclick="myFunction()" style="
                  position: absolute;
                  right: 2%;
                  width: auto;
                  bottom: 3px;
              " class="btn btn-primary btn-block">{{ __('confirm') }}</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="{{ route('confirm_supplies') }}" method="post" id="checks">
            @csrf
            <input class="btn btn-primary" type="button" onclick="selects();" value="{{ __('select_all') }}">
            <input class="btn btn-info" type="button" onclick="deSelect();" value="{{ __('deselect_all') }}">
            <input disabled id="total" class="btn btn-primary" value="">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>-</th>
                  <th>{{ __('supply_id') }}</th>
                  <th>{{ __('supply_created') }}</th>
                  <th>{{ __('payed') }}</th>
                  <th>{{ __('supply_confirmed') }}</th>
                </tr>
              </thead>
              <tbody>
                <?php $total = 0;?>
                @foreach ($supplies as $supply)
                <tr>
                  <td><input type="checkbox" name="{{'checkbox-'.$supply->id }}" value="{{$supply->id}}"></td>
                  <td>{{ $supply->id }}</td>
                  <td>{{ $supply->name }}</td>
                  <td>{{ $supply->payed }}</td>
                  <?php $total+=$supply->payed;?>
                  <td>
                    @if ($supply->confirm=='1')
                    {{ '✅' }}
                    @elseif ($supply->confirm=='0')
                    {{ '❌' }}
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>-</th>
                  <th>{{ __('supply_id') }}</th>
                  <th>{{ __('supply_created') }}</th>
                  <th>{{ __('payed') }}</th>
                  <th>{{ __('supply_confirmed') }}</th>
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
  function myFunction() {
    document.getElementById("checks").submit();
  }
  var x = document.getElementById("total").value="{{ __('total').' : '.$total }}";
</script>
<script>
  function selects() {
      @foreach ($supplies as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = true;
          }
      @endforeach
  }

  function deSelect() {
      @foreach ($supplies as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = false;
          }
      @endforeach
  }
</script>
@endsection
