{{-- {{ dd($premission) }} --}}
@extends('layouts.parent')
@section('title')
{{ __("edit_cause_delay") }}
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
    <form action="{{ route('updatecausedelay') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $cause->id }}">
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("cause_delay") }}</label>
                        <input type="text" name="cause" value="{{ $cause->cause }}" class="form-control"
                            placeholder="{{ __("cause_delay") }}">
                        @error('cause')
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
