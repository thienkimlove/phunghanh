@extends('v2.layouts.app')

@section('styles')
    <!-- Plugins css-->
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="/v2/vendor/ubold/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
    <link href="/v2/vendor/ubold/assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
    <link href="/v2/vendor/ubold/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{ route('networks.index') }}" class="btn btn-primary waves-effect waves-light"><span class="m-r-5"><i class="fa fa-list"></i></span> List</a>
            </div>
            <h4 class="page-title">Chi tiết network</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('networks.index') }}">Danh sách network</a>
                </li>
                <li class="active">
                    Chi tiết network <b># {{$network->name}}</b>
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        {!! Form::open(['route' => ['networks.update', $network->id], 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                        @include('v2.layouts.partials.errors')

                        <div class="form-group">
                            <label class="col-md-3 control-label">Tên network</label>
                            <div class="col-md-9">
                                {!! Form::text('name', $network->name, ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Tên network', 'required' => 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Click Url</label>
                            <div class="col-md-9">
                                {!! Form::text('click_url', $network->click_url, ['id' => 'click_url', 'class' => 'form-control', 'placeholder' => 'Click Url', 'required' => 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Map Params</label>
                            <div class="col-md-9">
                                {!! Form::text('map_params', $network->map_params, ['id' => 'map_params', 'class' => 'form-control', 'placeholder' => 'Map Params', 'required' => 'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Extend Params</label>
                            <div class="col-md-9">
                                {!! Form::text('extend_params', $network->extend_params, ['id' => 'extend_params', 'class' => 'form-control', 'placeholder' => 'Extend Params']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Callback Url</label>
                            <div class="col-md-9">
                                {!! Form::text('callback_url', $network->callback_url, ['id' => 'callback_url', 'class' => 'form-control', 'placeholder' => 'Callback Url', 'required' => 'required']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Callback Allow Ips</label>
                            <div class="col-md-9">
                                {!! Form::text('callback_allow_ip', $network->callback_allow_ip, ['id' => 'callback_allow_ip', 'class' => 'form-control', 'placeholder' => 'Callback Allow Ips']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Cron Url (In case SMS mode = System running cron to update)</label>
                            <div class="col-md-9">
                                {!! Form::text('cron_url', $network->cron_url, ['id' => 'cron_url', 'class' => 'form-control', 'placeholder' => 'Cron Url']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Loại</label>
                            <div class="col-md-9">
                                {!! Form::select('is_sms_callback', array(0 => 'Not using SMS callback', 1 => 'Partner call SMS update', 2 => 'System running cron to update'), $network->is_sms_callback, ['class' => 'form-control select2']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">Auto Add Postback (In case SMS mode = System running cron to update)</label>
                            <div class="col-md-9">
                                {!! Form::checkbox('auto', '1', $network->auto, ['data-plugin' => 'switchery', 'data-color' => '#81c868']) !!}
                                <span class="lbl"></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label">URL Redirect If Duplicate</label>
                            <div class="col-md-9">
                                {!! Form::text('redirect_if_duplicate', $network->redirect_if_duplicate, ['id' => 'redirect_if_duplicate', 'class' => 'form-control', 'placeholder' => 'URL Redirect']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Number of Time Redirect</label>
                            <div class="col-md-9">
                                {!! Form::number('number_redirect', $network->number_redirect, ['id' => 'number_redirect', 'class' => 'form-control', 'placeholder' => 'Number of Time Redirect']) !!}
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-md-3 control-label">Trạng thái</label>
                            <div class="col-md-9">
                                {!! Form::checkbox('status', '1', $network->status, ['data-plugin' => 'switchery', 'data-color' => '#81c868']) !!}
                                <span class="lbl"></span>
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
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/autocomplete/jquery.mockjax.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/plugins/autocomplete/countries.js"></script>
    <script type="text/javascript" src="/v2/vendor/ubold/assets/pages/autocomplete.js"></script>

    {{--<script type="text/javascript" src="/v2/vendor/ubold/assets/pages/jquery.form-advanced.init.js"></script>--}}
@endsection

@section('inline_scripts')
    <script>
        (function($){
            $('.select2').select2();

        })(jQuery);
    </script>

@endsection