{{-- {{ dd($errors) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("expenses_add") }}
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
    <form action="{{ route('expenses_store') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <label>{{ __("expenses_type") }}</label>
                <select name="type_out" class="form-control select2bs4 select2-hidden-accessible"
                    style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                    <option selected="selected" data-select2-id="19">{{ __("expenses_type") }}</option>
                    <option @selected(old('type_out')=='0' ) value="0">خارج شركه</option>
                    <option @selected(old('type_out')=='1' ) value="1">مصاريف شخصيه</option>
                    <option @selected(old('type_out')=='2' ) value="2">مصاريف نقل</option>
                    <option @selected(old('type_out')=='3' ) value="3">توالف</option>
                </select>
                @error('type_out')
                <div class="text-danger font-weight-bold">*{{ $message }}</div>
                @enderror
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("expenses_company_name") }}</label>
                    <input type="text" class="form-control" value="{{ old('name') }}" name="name" rows="3"
                        placeholder="{{ __("expenses_company_name") }}">
                    @error('name')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("expenses_company_cost") }}</label>
                    <input type="number" class="form-control" value="{{ old('cost') }}" name="cost" rows="3"
                        placeholder="{{ __("expenses_company_cost") }}">
                    @error('cost')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <center>
                <div class="card-footer" bis_skin_checked="1">
                    <button type="submit" class="btn btn-primary">{{ __("import") }}</button>
                </div>
            </center>
        </div>
    </form>
</div>
@endsection
@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function () {
          bsCustomFileInput.init();
        });
</script>
@endsection
