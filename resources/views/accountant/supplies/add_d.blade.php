{{-- {{ dd($errors) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("supplies_delegates") }}
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
                <th>id</th>
                <th>{{ __("agent_name") }}</th>
                <th>{{ __("dues") }}</th>
                <th>{{ __("payed") }}</th>
                <th>{{ __("total") }}</th>
                <th>{{ __("supplies_history") }}</th>
                <th>{{ __("supplies_new") }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
            <tr>
                <td>{{ $agent->id }}</td>
                <td>{{ $agent->name }}</td>
                <td>{{ $dues[$agent->id] }}</td>
                <td>{{ $payed[$agent->id] }}</td>
                <td>{{ $payed[$agent->id]-$dues[$agent->id] }}</td>
                <td><a class="btn btn-info" href="{{ route('accountant_agent_h_supplies',$agent->id) }}"><i class="fas fa-eye"></i>
                        {{ __("view") }}</a></td>
                <td><a class="btn btn-block btn-secondary"
                        href="{{ route('accountant_supplies_stored',$agent->id) }}">{{ __("new") }}</a></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>id</th>
                <th>{{ __("agent_name") }}</th>
                <th>{{ __("dues") }}</th>
                <th>{{ __("payed") }}</th>
                <th>{{ __("total") }}</th>
                <th>{{ __("supplies_history") }}</th>
                <th>{{ __("supplies_new") }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
