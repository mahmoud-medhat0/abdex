@extends('layouts.company')
@section('width')
@endsection
@section('title')
{{ __("orders_all") }}
@endsection
@section('css')
<style>
    #example1 {
        width: 15%;
    }
    #example1 tbody tr:hover{
        background-color: #0a8f94;
    }
</style>
@endsection
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('missing')!=null)
@foreach (session('missing') as $message)
<div class="alert alert-danger">
    {{ $message }}
</div>
@endforeach
@endif
@if (session('error')!=null)
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<section class="content">
    <div class="container-fluid">
        <div class="col">
            <div class="form-group" data-select2-id="">
                <label>{{ __("actions") }}</label>
                <select name="destroys" id="destroys" class="form-control select2bs4 select2-hidden-accessible"
                    style="width: 100%;" data-select2-id="" tabindex="-1" aria-hidden="true">
                    <option value="">none</option>
                    <option value="1" >{{__("delete")}}</option>
                    <option value="2">{{ __("print") }}</option>
                </select>
                <button type="button" onclick="destroy()"
                    class="btn btn-block btn-success">{{ __("exec") }}</button>
            </div>
        </div>
        <input class="btn btn-primary w-15" type="button" onclick="selects();" value="{{ __("select_all") }}">
        <input class="btn btn-info w-15" type="button" onclick="deSelect();" value="{{ __("deselect_all") }}">
        <a href="{{ route('companyaddmanual') }}" class="btn btn-primary w-15 ">{{ __("orders_add_new") }}</a>
        <a href="{{ route('stamp') }}" class="btn btn-primary  w-15">{{ __("download_stamp") }}</a>
        <div class="form-group w-15" bis_skin_checked="1">
            <form action="{{ route('orderssearch') }}" class="form-group" method="post" enctype="multipart/form-data">
                @csrf
                <label for="exampleInputFile">{{ __("sheet") }}</label>
                <div class="input-group w-15" bis_skin_checked="1">
                    <div class="custom-file w-15" bis_skin_checked="1">
                        <input name="sheet" value="{{ old('sheet') }}" type="file" id="exampleInputFile"
                            accept=".xlsx,.xls">
                        <label class="custom-file-label w-15" for="exampleInputFile">{{ __("choose_file") }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary ml-2">{{ __("search") }}</button>
                </div>
                @error('sheet')
                <div class="text-danger font-weight-bold">*{{ $message }}</div>
                @enderror
            </form>
        </div>
        <div id="printBar" class="pl-2"></div>

    </div>
    <!-- /.container-fluid -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card overflow-auto">
                    <div class="card-header ">
                        <h3 class="card-title">{{ __("orders_all_data") }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('companychecks') }}" method="post" id="checks">
                            @csrf
                            <input type="hidden" name="method" id="method" value="">
                            <input type="hidden" name="value" id="value" value="">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;"><input type="checkbox" class="selectall" id="all-check"
                                            name="checkbox">All</th>
                                        <th>{{ __("order_id") }}</th>
                                        <th>{{ __("company") }}</th>
                                        <th>{{ __("id_police") }}</th>
                                        <th>{{ __("name_client") }}</th>
                                        <th>{{ __("phone") }}</th>
                                        <th>{{ __("phone2") }}</th>
                                        <th>{{ __("center") }}</th>
                                        <th>{{ __("address") }}</th>
                                        <th>{{ __("cost") }}</th>
                                        <th>{{ __("date") }}</th>
                                        <th>{{ __("order_created_at") }}</th>
                                        <th>{{ __("notes") }}</th>
                                        <th>{{ __("special_intructions") }}</th>
                                        <th>{{ __("company_special_intructions") }}</th>
                                        <th>{{ __("name_product") }}</th>
                                        <th>{{ __("sender") }}</th>
                                        <th>{{ __("weghit") }}</th>
                                        <th>{{ __("premissions") }}</th>
                                        <th>{{ __("state_order") }}</th>
                                        <th>{{ __("cause_return") }}</th>
                                        <th>{{ __("cause_delay") }}</th>
                                        <th>{{ __("delay_date") }}</th>
                                        <th>{{ __("company_supply") }}</th>
                                        <th>{{ __("company_supply_date") }}</th>
                                        <th>{{ __("gps_delivered") }}</th>
                                        <th>{{ __("identy_number_order") }}</th>
                                        <th>{{ __("action") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (session()->get('orders')!=null)
                                    @foreach (session()->get('orders') as $order)
                                    <tr>
                                        <?php foreach ($police_duplicate as $police_duplicate1 => $value) {
                                            if ($order->id_police == $value) {
                                             $duplicate[$order->id] = 0;
                                                    }
                                                    }
                                                    ?>
                                        <td><input type="checkbox" name="{{ 'checkbox-' . $order->id }}"
                                                value="{{ $order->id }}"></td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('companyorderedit', $order->id) }}">{{ $order->id }}</a>
                                            <a href="{{ route('print', $order->id) }}"
                                                class="btn btn-primary hidden-print"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                    fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                    <path
                                                        d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                </svg></a>
                                        </td>
                                        <td>
                                            @foreach ($companies as $company)
                                            @if ($order->id_company == $company->id)
                                            {{ $company->name }}
                                            @endif
                                            @endforeach
                                        </td>
                                        <td @isset($duplicate[$order->id])
                                            {{ 'style=background-color:red;' }}
                                            @endisset>
                                            {{ $order->id_police }}
                                        </td>
                                        <td>{{ $order->name_client }}</td>
                                        <td>{{ $order->phone }}</td>
                                        <td>{{ $order->phone2 }}</td>
                                        <td>
                                            @if (isset($order->center_id))
                                            @foreach ($centers as $center)
                                            @if ($center->id == $order->center_id)
                                            {{ $center->center_name }}
                                            @endif
                                            @endforeach
                                            @else
                                            {{ 'فراغات' }}
                                            @endif
                                        </td>
                                        <td>
                                            <textarea disabled type="text" name=""
                                                id="">{{ $order->address }}</textarea>
                                        </td>
                                        <td>{{ $order->cost }}</td>
                                        <td>{{ $order->date }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>{{ $order->notes }}</td>
                                        <td>{{ $order->special_intructions }}</td>
                                        <td>{{ $order->special_intructions2 }}</td>
                                        <td>{{ $order->name_product }}</td>
                                        <td>{{ $order->sender }}</td>
                                        <td>{{ $order->weghit }}</td>
                                        <td>{{ $order->open }}</td>
                                        <td>
                                            @if ($order->status_id == null)
                                            {{ 'فراغات' }}
                                            @else
                                            @foreach ($states as $state)
                                            @if ($state->id == $order->status_id)
                                            {{ $state->state }}
                                            @endif
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->cause_id == null)
                                            {{ 'فراغات' }}
                                            @else
                                            @foreach ($causes as $cause)
                                            @if ($cause->id == $order->cause_id)
                                            {{ $cause->cause }}
                                            @endif
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->delay_id == null)
                                            {{ 'فراغات' }}
                                            @else
                                            @foreach ($causes as $cause)
                                            @if ($cause->id == $order->delay_id)
                                            {{ $cause->cause }}
                                            @endif
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->delay_date ==null)
                                            {{ 'فراغات' }}
                                            @else
                                            {{ $order->delay_date }}
                                            @endif
                                        </td>
                                        <td>
                                            @switch($order->company_supply)
                                            @case('0')
                                            {{ 'لم يتم التوريد' }}
                                            @break

                                            @case('1')
                                            {{ 'تم توريدة' }}
                                            @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if ($order->company_supply_date == null)
                                            {{ 'لم يتم التعيين بعد' }}
                                            @else
                                            {{ $order->company_supply_date }}
                                            @endif
                                        </td>
                                        <td>
                                            @isset($order->gps_delivered)
                                            <a href="{{ 'https://www.google.com/maps/search/'.$order->gps_delivered }}"
                                                target="_blank">{{ __('view_map') }}</a>
                                            @endisset
                                        </td>
                                        <td>{{ $order->identy_number }}</td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-danger btn-sm"
                                                href="{{ route('order_delete', $order->id) }}">
                                                <i class="fas fa-trash">
                                                </i>
                                                {{ __("delete") }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 20%;"><input type="checkbox" class="selectall" id="all-check"
                                            name="checkbox">All</th>
                                        <th>{{ __("order_id") }}</th>
                                        <th>{{ __("company") }}</th>
                                        <th>{{ __("id_police") }}</th>
                                        <th>{{ __("name_client") }}</th>
                                        <th>{{ __("phone") }}</th>
                                        <th>{{ __("phone2") }}</th>
                                        <th>{{ __("center") }}</th>
                                        <th>{{ __("address") }}</th>
                                        <th>{{ __("cost") }}</th>
                                        <th>{{ __("date") }}</th>
                                        <th>{{ __("order_created_at") }}</th>
                                        <th>{{ __("notes") }}</th>
                                        <th>{{ __("special_intructions") }}</th>
                                        <th>{{ __("company_special_intructions") }}</th>
                                        <th>{{ __("name_product") }}</th>
                                        <th>{{ __("sender") }}</th>
                                        <th>{{ __("weghit") }}</th>
                                        <th>{{ __("premissions") }}</th>
                                        <th>{{ __("state_order") }}</th>
                                        <th>{{ __("cause_return") }}</th>
                                        <th>{{ __("cause_delay") }}</th>
                                        <th>{{ __("delay_date") }}</th>
                                        <th>{{ __("company_supply") }}</th>
                                        <th>{{ __("company_supply_date") }}</th>
                                        <th>{{ __("gps_delivered") }}</th>
                                        <th>{{ __("identy_number_order") }}</th>
                                        <th>{{ __("action") }}</th>
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
</section>
@endsection
@section('js')
<script>
    function state() {
            var x = document.getElementById("method").value = "state";
            var v = document.getElementById("states").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }

        function cause() {
            var x = document.getElementById("method").value = "cause";
            var v = document.getElementById("causes").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }

        function locate() {
            var x = document.getElementById("method").value = "locate";
            var v = document.getElementById("locates").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }

        function destroy() {
            var x = document.getElementById("method").value = "destroy";
            var v = document.getElementById("destroys").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }
        function center() {
            var x = document.getElementById("method").value = "center";
            var v = document.getElementById("centers").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }
</script>
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
        table.buttons().container().appendTo('#printBar');
</script>
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function() {
            bsCustomFileInput.init();
        });
</script>
<script>
    $('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});
    function selects() {
        @if (session()->get('orders')!=null)
        @foreach (session()->get('orders') as $order)
                var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
                for (var i = 0; i < ele.length; i++) {
                    if (ele[i].type == 'checkbox')
                        ele[i].checked = true;
                }
            @endforeach
        @endif
        }

        function deSelect() {
            @if (session()->get('orders')!=null)
            @foreach (session()->get('orders') as $order)
                var ele = document.getElementsByName('{{ 'checkbox-' . $order->id }}');
                for (var i = 0; i < ele.length; i++) {
                    if (ele[i].type == 'checkbox')
                        ele[i].checked = false;
                }
            @endforeach
            @endif
        }
        document.addEventListener("DOMContentLoaded", function() {
            // Function to update the count and print it in the console
            function updateCount() {
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
            }

            // Initially, update the count based on the existing checked checkboxes
            updateCount();

            // Event listener for checkbox change
            document.addEventListener("change", function(event) {
              var target = event.target;
              if (target.matches("input[type='checkbox']")) {
                // Update the count whenever a checkbox is checked or unchecked
                updateCount();
              }
            });
          });
</script>
@endsection
