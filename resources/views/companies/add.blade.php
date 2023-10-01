@extends('layouts.parent')
@section('title')
{{ __("companies_add_new") }}
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
    <form action="{{ route('companies_store') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("name") }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="{{ __("name") }}">
                        @error('name')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("company_special_intructions") }}</label>
                        <input type="text" name="special_intructions" value="{{ old('special_intructions') }}" class="form-control"
                            placeholder="{{ __("company_special_intructions") }}">
                        @error('special_intructions')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <center>
                <div class="card-footer" bis_skin_checked="1">
                    <button type="submit" class="btn btn-primary">{{ __("submit") }}</button>
                </div>
            </center>
        </div>
    </form>
</div>
@endsection
@section('js')
@endsection
