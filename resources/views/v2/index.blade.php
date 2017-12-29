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

    <!-- End row -->
@endsection

@section('scripts')
    <script src="/v2/vendor/ubold/assets/plugins/switchery/js/switchery.min.js"></script>

    <!--C3 Chart-->
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/d3/d3.min.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/c3/c3.min.js"></script>
    {{--<script src="/v2/vendor/ubold/assets/pages/jquery.c3-chart.init.js"></script>--}}

@endsection
