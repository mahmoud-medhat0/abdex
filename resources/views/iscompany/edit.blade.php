@extends('layouts.company')
@section('title')
اضافه طرد يدوي
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
    <form action="{{ route('companyorderupdate',$order->id) }}" class="form-group" method="post">
        @csrf
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">رقم البوليصة</label>
                        <input name="name_client" type="text" disabled value="{{ $order->id_police }}"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">اسم العميل</label>
                        <input name="name_client" type="text" placeholder="name client" value="{{ $order->name_client }}"
                            class="form-control">
                        @error('name_client')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">رقم هاتف اول</label>
                        <input type="text" name="phone" value="{{ $order->phone }}" class="form-control"
                            placeholder="phone">
                        @error('phone')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">رقم هاتف ثاني</label>
                        <input type="text" name="phone2" value="{{ $order->phone2 }}" class="form-control"
                            placeholder="phone2">
                        @error('phone2')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>العنوان</label>
                    <input class="form-control" name="address" value="{{ $order->address }}" rows="3"
                        placeholder="Enter Address">
                    @error('address')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>السعر</label>
                    <input class="form-control" name="cost" value="{{ $order->cost }}" rows="3" placeholder="Enter cost">
                    @error('cost')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>مذكرات</label>
                    <input class="form-control" name="notes" value="{{ $order->notes }}" rows="3"
                        placeholder="Enter notes">
                    @error('notes')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>تعليمات خاصه</label>
                    <input class="form-control" name="special_intructions" value="{{ $order->special_intructions }}"
                        rows="3" placeholder="Enter special intructions">
                    @error('special_intructions')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>اسم المنتج</label>
                    <input class="form-control" name="name_product" value="{{ $order->name_product }}" rows="3"
                        placeholder="Enter name product">
                    @error('name_product')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>اسم الراسل</label>
                    <input class="form-control" name="sender" value="{{ $order->sender }}" rows="3"
                        placeholder="Enter sender">
                    @error('sender')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>الوزن</label>
                    <input class="form-control" name="weghit" value="{{ $order->weghit }}" rows="3"
                        placeholder="Enter weghit">
                    @error('weghit')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>الفتح</label>
                    <input class="form-control" name="open" value="{{ $order->open }}" rows="3" placeholder="Enter open">
                    @error('open')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-3" bis_skin_checked="1">
                    <label>رقم البطاقه</label>
                    <input class="form-control" name="identy_number" value="{{ $order->identy_number }}" rows="3"
                        placeholder="Enter identy_number">
                    @error('identy_number')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <center>
            <div class="card-footer" bis_skin_checked="1">
                <button type="submit" class="btn btn-primary">{{ __('update') }}</button>
            </div>
        </center>
    </form>
</div>
@endsection
