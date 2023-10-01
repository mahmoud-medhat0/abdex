{{-- {{ dd($companies) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("companies_add_new_number") }}
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
    <form action="{{ route('companies_num_store') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("company") }}</label>
                    <select name="company" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("company") }}</option>
                        @foreach ($companies as $company)
                        <option @selected(old('company')==$company->id ) value="{{ $company->id }}">{{
                            $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company')
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
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("note_en") }}</label>
                        <input type="text" name="note" value="{{ old('note') }}" class="form-control"
                            placeholder="{{ __("note_en") }}">
                        @error('note')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("note_ar") }}</label>
                        <input type="text" name="notear" value="{{ old('notear') }}" class="form-control"
                            placeholder="{{ __("note_ar") }}">
                        @error('notear')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("number") }}</label>
                        <input type="number" name="number" value="{{ old('number') }}" class="form-control"
                            placeholder="{{ __("number") }}">
                        @error('number')
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
