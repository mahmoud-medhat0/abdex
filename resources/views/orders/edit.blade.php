{{-- {{ dd($order) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("orders_edit") }}
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
    <form action="{{ route('orderupdate') }}" class="form-group" method="post">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $order->id }}">
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-2" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("order_id") }}</label>
                        <input disabled type="text" name="id" value="{{ $order->id }}" class="form-control">
                        @error('id')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("company_name") }}</label>
                        <input disabled type="text" value="@foreach ($companies as $company)@if ($order->id_company==$company->id){{ $company->name}}@endif @endforeach" class="form-control">
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("id_police") }}</label>
                        <input disabled type="text" value="{{ $order->id_police }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("name_client") }}</label>
                        <input disabled type="text" value="{{ $order->name_client }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("phone") }}</label>
                        <input type="text" name="phone" value="{{ $order->phone }}" class="form-control"
                            placeholder="{{ __("phone") }}">
                        @error('phone')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("phone2") }}</label>
                        <input type="text" name="phone2" value="{{ $order->phone2 }}" class="form-control"
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
                        <option selected="selected" data-select2-id="19" value="0">{{ __("delegate") }}</option>
                        @foreach ($delegates as $delegate)
                        <option @selected($order->delegate_id == $delegate->id ) value="{{ $delegate->id }}">{{
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
                    <input class="form-control" name="address" value="{{ $order->address}}" rows="3"
                        placeholder="{{ __("address") }}">
                    @error('address')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("cost") }}</label>
                    <input class="form-control" name="cost" value="{{ $order->cost}}" rows="3" placeholder="{{ __("cost") }}">
                    @error('cost')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("notes") }}</label>
                    <input class="form-control" name="notes" value="{{ $order->notes}}" rows="3"
                        placeholder="{{ __("notes") }}">
                    @error('notes')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("special_intructions") }}</label>
                    <input class="form-control" name="special_intructions" value="{{ $order->special_intructions}}"
                        rows="3" placeholder="{{ __("special_intructions") }}">
                    @error('special_intructions')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("name_product") }}</label>
                    <input class="form-control" name="name_product" value="{{ $order->name_product}}" rows="3"
                        placeholder="{{ __("name_product") }}">
                    @error('name_product')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("sender") }}</label>
                    <input class="form-control" name="sender" value="{{ $order->sender}}" rows="3"
                        placeholder="{{ __("sender") }}">
                    @error('sender')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("weghit") }}</label>
                    <input class="form-control" name="weghit" value="{{ $order->weghit}}" rows="3"
                        placeholder="{{ __("weghit") }}">
                    @error('weghit')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("open") }}</label>
                    <input class="form-control" name="open" value="{{ $order->open}}" rows="3" placeholder="{{ __("open") }}">
                    @error('open')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>{{ __("state_order") }}</label>
                    <select name="status_id" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("state_order") }}</option>
                        @foreach ($states as $state)
                        <option @selected($order->status_id == $state->id ) value="{{ $state->id }}">{{ $state->state}}
                        </option>
                        @endforeach
                    </select>
                    @error('status_id')
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
                    <label>{{ __("cause_return") }}</label>
                    <select name="cause_id" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("cause_return") }}</option>
                        @foreach ($causes as $cause)
                        <option @selected($order->cause_id == $cause->id ) value="{{ $cause->id }}">{{ $cause->cause}}
                        </option>
                        @endforeach
                    </select>
                    @error('cause_id')
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
                    <label>{{ __("cause_delay") }}</label>
                    <select name="delay_id" class="form-control select2bs4 select2-hidden-accessible"
                        style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("cause_delay") }}</option>
                        @foreach ($causes_delay as $cause)
                        <option @selected($order->delay_id  == $cause->id ) value="{{ $cause->id }}">{{ $cause->cause}}
                        </option>
                        @endforeach
                    </select>
                    @error('delay_id')
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
                <div class="col-sm-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label>{{ __("delegate_supply") }}</label>
                        <select name="delegate_supply" class="form-control">
                            <option selected="selected" data-select2-id="19">{{ __("delegate_supply") }}</option>
                            <option @selected($order->delegate_supply=="0" ) value="0">لم يتم التوريد</option>
                            <option @selected($order->delegate_supply=="1" ) value="1">تم التوريد</option>
                        </select>
                        @error('delegate_supply')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label>{{ __("company_supply") }}</label>
                        <select name="company_supply" class="form-control">
                            <option selected="selected" data-select2-id="19">{{ __("company_supply") }}</option>
                            <option @selected($order->company_supply=="0" ) value="0">لم يتم التوريد</option>
                            <option @selected($order->company_supply=="1" ) value="1">تم التوريد</option>
                        </select>
                        @error('company_supply')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label>{{ __("locate_order") }}</label>
                        <select name="order_locate" class="form-control">
                            <option selected="selected" data-select2-id="19">{{ __("locate_order") }}</option>
                            <option @selected($order->order_locate=="0" ) value="0">لم يتم الاستلام بعد</option>
                            <option @selected($order->order_locate=="1" ) value="1">بالمقر</option>
                            <option @selected($order->order_locate=="2" ) value="2">مع المندوب</option>
                            <option @selected($order->order_locate=="3" ) value="3">تم الرد للراسل</option>
                            <option @selected($order->order_locate=="4" ) value="4">مطلوب من المندوب</option>
                        </select>
                        @error('order_locate')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label for="exampleInputPassword1">{{ __("identy_number_order") }}</label>
                    <input type="text" name="identy_number" value="{{ $order->identy_number}}" class="form-control" id="exampleInputPassword1"
                        placeholder="{{ __("identy_number_order") }}">
                    @error('identy_number')
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
</form>
</div>
@endsection
