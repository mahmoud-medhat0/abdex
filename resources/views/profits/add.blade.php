{{-- {{ dd($errors) }} --}}
@extends('layouts.parent')
@section('title')
{{ __('profits_out_add') }}
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
    <form action="{{ route('profits_out_store') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __('name') }}</label>
                    <input type="text" class="form-control" value="{{ old('name') }}" name="name" rows="3"
                        placeholder="Enter name">
                    @error('name')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __('cost') }}</label>
                    <input type="number" class="form-control" value="{{ old('cost') }}" name="cost" rows="3"
                        placeholder="Enter cost">
                    @error('cost')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <center>
                <div class="card-footer" bis_skin_checked="1">
                    <button type="submit" class="btn btn-primary">{{ __('import') }}</button>
                </div>
            </center>
        </div>
    </form>
</div>
@endsection
