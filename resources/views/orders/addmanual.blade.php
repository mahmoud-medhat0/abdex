{{-- {{ dd($order) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("orders_add") }}
@endsection
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="card card-default" bis_skin_checked="1">
    <div class="card-header" bis_skin_checked="1">
        <h3 class="card-title">@yield('title')</h3>
    </div>
    <form action="{{ route('store_m') }}" class="form-group" method="post">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("company_name") }}</label>
                    <select name="id_company" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("company_name") }}</option>
                        @foreach ($companies as $company)
                        <option @selected(old('id_company')==$company->id ) value="{{ $company->id }}">{{
                            $company->name}}</option>
                        @endforeach
                    </select>
                    @error('id_company')
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
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("name_client") }}</label>
                        <input name="name_client" type="text" placeholder="{{ __("name_client") }}" value="{{ old('name_client') }}"
                            class="form-control">
                        @error('name_client')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("phone") }}</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                            placeholder="{{ __("phone") }}">
                        @error('phone')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("phone2") }}</label>
                        <input type="text" name="phone2" value="{{ old('phone2') }}" class="form-control"
                            placeholder="{{ __("phone2") }}">
                        @error('phone2')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("delegate") }}</label>
                    <select name="delegate_id" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("delegate") }}</option>
                        @foreach ($delegates as $delegate)
                        <option @selected(old('delegate_id')==$delegate->id ) value="{{ $delegate->id }}">{{
                            $delegate->name}}</option>
                        @endforeach
                    </select>
                    @error('delegate_id')
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
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("address") }}</label>
                    <input class="form-control" name="address" value="{{old('address')}}" rows="3"
                        placeholder="{{ __("address") }}">
                    @error('address')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("cost") }}</label>
                    <input class="form-control" name="cost" value="{{ old('cost') }}" rows="3" placeholder="{{ __("cost") }}">
                    @error('cost')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("notes") }}</label>
                    <input class="form-control" name="notes" value="{{ old('notes') }}" rows="3"
                        placeholder="{{ __("notes") }}">
                    @error('notes')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("special_intructions") }}</label>
                    <input class="form-control" name="special_intructions" value="{{ old('special_intructions')}}"
                        rows="3" placeholder="Enter special intructions">
                    @error('special_intructions')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("name_product") }}</label>
                    <input class="form-control" name="name_product" value="{{ old('name_product')}}" rows="3"
                        placeholder="{{ __("name_product") }}">
                    @error('name_product')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("sender") }}</label>
                    <input class="form-control" name="sender" value="{{ old('sender')}}" rows="3"
                        placeholder="{{ __("sender") }}">
                    @error('sender')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("weghit") }}</label>
                    <input class="form-control" name="weghit" value="{{ old('weghit') }}" rows="3"
                        placeholder="{{ __("weghit") }}">
                    @error('weghit')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("open") }}</label>
                    <input class="form-control" name="open" value="{{ old('open') }}" rows="3" placeholder="{{ __("open") }}">
                    @error('open')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("identy_number_order") }}</label>
                    <input class="form-control" name="identy_number" value="{{ old('identy_number') }}" rows="3"
                        placeholder="{{ __("identy_number_order") }}">
                    @error('identy_number')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label>{{ __("locate_order") }}</label>
                        <select name="order_locate" class="form-control">
                            <option selected="selected" data-select2-id="19">{{ __("locate_order") }}</option>
                            <option @selected(old('order_locate')=="0" ) value="0">لم يتم الاستلام بعد</option>
                            <option @selected(old('order_locate')=="1" ) value="1">بالمقر</option>
                            <option @selected(old('order_locate')=="2" ) value="2">مع المندوب</option>
                            <option @selected(old('order_locate')=="3" ) value="3">تم الرد للراسل</option>
                            <option @selected(old('order_locate')=="4" ) value="4">مطلوب من المندوب</option>
                        </select>
                        @error('order_locate')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <center>
            <div class="card-footer" bis_skin_checked="1">
                <button type="submit" class="btn btn-primary">{{ __("import") }}</button>
            </div>
        </center>
    </form>
</div>
@endsection
