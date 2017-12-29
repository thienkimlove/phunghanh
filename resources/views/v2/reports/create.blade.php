@extends('v2.layouts.app')

@section('styles')
    <!-- Plugins css-->
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="/v2/vendor/ubold/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
    <link href="/v2/vendor/ubold/assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
    <link href="/v2/vendor/ubold/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="/v2/js/admin/datetimepicker/build/jquery.datetimepicker.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{ route('reports.index') }}" class="btn btn-primary waves-effect waves-light"><span class="m-r-5"><i class="fa fa-list"></i></span> List</a>
            </div>
            <h4 class="page-title">Tạo mới sản lượng</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('networks.index') }}">Danh sách sản lượng nhập tay</a>
                </li>
                <li class="active">
                    Tạo mới sản lượng
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => ['reports.store'], 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                        @include('v2.layouts.partials.errors')

                        <div class="form-group">
                            <label class="col-md-3 control-label">Date</label>
                            <div class="col-md-9">
                                {!! Form::text('date', \Carbon\Carbon::now()->toDateString(), ['class' => 'form-control', 'id' => 'start-network-date']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Quantity</label>
                            <div class="col-md-9">
                                {!! Form::number('quantity', null, ['id' => 'quantity', 'class' => 'form-control', 'placeholder' => 'Quantity']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Network</label>
                            <div class="col-md-9">
                                {!! Form::select('network_id', \App\Network::where('is_sms_callback', 2)->pluck('name','id')->all(), null, ['class' => 'form-control select2']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-success waves-effect waves-light">Lưu</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/switchery/js/switchery.min.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/multiselect/js/jquery.multi-select.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/autocomplete/countries.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/pages/autocomplete.js"></script>

    <script type="text/javascript" src="/v2/vendor/ubold/assets/pages/jquery.form-advanced.init.js"></script>

    <script src="/v2/vendor/ubold/assets/plugins/moment/moment.js"></script>
    <script src="/v2/js/admin/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('inline_scripts')
    <script>
        (function($){
            $('.select2').select2();
        })(jQuery);
    </script>

    <script>
        $(document).ready(function(){
            jQuery.datetimepicker.setLocale('vi');

            jQuery('#start-network-date').datetimepicker({
                i18n:{
                    vi:{
                        months:[
                            'Thang 1','Thang 2','Thang 3','Thang 4',
                            'Thang 5','Thang 6','Thang 7','Thang 8',
                            'Thang 9','Thang 10','Thang 11','Thang 12',
                        ],
                        dayOfWeek:[
                            "Chu Nhat", "Thu 2", "Thu 3", "Thu 4",
                            "Thu 5", "Thu 6", "Thu 7",
                        ]
                    }
                },
                timepicker:false,
                minDate:'1970-01-02',
                format:'Y-m-d'
            });
        });
    </script>
@endsection