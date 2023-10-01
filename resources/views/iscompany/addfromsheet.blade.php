@extends('layouts.company')
@section('title')
{{ __("orders_add_sheet") }}
@endsection
@section('content')
<div class="card card-default" bis_skin_checked="1">
    <div class="card-header" bis_skin_checked="1">
        <h3 class="card-title">@yield('title')</h3>
        <a href="{{ route('stamp') }}"
        style="
        position: absolute;
        left:15%;
        width: auto;
        bottom: 3px;"
        class="btn btn-primary btn-block">{{ __("download_stamp") }}</a>
</div>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('companystoresheet') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputFile">{{ __("sheet") }}</label>
                    <div class="input-group" bis_skin_checked="1">
                        <div class="custom-file" bis_skin_checked="1">
                            <input name="sheet" value="{{ old('sheet') }}" type="file" id="exampleInputFile"
                            accept=".xlsx,.xls">
                            <label class="custom-file-label" for="exampleInputFile">{{ __("choose_file") }}</label>
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
