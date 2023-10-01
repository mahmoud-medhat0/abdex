@extends('layouts.parent')
@section('title')
{{ __("users_edit") }}
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
    <form action="{{ route('user_update') }}" class="form-group" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="card-body" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("name") }}</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control"
                            placeholder="{{ __("name") }}">
                        @error('name')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputEmail1">{{ __("username") }}</label>
                        <input type="text" name="username" value="{{ $user->username }}" class="form-control"
                            placeholder="{{ __("username") }}">
                        @error('username')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputPassword1">{{ __("password") }}</label>
                    <input type="text" name="password" class="form-control" id="exampleInputPassword1"
                        placeholder="{{ __("password") }}">
                    @error('password')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputPassword1">{{ __("password_confirm") }}</label>
                    <input type="text" name="password_confirmation" class="form-control" id="exampleInputPassword1"
                        placeholder="{{ __("password_confirm") }}">
                    @error('password_confirmation')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6" bis_skin_checked="1">
                    <div class="form-group" bis_skin_checked="1">
                        <label>{{ __("gender") }}</label>
                        <select name="gender" class="form-control">
                            <option selected="selected" data-select2-id="19">{{ __("gender") }}</option>
                            <option @selected($user->gender=="m" ) value="m">{{ __("male") }}</option>
                            <option @selected($user->gender=="f" ) value="f">{{ __("female") }}</option>
                        </select>
                        @error('gender')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputFile">{{ __("identy_number") }}</label>
                    <div class="input-group" bis_skin_checked="1">
                        @if ($user->identy_number!='*')
                        <a class="btn btn-primary btn-sm" href="{{ asset($user->identy_number) }}">
                            <i class="fas fa-eye"></i>
                            {{ __("view") }}
                        </a>
                        @endif
                        <div class="custom-file" bis_skin_checked="1">
                            <input name="identy" value="{{ old('identy') }}" type="file" id="exampleInputFile"
                                accept="application/pdf">
                            <label class="custom-file-label" for="exampleInputFile">{{ __("choose_file") }}</label>
                        </div>
                    </div>
                    @error('identy')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputFile1">{{ __("driving_license") }}</label>
                    <div class="input-group" bis_skin_checked="1">
                        @if ($user->Driving_License!='*')
                        <a class="btn btn-primary btn-sm" href="{{ asset($user->Driving_License) }}">
                            <i class="fas fa-eye">
                            </i>
                            {{ __("view") }}
                        </a>
                        @endif
                        <div class="custom-file" bis_skin_checked="1">
                            <input name="drvlicence" value="{{ old('drvlicence') }}" type="file" id="exampleInputFile1"
                                accept="application/pdf" />
                            <label class="custom-file-label" for="exampleInputFile1">{{ __("choose_file") }}</label>
                        </div>
                    </div>
                    @error('drvlicence')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label for="exampleInputFile2">{{ __("bike_license") }}</label>
                    <div class="input-group" bis_skin_checked="1">
                        @if ($user->bike_license!='*')
                        <a class="btn btn-primary btn-sm" href="{{ asset($user->bike_license) }}">
                            <i class="fas fa-eye">
                            </i>
                            {{ __("view") }}
                        </a>
                        @endif
                        <div class="custom-file" bis_skin_checked="1">
                            <input name="bilkelicense" value="{{ old('bilkelicense') }}" type="file"
                                id="exampleInputFile2" accept="application/pdf" />
                            <label class="custom-file-label" for="exampleInputFile2">{{ __("choose_file") }}</label>
                        </div>
                    </div>
                    @error('bilkelicense')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("rank") }}</label>
                    <select name="rank" class="form-control select2bs4 select2-hidden-accessible" style="width: 100%;"
                        data-select2-id="17" tabindex="-1" aria-hidden="true">
                        <option selected="selected" data-select2-id="19">{{ __("rank") }}</option>
                        @foreach ($ranks as $rank)
                        <option @selected($user->rank_id == $rank->id ) value="{{ $rank->id }}"> 
                            @if(app()->getLocale()=="en")
                            {{ $rank->name }}
                            @elseif (app()->getLocale()=="ar")
                            {{ $rank->name_ar }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('rank')
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
                    <label>{{ __("phone_personal") }}</label>
                    <input class="form-control" value="{{ $user->phonep }}" name="phonep" rows="3"
                        placeholder="{{ __("phone_personal") }}">
                    @error('phonep')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("phone_work") }}</label>
                    <input class="form-control" value="{{ $user->phonew }}" name="phonew" rows="3"
                        placeholder="{{ __("phone_work") }}">
                    @error('phonew')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("phone_relative") }}</label>
                    <input class="form-control" name="phone3" value="{{ $user->phone3 }}" rows="3"
                        placeholder="{{ __("phone_relative") }}">
                    @error('phone3')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("address") }}</label>
                    <input class="form-control" name="address" value="{{ $user->address}}" rows="3"
                        placeholder="{{ __("address") }}">
                    @error('address')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror

                </div>
                <div class="form-group col-md-6" bis_skin_checked="1">
                    <label>{{ __("commision") }}</label>
                    <input type="number" class="form-control" name="commision" value="{{ $user->commision}}" rows="3"
                        placeholder="{{ __("commision") }}">
                    @error('commision')
                    <div class="text-danger font-weight-bold">*{{ $message }}</div>
                    @enderror
                    <div class="form-group col-md-6" bis_skin_checked="1">
                        <label>{{ __("company") }}</label>
                        <select name="company_id" class="form-control select2bs4 select2-hidden-accessible"
                            style="width: 100%;" data-select2-id="17" tabindex="-1" aria-hidden="true">
                            <option selected="selected" data-select2-id="19">{{ __("company") }}</option>
                            @foreach ($companies as $company)
                            <option @selected($user->company_id==$company->id ) value="{{ $company->id }}">{{
                                $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                        <div class="text-danger font-weight-bold">*{{ $message }}</div>
                        @enderror
                        <span class="select2 select2-container select2-container--bootstrap4" dir="ltr"
                            data-select2-id="18" style="width: 100%;"><span class="selection"><span
                                    class="select2-selection select2-selection--single" role="combobox"
                                    aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false"
                                    aria-labelledby="select2-rmnh-container"><span class="select2-selection__arrow"
                                        role="presentation"><b role="presentation"></b></span></span></span><span
                                class="dropdown-wrapper" aria-hidden="true"></span></span>
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
@section('js')
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function () {
          bsCustomFileInput.init();
        });
</script>
@endsection