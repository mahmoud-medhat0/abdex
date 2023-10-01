@php
    use Carbon\Carbon;
@endphp
@extends('layouts.parent')
@section('title')
    {{ __('orders_all') }}
@endsection
@section('css')
    <style>
        #example1 {
            width: 15%;
        }

        #example1 tbody tr:hover {
            background-color: #0a8f94;
        }

        .dataTables_processing {
            margin-top: 200px;
        }
    </style>
@endsection
@section('css')
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('missing') != null)
        @foreach (session('missing') as $message)
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @endforeach
    @endif
    @if (session('error') != null)
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
        <div class="container-fluid ">
            <div class="row">
                <div class="col">
                    <div class="form-group" data-select2-id="">
                        <label>{{ __('companies_orders') }}</label>
                        <select class="form-control select2bs4 select2-hidden-accessible" id="company"
                            style="width: 100%;" aria-hidden="true">
                            <option value="">{{ __('companies_orders') }}</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company')
                            <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                        <button type="button" onclick="company()"
                            class="btn btn-block btn-success">{{ __('exec') }}</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group" data-select2-id="">
                        <label>{{ __('states_orders') }}</label>
                        <select class="form-control select2bs4 select2-hidden-accessible" id="states"
                            style="width: 100%;" aria-hidden="true">
                            <option value="">{{ __('states_orders') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}">{{ $state->state }}</option>
                            @endforeach
                        </select>
                        @error('states')
                            <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                        <button type="button" onclick="state()"
                            class="btn btn-block btn-success">{{ __('exec') }}</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group" data-select2-id="">
                        <label>{{ __('causes_return') }}</label>
                        <select name="causes" id="causes" class="form-control select2bs4 select2-hidden-accessible"
                            style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                            <option value="">{{ __('causes_return') }}</option>
                            @foreach ($causes as $cause)
                                <option value="{{ $cause->id }}">{{ $cause->cause }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="cause()"
                            class="btn btn-block btn-success ">{{ __('exec') }}</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group" data-select2-id="">
                        <label>{{ __('locate_order') }}</label>
                        <select name="locates" id="locates" class="form-control select2bs4 select2-hidden-accessible"
                            style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                            <option value="">{{ __('locate_order') }}</option>
                            <option value="0">لم يتم الاستلام بعد</option>
                            <option value="1">بالمقر</option>
                            <option value="2">مع المندوب</option>
                            <option value="3">تم الرد للراسل</option>
                            <option value="4">مطلوب من المندوب</option>
                        </select>
                        <button type="button" onclick="locate()"
                            class="btn btn-block btn-success ">{{ __('exec') }}</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group" data-select2-id="">
                        <label>{{ __('delegate_change') }}</label>
                        <select name="delegates" id="delegates" class="form-control select2bs4 select2-hidden-accessible"
                            style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option>{{ __('delegate_change') }}</option>
                            @foreach ($delegates as $delegate)
                                <option value="{{ $delegate->id }}">{{ $delegate->name }}</option>
                            @endforeach
                        </select>
                        @error('delegates')
                            <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                        <button type="button" onclick="delegate()"
                            class="btn btn-block btn-success">{{ __('exec') }}</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group" data-select2-id="">
                        <label>{{ __('actions') }}</label>
                        <select name="destroys" id="destroys" class="form-control select2bs4 select2-hidden-accessible"
                            style="width: 100%;" data-select2-id="" tabindex="-1" aria-hidden="true">
                            <option value="">none</option>
                            <option value="1">{{ __('delete') }}</option>
                            <option value="2">{{ __('print') }}</option>
                            <option value="3">{{ __('To Archieve') }}</option>
                        </select>
                        <button type="button" onclick="destroy()"
                            class="btn btn-block btn-success">{{ __('exec') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <input class="btn btn-primary w-15" type="button" onclick="selects();" value="{{ __('select_all') }}">
            <input class="btn btn-info w-15" type="button" onclick="deSelect();" value="{{ __('deselect_all') }}">
            <a href="{{ route('neworders') }}" class="btn btn-primary w-15 ">{{ __('orders_add_new') }}</a>
            <a href="{{ route('stamp') }}" class="btn btn-primary  w-15">{{ __('download_stamp') }}</a>
            <div class="form-group w-15" bis_skin_checked="1">
                <form action="{{ route('orderssearch') }}" class="form-group" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <label for="exampleInputFile">{{ __('sheet') }}</label>
                    <div class="input-group w-15" bis_skin_checked="1">
                        <div class="custom-file w-15" bis_skin_checked="1">
                            <input name="sheet" value="{{ old('sheet') }}" type="file" id="exampleInputFile"
                                accept=".xlsx,.xls">
                            <label class="custom-file-label w-15" for="exampleInputFile">{{ __('choose_file') }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary ml-2">{{ __('search') }}</button>
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
                        <div class="card-header">
                            <h3 class="card-title">{{ __('orders_all_data') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="container">
                            <center>
                                <b>Filter By Delegate Delivered</b>
                            </center>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="startdate" class="form-label">Delegate Delivered From:</label>
                                        <input type="datetime-local" id="startdate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="enddate" class="form-label">Delegate Delivered to:</label>
                                        <input type="datetime-local" id="enddate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group"
                                        style="
                                    padding-top: 9%;
                                ">
                                        <button id="apply-filters" class="btn btn-primary btn-block">Apply
                                            Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('orderchecks') }}" method="post" id="checks">
                                @csrf
                                <input type="hidden" name="method" id="method" value="">
                                <input type="hidden" name="value" id="value" value="">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%;"><input type="checkbox" class="selectall"
                                                    id="all-check" name="checkbox">All</th>
                                            <th id="id">{{ __('order_id') }}</th>
                                            <th id="printed">{{ __('print count') }}</th>
                                            <th id="companyname">{{ __('company') }}</th>
                                            <th id="id_police">{{ __('id_police') }}</th>
                                            <th id="name_client">{{ __('name_client') }}</th>
                                            <th id="phone">{{ __('phone') }}</th>
                                            <th id="phone2">{{ __('phone2') }}</th>
                                            <th id="delegate_name">{{ __('delegate_name') }}</th>
                                            <th id="delegate_delivered">{{ __('delegate_delivered') }}</th>
                                            <th id="address">{{ __('address') }}</th>
                                            <th id="cost">{{ __('cost') }}</th>
                                            <th id="date">{{ __('date') }}</th>
                                            <th id="created_at">{{ __('order_created_at') }}</th>
                                            <th id="notes">{{ __('notes') }}</th>
                                            <th id="special_inturactions">{{ __('special_intructions') }}</th>
                                            <th id="special_intructions2">{{ __('company_special_intructions') }}</th>
                                            <th id="name_product">{{ __('name_product') }}</th>
                                            <th id="sender">{{ __('sender') }}</th>
                                            <th id="weghit">{{ __('weghit') }}</th>
                                            <th id="statename">{{ __('state_order') }}</th>
                                            <th id="causereturn">{{ __('cause_return') }}</th>
                                            <th id="delaycause">{{ __('cause_delay') }}</th>
                                            <th id="delay_date">{{ __('delay_date') }}</th>
                                            <th>{{ __('delegate_supply') }}</th>
                                            <th>{{ __('delegate_supply_date') }}</th>
                                            <th>{{ __('company_supply') }}</th>
                                            <th>{{ __('company_supply_date') }}</th>
                                            <th>{{ __('identy_number_order') }}</th>
                                            <th id="orderlocate">{{ __('locate_order') }}</th>
                                            <th>{{ __('action') }}</th>
                                        </tr>
                                    </thead>
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
        $('.selectall').click(function() {
            if ($(this).is(':checked')) {
                $('div input').attr('checked', true);
            } else {
                $('div input').attr('checked', false);
            }
        });

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

        function delegate() {
            var x = document.getElementById("method").value = "delegate";
            var v = document.getElementById("delegates").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }

        function company() {
            var x = document.getElementById("method").value = "company";
            var v = document.getElementById("company").value;
            var val = document.getElementById("value").value = v;
            document.getElementById("checks").submit();
        }
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example1').DataTable({
                pageLength: 500,
                lengthMenu: [20, 50, 100, 500, 1000],
                responsive: false,
                lengthChange: true,
                autoWidth: false,
                processing: true,
                language: {
                    processing: "<div id='custom-processing' class='text-center'><div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div><p class='mt-2'>Processing...</p></div>"
                },
                serverSide: true,
                paging: true,
                buttons: ["excel", "copy", "colvis"],
                dom: 'Bfrtip',
                ajax: {
                    url: '/orders-data',
                    data: function(data) {
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
                        data.startdate = $('#startdate').val();
                        data.enddate = $('#enddate').val();
                        data.address = $('#filteraddress').val();
                        data.cost = $('#filtercost').val();
                        data.notes = $('#filternotes').val();
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, full, meta) {
                            return '<input type="checkbox" class="checkbox" name="checkbox-' + data
                                .id + '" value="' + data.id + '">';
                        }
                    },
                    {
                        data: null,
                        name: 'id',
                        render: function(data, type, full, meta) {
                            var editLink = '<a class="btn btn-info btn-sm" href="/orders/edit/' +
                                data
                                .id + '">' + data.id + '</a>';
                            var printLink = '<a href="/orders/print/' + data.id +
                                '" class="btn btn-primary hidden-print"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" /><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg></a>';
                            return editLink + ' ' + printLink;
                        }
                    },
                    {
                        data: null,
                        name: 'print_count',
                        render:function(data, type , full, meta){
                            return '<a href="/history/print/'+data.id+'" class="btn btn-info btn-sm">'+data.print_count+'</a>'
                        }
                    },
                    {
                        data: 'companyname',
                        name: 'company name'
                    },
                    {
                        data: 'id_police',
                        name: 'id_police'
                    },
                    {
                        data: 'name_client',
                        name: 'name_client'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'phone2',
                        name: 'phone2'
                    },
                    {
                        data: 'delegatename',
                        name: 'delegate name'
                    },
                    {
                        data: 'delegate_deliver',
                        name: 'delegate_deliver'
                    },
                    {
                        data: null,
                        name: 'address',
                        render: function(data) {
                            return '<textarea disabled="" type="text" name="" id="">' + data
                                .address + '</textarea>';
                        }
                    },
                    {
                        data: 'cost',
                        name: 'cost'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'created_atca',
                        name: 'created_at'
                    },
                    {
                        data: null,
                        name: 'notes',
                        render: function(data) {
                            return '<textarea disabled="" type="text" name="" id="">' + data
                                .notes + '</textarea>';
                        }
                    },
                    {
                        data: 'special_intructions',
                        name: 'special_intructions'
                    },
                    {
                        data: 'special_intructions2',
                        name: 'special_intructions2'
                    },
                    {
                        data: 'name_product',
                        name: 'name_product'
                    },
                    {
                        data: 'sender',
                        name: 'sender'
                    },
                    {
                        data: 'weghit',
                        name: 'weghit'
                    },
                    {
                        data: 'statename',
                        name: 'statename'
                    },
                    {
                        data: 'causereturn',
                        name: 'causereturn'
                    },
                    {
                        data: 'delaycause',
                        name: 'delaycause'
                    },
                    {
                        data: null,
                        name: 'delay_date',
                        render: function(data) {
                            return data.delay_date ? data.delay_date : 'فراغات';
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.delegate_supply == '1' ? 'مورد' : 'لم يورد';
                        }
                    },
                    {
                        data: 'delegatesupplied',
                        name: 'delegatesupplied'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.company_supply == '1' ? 'مورد' : 'لم يورد';
                        }
                    },
                    {
                        data: 'companysupplied',
                        name: 'companysupplied'
                    },
                    {
                        data: 'identy_number',
                        name: 'identy_number'
                    },
                    {
                        data: 'orderlocatename',
                        name: 'orderlocatename'
                    },
                    {
                        data: null,
                        name: 'action',
                        render: function(data) {
                            return '<a class="btn btn-danger btn-sm delete-record-btn" data-record-id="' +
                                data.id + '"><i class="fas fa-trash"></i>' +
                                '{{ __('delete') }}' +
                                '</a>';
                        }
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('record-id', data.id);
                },
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ],
                initComplete: function() {
                    // Customize the action buttons here
                    table.buttons().container()
                        .appendTo('#example1 .col-md-6:eq(0)');
                }
            });
            $('#apply-filters').on('click', function() {
                table.ajax.reload();
            });
            $('#example1 tbody').on('click', 'a.btn-danger', function() {
                console.log('thing');
                var recordId = $(this).data('record-id');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: `/orders/ajax/delete/${recordId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Add the CSRF token to the headers
                    },
                    success: function(response) {
                        // Handle the success response
                        console.log(response.message);
                        // Show the Bootstrap toast with the response message
                        $('#toast-message').text(response.message);
                        $('#success-toast').toast('show');
                        // Remove the record from the DataTable
                        table.row($(this).closest('tr')).remove().draw();
                    },
                    error: function(xhr) {
                        // Handle the error response
                        console.log(xhr.responseText);
                    }
                });
            });
            $('#example1 thead tr').clone(true).appendTo('#example1 thead').addClass('filters custom-width-th');
            // Apply DataTables search and filtering behavior to the cloned filters
            $('#example1 thead tr:eq(1) th').each(function(i) {
                var title = $(this);
                $(this).html('<input class="form-control form-control-sm" type="text" id="filter' + title
                    .attr('id') + '" name="' + title + '" placeholder="Search ' + title.text() + '" />');
                $('input', this).on('keyup change', function() {
                    if ($(this).val() != null) {
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
@endsection
