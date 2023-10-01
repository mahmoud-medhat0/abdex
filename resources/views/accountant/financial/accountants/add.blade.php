@extends('layouts.parent')
@section('title')
{{ __('Account_Statements_add') }}
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
    <form action="{{ route('accountant_store_finance') }}" class="form-group" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __('statement_person') }}</label>
                    <select name="person" class="form-control select2bs4 select2-hidden-accessible" style="width: 100%;"
                        data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">none</option>
                        @foreach ($persons as $person)
                        <option @selected(old('person')==$person->id ) value="{{ $person->id }}">{{ $person->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('person')
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
                    <label>{{ __('in') }}</label>
                    <input type="number" class="form-control" value="{{ old('creditor') }}" name="creditor" rows="3"
                        placeholder="Enter cost">
                    @error('creditor')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __('out') }}</label>
                    <input type="number" class="form-control" value="{{ old('debtor') }}" name="debtor" rows="3"
                        placeholder="Enter cost">
                    @error('debtor')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __('cause') }}</label>
                    <input type="text" class="form-control" value="{{ old('cause') }}" name="cause" rows="3"
                        placeholder="Enter notes">
                    @error('cause')
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
