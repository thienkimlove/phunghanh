@extends('v2.layouts.app')

@section('styles')
    <link href="/v2/vendor/ubold/assets/plugins/c3/c3.min.css" rel="stylesheet" type="text/css"  />
    <link href="/v2/vendor/ubold/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />

@endsection

@section('content')
    @php
        $currentUser = auth()->user();
    @endphp
    <div class="row">
        <div class="col-sm-12">
            {{--<h4 class="page-title">HỆ THỐNG CUSTOMER SERVICES</h4>--}}
            <p class="text-muted page-title-alt">Welcome {{ $currentUser->name }}</p>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box fadeInDown animated">
                <div class="bg-icon bg-icon-success pull-left">
                    <i class="md md-input text-success"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{ \App\Network::count() }}</b></h3>
                    <p class="text-muted">Số lượng network</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-pink pull-left">
                    <i class="md md-import-export text-pink"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{ \App\NetworkClick::count() }}</b></h3>
                    <p class="text-muted">Số lượng Clicks</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-purple pull-left">
                    <i class="md md-store text-purple"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{ \App\Connection::count() }}</b></h3>
                    <p class="text-muted">Số lượng Connection</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-info pull-left">
                    <i class="md md-account-child text-info"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{ \App\User::count() }}</b></h3>
                    <p class="text-muted">Số lượng người dùng</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <!-- End row -->
@endsection

@section('scripts')
    <script src="/v2/vendor/ubold/assets/plugins/switchery/js/switchery.min.js"></script>

    <!--C3 Chart-->
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/d3/d3.min.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/c3/c3.min.js"></script>
    {{--<script src="/v2/vendor/ubold/assets/pages/jquery.c3-chart.init.js"></script>--}}

@endsection
