@extends('layouts.parent')
@section('title')
{{ __("subcenters_add_new_excel") }}
@endsection
@section('content')
<div class="card card-default" bis_skin_checked="1">
    <div class="card-header" bis_skin_checked="1">
        <h3 class="card-title">@yield('title')</h3>
            <a href="{{ route('stamp_sub') }}"
            style="
            position: absolute;
            left:20%;
            width: auto;
            bottom: 3px;"
            class="btn btn-primary btn-block">{{ __("download_stamp") }}</a>
    </div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('excelcenterstore') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("center") }}</label>
                    <select name="center" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("center") }}</option>
                        @foreach ($centers as $center)
                        <option @selected(old('company')==$center->id ) value="{{ $center->id }}">{{ $center->center_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('center')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                    <span class="select2 select2-container select2-container--bootstrap4" dir="ltr" data-select2-id="18"
                        style="width: 100%;"><span class="selection"><span
                                class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true"
                                aria-expanded="false" tabindex="0" aria-disabled="false"
                                aria-labelledby="select2-rmnh-container"><span class="select2-selection__arrow"
                                    role="presentation"><b role="presentation"></b></span></span></span><span
                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputFile">{{ __("sheet") }}</label>
                    <div class="input-group" bis_skin_checked="1">
                        <div class="custom-file" bis_skin_checked="1">
                            <input name="sheet" value="{{ old('sheet') }}" type="file" id="exampleInputFile"
                            accept=".xlsx,.xls">
                            <label class="custom-file-label" for="exampleInputFile">{{__("choose_file")}}</label>
                        </div>
                    </div>
                    @error('sheet')
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
