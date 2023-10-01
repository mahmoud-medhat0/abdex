@extends('layouts.parent')
@section('width')
{{-- style="height: auto;width: max-content;" --}}
@endsection
@section('title')
{{ __("orders_archieve") }}
@endsection
@section('css')
<style>
    /* #example1 tr th>input{
        width: 20%;
      } */
    #example1 {
        width: 15%;
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
        <input class="btn btn-primary w-15" type="button" onclick="selects();" value="{{ __("select_all") }}">
        <input class="btn btn-info w-15" type="button" onclick="deSelect();" value="{{ __("deselect_all") }}">
        <a href="{{ route('neworders') }}" class="btn btn-primary w-15 ">{{ __("orders_add_new") }}</a>
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card overflow-auto">
                    <div class="card-header ">
                        <h3 class="card-title">{{ __("orders_all_data") }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('orderchecks') }}" method="post" id="checks">
                            @csrf
                            <input type="hidden" name="method" id="method" value="">
                            <input type="hidden" name="value" id="value" value="">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;"><input type="checkbox" class="selectall" id="all-check"
                                            name="checkbox">All</th>
                                        <th id="id">{{ __("order_id") }}</th>
                                        <th id="companyname">{{ __("company") }}</th>
                                        <th id="id_police">{{ __("id_police") }}</th>
                                        <th id="name_client">{{ __("name_client") }}</th>
                                        <th id="phone">{{ __("phone") }}</th>
                                        <th id="phone2">{{ __("phone2") }}</th>
                                        <th id="delegate_name">{{ __("delegate_name") }}</th>
                                        <th id="address">{{ __("address") }}</th>
                                        <th id="cost">{{ __("cost") }}</th>
                                        <th id="date">{{ __("date") }}</th>
                                        <th>{{ __("order_created_at") }}</th>
                                        <th id="notes">{{ __("notes") }}</th>
                                        <th>{{ __("special_intructions") }}</th>
                                        <th>{{ __("company_special_intructions") }}</th>
                                        <th id="nameproduct">{{ __("name_product") }}</th>
                                        <th id="sender">{{ __("sender") }}</th>
                                        <th id="weghit">{{ __("weghit") }}</th>
                                        <th id="statename">{{ __("state_order") }}</th>
                                        <th id="cause_return">{{ __("cause_return") }}</th>
                                        <th>{{ __("cause_delay") }}</th>
                                        <th>{{ __("delay_date") }}</th>
                                        <th>{{ __("delegate_supply") }}</th>
                                        <th>{{ __("delegate_supply_date") }}</th>
                                        <th>{{ __("company_supply") }}</th>
                                        <th>{{ __("company_supply_date") }}</th>
                                        <th>{{ __("locate_order") }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
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

        function agent() {
            var x = document.getElementById("method").value = "agent";
            var v = document.getElementById("agents").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }

        function delegate() {
            var x = document.getElementById("method").value = "delegate";
            var v = document.getElementById("delegates").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }
</script>
<script>
    $(document).ready(function () {
        var table = $('#example1').DataTable({
            responsive: false,
            lengthChange: true,
            autoWidth: false,
            orderCellsTop: true,
            processing: true,
            language: {
                processing: "<div id='custom-processing' class='text-center'><div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div><p class='mt-2'>Processing...</p></div>"
            },
            serverSide: true,
            paging:true,
            buttons: ["excel", "copy", "colvis"],
            dom: 'Bfrtip',
            ajax: {
                url :'{{ route('archieve_ajax') }}',
                data: function (data) {
                    // Add filters to the DataTables request data
                    data.id = $('#filterid').val();
                    data.printed = $('#filterprinted').val();
                    data.companyname = $('#filtercompanyname').val();
                    data.statename = $('#filterstatename').val();
                    data.id_police = $('#filterid_police').val();
                    data.name_client = $('#filtername_client').val();
                    data.delegatename = $('#filterdelegate_name').val();
                    data.phone = $('#filterphone').val();
                    data.phone2 = $('#filterphone2').val();
                    data.date = $('#date').val();
                    data.createdat = $('#createdat').val();
                    data.address = $('#filteraddress').val();
                    data.cost = $('#filtercost').val();
                    data.notes = $('#filternotes').val();
                    data.nameproduct = $('#filternameproduct').val();
                    data.sender = $('#filtersender').val();
                    data.weghit = $('#filterweghit').val();
                    data.cause_return = $('#filtercause_return').val();
                }
            },
            columns: [
                {data: null,
                    render: function (data, type, full, meta) {
                        return '<input type="checkbox" class="checkbox" name="checkbox-'+data.id+'" value="' + data.id + '">';
                        }
                },
                { data: null, name: 'id',
                    render: function (data, type, full, meta) {
                    var editLink = '<a class="btn btn-info btn-sm" href="/orderedit/' + data.id + '">' + data.id + '</a>';
                    var printLink = '<a href="/orders/print/' + data.id + '" class="btn btn-primary hidden-print"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" /><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg></a>';
                    return editLink + ' ' + printLink;}
                },
                {data:'companyname' ,name:'company name'},
                {data:'id_police', name:'id_police'},
                {data:'name_client',name:'name_client'},
                {data:'phone',name:'phone'},
                {data:'phone2',name:'phone2'},
                {data:'delegatename',name:'delegate name'},
                {data:null,name:'address',render:function(data){
                    return '<textarea disabled="" type="text" name="" id="">'+data.address+'</textarea>';
                }},
                {data:'cost',name:'cost'},
                {data:'date',name:'date'},
                {data:'created_atca', name: 'created_at' },
                {data:'notes',name:'notes'},
                {data:'special_intructions',name:'special_intructions'},
                {data:'special_intructions2',name:'special_intructions2'},
                {data:'name_product',name:'name_product'},
                {data:'sender',name:'sender'},
                {data:'weghit',name:'weghit'},
                {data:'statename',name:'statename'},
                {data:'causereturn',name:'causereturn'},
                {data:'delaycause',name:'delaycause'},
                {data:null,name:'delay_date',
                render:function(data){
                    return data.delay_date ? data.delay_date :'فراغات';
                }
                },
                {data:null,
                render:function(data){
                    return data.delegate_supply=='1'? 'مورد':'لم يورد';
                }},
                {data:'delegatesupplied',name:'delegatesupplied'},
                {data:null,
                    render:function(data){
                        return data.company_supply=='1'? 'مورد':'لم يورد';
                }},
                {data:'companysupplied',name:'companysupplied'},
                {data:'orderlocatename',name:'orderlocatename'},
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('record-id', data.id);
            },
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            initComplete: function () {
                // Customize the action buttons here
                table.buttons().container()
                    .appendTo('#example1 .col-md-6:eq(0)');
            }
        });
        $('#apply-filters').on('click', function () {
            table.ajax.reload();
        });
        $('#example1 thead tr').clone(true).appendTo('#example1 thead').addClass('filters custom-width-th');
        // Apply DataTables search and filtering behavior to the cloned filters
        $('#example1 thead tr:eq(1) th').each(function (i) {
            var title = $(this);
            $(this).html('<input class="form-control form-control-sm" type="text" id="filter'+title.attr('id')+'" name="'+title+'" placeholder="Search ' + title.text() + '" />');
                $('input', this).on('keyup change', function () {
                    if($(this).val()!=null){
                    table.ajax.reload();
                    }
                });
        });
        $('#example1 thead tr.filters th').removeClass('sorting sorting_asc sorting_desc sorting_disabled');
    });
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
</script>
@endsection
