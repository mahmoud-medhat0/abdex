{{-- {{ dd($errors) }} --}}
@extends('layouts.parent')
@section('title')
{{ __('companies_supply') }}
@endsection
@section('content')
<div class="card card-default" bis_skin_checked="1">
    <div class="card-header" bis_skin_checked="1">
        <h3 class="card-title">@yield('title')</h3>
    </div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <select id="states">
        <option>none</option>
        @foreach ($states as $state)
            <option value="{{ $state->id }}">{{ $state->state }}</option>
        @endforeach
    </select>
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('name') }}</th>
                <th>{{ __('supplies_new') }}</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            {{-- @foreach ($companies as $company)
            <tr>
                <td><input type="checkbox" name="{{'checkbox-'.$company->id }}" value="{{$company->id}}"></td>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $dues[$company->id] }}</td>
                <td>{{ $payed[$company->id] }}</td>
                <td><a class="btn btn-info" href="{{ route('h_csupplies',$company->id) }}"><i class="fas fa-eye"></i>{{ __('view') }}</a></td>
                <td><a class="btn btn-block btn-secondary" href="{{ route('companies_new',$company->id) }}">{{ __('new') }}</a></td>
            </tr>
            @endforeach --}}
        </tbody>
        <tfoot>
            <tr>
                <th>{{ __('name') }}</th>
                <th>{{ __('supplies_new') }}</th>
            </tr>
        </tfoot>
    </table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
      $('#states').on('change', function() {
        var selectedOption = $(this).val();

        // Get the CSRF token value from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
          url: "{{ route('companies.filter.ajax') }}",
          method: 'POST',
          data: {
            option: selectedOption,
            _token: csrfToken // Include the CSRF token in the request data
          },
          success: function(response) {
            var tableBody = $('#tableBody');
            tableBody.empty(); // Clear existing table body content

            // Loop through the response data and create table rows and cells
            $.each(response, function(index, rowData) {
                var companyId = rowData.id;
                var routeUrl = "{{ route('companies_new', ':companyId') }}";
                routeUrl = routeUrl.replace(':companyId', companyId);
                var body = '<tr> <td>' + rowData.name + '</td><td>' +
                '<a class="btn btn-block btn-secondary" href="'+routeUrl + '">' +
                '{{ __("new") }}' + '</a>' + '</td> </tr>';
                tableBody.append(body);
            });
          }
        });
      });
    });
  </script>
@endsection
