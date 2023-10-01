@extends('layouts.parent')
@section('title')
 {{ __('history_supply_agent').' '.$name }}
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
                <th>{{ __('id') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('in') }}</th>
                <th>{{ __('out') }}</th>
                <th>{{ __('total') }}</th>
                <th>{{ __('supply_date') }}</th>
                <th>{{ __('sheet') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($histories as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ $history->name }}</td>
                <td>{{ $history->payed }}</td>
                <td>{{ $history->late }}</td>
                <td>{{ $history->total }}</td>
                <td>{{ $history->created_at }}</td>
                <td><a href="{{ route('supply_agent_sheet',$history->id) }}">{{ __('view') }}</a></td>
            </tr>
            @endforeach
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>{{ __('id') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('in') }}</th>
                <th>{{ __('out') }}</th>
                <th>{{ __('total') }}</th>
                <th>{{ __('supply_date') }}</th>
                <th>{{ __('sheet') }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
