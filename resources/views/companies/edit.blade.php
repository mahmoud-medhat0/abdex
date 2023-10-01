{{-- {{ dd($numbers) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("companies_data_edit") }}
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
    <form action="{{ route('companies_update') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $company->id }}">
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("name") }}</label>
                        <input type="text" name="name" value="{{ $company->name }}" class="form-control"
                            placeholder="{{ __(" name") }}">
                        @error('name')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("company_special_intructions") }}</label>
                        <input type="text" name="special_intructions" value="{{ $company->special_intructions }}"
                            class="form-control" placeholder="{{ __(" company_special_intructions") }}">
                        @error('special_intructions')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @if($numbers!='[]')
                @foreach ($numbers as $number)
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{__("note_en"). $number->id }}</label>
                        <input type="text" name="{{ " noteen_".$number->id }}" value="{{ $number->note_en }}"
                        class="form-control"
                        placeholder="{{ __("note_en") }}">
                        @error('note')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{__("note_ar").$number->id }}</label>
                        <input type="text" name="{{ " notear_".$number->id }}" value="{{ $number->note_ar }}"
                        class="form-control"
                        placeholder="{{ __("note_ar") }}">
                        @error('notear')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">number {{ $number->id }}</label>
                        <input type="number" name="{{ " number_".$number->id }}" value="{{ $number->phone_number }}"
                        class="form-control"
                        placeholder="number">
                        @error('number')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @endforeach
                @endif
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
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function () {
          bsCustomFileInput.init();
        });
</script>
@endsection
