@extends('layouts.parent')
@section('title')
{{ __('home') }}
@endsection
@section('content')
<div class="card card-default" bis_skin_checked="1">
    <div class="card-header" bis_skin_checked="1">
        <h3 class="card-title">@yield('title')</h3>
    </div>

    <div class="card-body" bis_skin_checked="1">
        {{-- <div class="mb-3">{!! DNS1D::getBarcodeHTML('*MAR2133750*', 'C128') !!}</div> --}}
        <div class="row" bis_skin_checked="1">
            Hi, SUR {{ Auth::user()->name }} Welcome To MAR EXPRESS
            @switch(Auth::user()->rank_id)
            @case(1)
            {{ 'admin' }}
            @break

            @case(2)
            {{ 'customer service' }}
            @break

            @case(3)
            {{ 'operations' }}
            @break

            @case(4)
            {{ 'data entry' }}
            @break

            @case(5)
            {{ 'acountant' }}
            @break

            @case(6)
            {{ 'sales' }}
            @break

            @case(7)
            {{ 'company' }}
            @break

            @case(8)
            {{ 'agent' }}
            @break

            @case(9)
            {{ 'representative' }}
            @break
            @endswitch Panel.
        </div>
    </div>

</div>

{{-- statistics overview colored --}}

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-lightblue">
                    <div class="inner">
                        <h3>{{ $tempmoney }}</h3>

                        <p>{{ __('temp_money') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-primary">
                    <div class="inner">
                        <h3>{{ $salaries_under }}</h3>

                        <p>{{ __('salaries_under') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{ $company_out }}</h3>

                        <p>{{ __('expenses_company_all') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-navy">
                    <div class="inner">
                        <h3>{{ $company_moving }}</h3>

                        <p>{{ __('expenses_out_moving') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $money }}</h3>

                        <p>{{ __('money') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $profits }}</h3>

                        <p>{{ __('balance_profits') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-olive">
                    <div class="inner">
                        <h3>{{ $company_personal }}</h3>

                        <p>{{ __('expenses_out_personal') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $company_consists }}</h3>

                        <p>{{ __('expenses_consists') }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
