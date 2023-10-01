@extends('layouts.parent')
@section('title')
{{__('orders_delevired_company').' '.$companyname }}
@endsection
@section('content')
<form action="{{ route('orderssearch') }}" class="form-group" method="post" enctype="multipart/form-data">
    @csrf
    <div class="card-body" bis_skin_checked="1">
        <div class="row" bis_skin_checked="1">
            <div class="form-group col-md-6" bis_skin_checked="1">
                <label for="exampleInputFile">{{ __("sheet") }}</label>
                <div class="input-group" bis_skin_checked="1">
                    <div class="custom-file" bis_skin_checked="1">
                        <input name="sheet" value="{{ old('sheet') }}" type="file" id="exampleInputFile"
                            accept=".xlsx,.xls">
                        <label class="custom-file-label" for="exampleInputFile">{{ __("choose_file") }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __("search") }}</button>
                </div>
                @error('sheet')
                <div class="text-danger font-weight-bold">*{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</form>
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
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <div class="card overflow-auto">

                    <div class="card-header">
                            <h3 class="card-title">{{ __("orders_all_data") }}</h3>
                            <a href="{{ route('neworders') }}" class="btn btn-primary w-15 ">{{ __("orders_add_new") }}</a>
                            <a href="{{ route('stamp') }}" class="btn btn-primary  w-15">{{ __("download_stamp") }}</a>
                    </div>
                </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('companies_supply') }}" method="post" id="checks">
                            @csrf
                            <label for="payed">{{ __('payed') }}</label>
                            <input required="required" type="number" onclick="payed()" name="payed" value="" id="payed">
                            <input type="button" class="btn btn-flex btn-success" onclick="myFunction()" value="توريد">
                            <input type="hidden" name="name" value="{{ $companyname }}">
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input class="btn btn-flex btn-primary w-15" type="button" onclick="selects();"
                                value="{{ __("select_all") }}">
                            <input class="btn btn-flex btn-info w-15" type="button" onclick="deSelect();"
                            value="{{ __("deselect_all") }}">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="checkbox[]"></th>
                                        <th>{{ __('order_id') }}</th>
                                        <th>{{ __('company') }}</th>
                                        <th>{{ __('id_police') }}</th>
                                        <th>{{ __('name_client') }}</th>
                                        <th>{{ __('phone') }}</th>
                                        <th>{{ __('phone2') }}</th>
                                        <th>{{ __('address')}}</th>
                                        <th>{{ __('cost') }}</th>
                                        <th>{{ __('companies_commission') }}</th>
                                        <th>{{ __('total') }}</th>
                                        <th>{{ __('state_order') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $total=0;
                                        $total1=0;
                                        $total2=0;
                                    ?>
                                    @foreach (session()->get('orders') as $order)
                                    <tr>
                                        <td><input type="checkbox" name="{{'checkbox-'.$order->id }}"
                                                value="{{$order->id}}"></td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('orderedit',$order->id) }}">{{ $order->id }}</a>
                                        </td>
                                        <td>{{ $order->company_name }}</td>
                                        <td>{{ $order->id_police }}</td>
                                        <td>{{ $order->name_client }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order->phone2 }}</td>
                                        <td><textarea disabled>{{ $order->address }}</textarea></td>
                                        <td><input type="text" id="cost_{{ $order->id }}" disabled value="{{ $order->cost }}">
                                            <?php $total+=$order->cost;?>
                                        </td>
                                        <td><input type="number" name="commission_{{ $order->id }}" id="commission">
                                        </td>
                                        <td><input disabled id="total_{{ $order->id }}" >
                                        </td>
                                        <td>{{ $order->state }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>@isset($orders[0]->company_name)
                                            {{ $orders[0]->company_name}}
                                            @endisset
                                        </td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>{{ $total }}</td>
                                        <td><input id="sum" type="text" disabled value="{{ $total1 }}">
                                            <input id="sum" type="hidden" name="commission" value="{{ $total1 }}">
                                        </td>
                                        <td><inpu type="text" disabled name="total" value="{{ $total }}">
                                            <input id="total" type="hidden" name="total" value="{{ $total }}">
                                        </td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><input type="checkbox" name="checkbox[]"></th>
                                        <th>{{ __('order_id') }}</th>
                                        <th>{{ __('company') }}</th>
                                        <th>{{ __('id_police') }}</th>
                                        <th>{{ __('name_client') }}</th>
                                        <th>{{ __('phone') }}</th>
                                        <th>{{ __('phone2') }}</th>
                                                                                <th>{{ __('address') }}</th>
                                        <th>{{ __('cost') }}</th>
                                        <th>{{ __('companies_commission') }}</th>
                                        <th>{{ __('total') }}</th>
                                        <th>{{ __('state_order') }}</th>
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
    $('#example1 thead tr').clone(true).appendTo('#example1 thead');
            // $('#example1 thead tr').clone(true).appendTo('#example1 thead').css('display','none');
            $('#example1 thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control form-control-sm" placeholder="' +
                    title + '" />');
                $('input', this).on('keyup click change', function(e) {
                    e.stopPropagation();
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            })
        var table = $("#example1").DataTable({
            responsive: false,
            lengthChange: true,
            autoWidth: false,
            orderCellsTop: true,
            paging:false,
            buttons: ["excel","copy", "colvis"],
        });
</script>
<script>
    function myFunction() {
    document.getElementById("checks").submit();
  }
  // function payed(){
    var x = document.getElementById("payed").value="{{ $total2 }}";
  // }
</script>
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function () {
          bsCustomFileInput.init();
        });
</script>
<script>
    $( document ).ready(function() {
        @if (session()->get('orders')!=null)
        @foreach (session()->get('orders') as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = true;
          }
        @endforeach
        @endif
});
$(document).ready(function() {
    if ("{{ session()->get('orders') }}" !== null) {
        var count = 0;
        @foreach (session()->get('orders') as $order)
            var ele = document.getElementsByName("{{ 'checkbox-' . $order->id }}");
            for (var i = 0; i < ele.length; i++) {
                if (ele[i].type === 'checkbox') {
                    ele[i].checked = true;
                    count++;
                }
            }
        @endforeach

        console.log('Checked checkboxes count:', count);
    }
});

  function selects() {
      @foreach (session()->get('orders') as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = true;
          }
      @endforeach
  }

  function deSelect() {
      @foreach (session()->get('orders') as $order)
          var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
          for (var i = 0; i < ele.length; i++) {
              if (ele[i].type == 'checkbox')
                  ele[i].checked = false;
          }
      @endforeach
  }
</script>
<script>
    const inputNumbers = document.querySelectorAll('#commission');
    const resultElement = document.getElementById('result');

    // Function to calculate the sum of all input values
    function calculateSum() {
      let sum = 0;
      inputNumbers.forEach(function(input) {
        sum += parseFloat(input.value) || 0;
      });
      return sum;
    }
    // Event listener for input changes
    inputNumbers.forEach(function(input) {
      input.addEventListener('input', function() {
        @foreach (session()->get('orders') as $order)
        document.getElementById('total_'+{{$order->id}}).value = document.getElementById('cost_'+{{$order->id}}).value - document.getElementsByName('commission_'+{{$order->id}})[0].value;
        @endforeach
        const sum = calculateSum();
        document.querySelectorAll('#sum').forEach(function(e){
            e.value = sum;
        });
        document.getElementById('payed').value = {{ $total }} - sum;
        document.getElementById('total').value ={{ $total }} - sum;
        document.getElementById('totaltotal').value ={{ $total }} - sum;
      });
    });
  </script>
@endsection
