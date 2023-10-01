{{-- {{ dd($salary) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("salary_edit") }}
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
    <form action="{{ route('updatesalary') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $salary->id }}">
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("salary_discount_total") }}</label>
                        <input type="text" name="discount" value="{{ $salary->discount }}" class="form-control"
                            placeholder="{{ __("salary_discount_total") }}">
                        @error('discount')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label>{{ __("salary_confirm") }}</label>
                        <select name="done" class="form-control">
                            <option @selected($salary->done=="0" ) value="0">❌لم يتم تسليم الراتب </option>
                            <option @selected($salary->done=="1" ) value="1">✅تم تسليم الراتب </option>
                        </select>
                        @error('done')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <center>
                <div class="card-footer" bis_skin_checked="1">
                    <button type="submit" class="btn btn-primary">{{ __("update") }}</button>
                </div>
            </center>
        </div>
    </form>
</div>
@endsection
