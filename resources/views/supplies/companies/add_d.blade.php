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
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>-</th>
                <th>{{ __('id') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('in') }}</th>
                <th>{{ __('out') }}</th>
                <th>{{ __('supplies_history') }}</th>
                <th>{{ __('supplies_new') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
            <tr>
                <td><input type="checkbox" name="{{'checkbox-'.$company->id }}" value="{{$company->id}}"></td>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $dues[$company->id] }}</td>
                <td>{{ $payed[$company->id] }}</td>
                <td><a class="btn btn-info" href="{{ route('h_csupplies',$company->id) }}"><i class="fas fa-eye"></i>{{ __('view') }}</a></td>
                <td><a class="btn btn-block btn-secondary" href="{{ route('companies_new',$company->id) }}">{{ __('new') }}</a></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>-</th>
                <th>{{ __('id') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('in') }}</th>
                <th>{{ __('out') }}</th>
                <th>{{ __('supplies_history') }}</th>
                <th>{{ __('supplies_new') }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
