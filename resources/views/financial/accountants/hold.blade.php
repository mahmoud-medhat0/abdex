@extends('layouts.parent')
@section('title')
{{ __('Account_Statements_hold') }}
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
            <form action="{{ route('accountants_hold_store') }}" method="post" id="checks">
            @csrf
            <input class="btn btn-primary" type="button" onclick="selects();" value="{{ __('select_all') }}">
            <input class="btn btn-info" type="button" onclick="deSelect();" value="{{ __('deselect_all') }}">
            <input disabled id="total" class="btn btn-primary" value="">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>-</th>
                  <th>{{ __('statement_id') }}</th>
                  <th>{{ __('statement_person') }}</th>
                  <th>{{ __('in') }}</th>
                  <th>{{ __('out') }}</th>
                  <th>{{ __('statement_confirmed') }}</th>
                  <th>{{ __('statement_user') }}</th>
                  <th>{{ __('statement_created') }}</th>
                </tr>
              </thead>
              <tbody>
                <?php $total = 0;
                $total2 = 0;?>
                @foreach ($accountants as $accountant)
                <tr>
                  <td><input type="checkbox" name="{{'checkbox-'.$accountant->id }}" value="{{$accountant->id}}"></td>
                  <td>{{ $accountant->id }}</td>
                  <td>{{ $accountant->person_name }}</td>
                  <td>{{ $accountant->creditor }}</td>
                  <td>{{ $accountant->debtor }}</td>
                  <?php $total+=$accountant->creditor;
                        $total2+=$accountant->debtor;?>
                  <td>
                    @if ($accountant->confirm=='1')
                    {{ '✅' }}
                    @elseif ($accountant->confirm=='0')
                    {{ '❌' }}
                    @endif
                  </td>
                  <td>{{ $accountant->name }}</td>
                  <td>{{ $accountant->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>-</th>
                  <th>{{ __('statement_id') }}</th>
                  <th>{{ __('statement_person') }}</th>
                  <th>{{ __('in') }}</th>
                  <th>{{ __('out') }}</th>
                  <th>{{ __('statement_confirmed') }}</th>
                  <th>{{ __('statement_user') }}</th>
                  <th>{{ __('statement_created') }}</th>
                </tr>
              </tfoot>
            </table>
            <div id="hiddenInputsContainer"></div>
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
  var x = document.getElementById("total").value="{{ __('total').' : '.$total-$total2 }}";
</script>
<script>
  function selects() {
      @foreach ($accountants as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = true;
          }
      @endforeach
  }

  function deSelect() {
      @foreach ($accountants as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = false;
          }
      @endforeach
  }
</script>
@endsection
