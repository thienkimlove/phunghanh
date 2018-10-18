@extends('v2.layouts.app')

@section('inline_styles')
    <style>
        .select2-container--default {
            width: 250px !important;
        }
        .select2-container--default .select2-results > .select2-results__options {
            max-height: 500px;
            min-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('styles')
    <!-- DataTables -->
    <link href="/v2/vendor/ubold/assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/v2/vendor/ubold/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/v2/vendor/ubold/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="/networks/create"><button type="button" class="btn btn-default dropdown-toggle waves-effect" >Tạo mới <span class="m-l-5"><i class="fa fa-plus"></i></span></button></a>
            </div>

            <h4 class="page-title">Danh sách networks</h4>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-inline" role="form" id="search-form">
                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Network name</label>
                                <input type="text" class="form-control" placeholder="Tên network" name="name"/>
                            </div>

                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Trạng thái</label>
                                {!! Form::select('status', ['' => '--- Chọn trạng thái ---'] + [1 => 'Active', 0 => 'Inactive'], null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">AutoPostBack</label>
                                {!! Form::select('auto', ['' => '--- Chọn AutoPostBack ---'] + array( 0 => 'Inactive', 1 => 'Active'), null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Loại kết nối</label>
                                {!! Form::select('is_sms_callback', ['' => '--- Chọn loại ---'] + array(0 => 'Not using SMS callback', 1 => 'Partner call SMS update', 2 => 'System running cron to update'), null, ['class' => 'form-control']) !!}
                            </div>


                            <button type="submit" class="btn btn-success waves-effect waves-light m-l-15">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <p class="text-muted font-13 m-b-30"></p>
                <table id="dataTables-networks" class="table table-striped table-bordered table-actions-bar">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>ClickUrl</th>
                        <th>Auto</th>
                        <th>ClickConvert</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="list-content-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">HowTo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="show_howto" class="col-sm-12">

                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('scripts')
    <script src="/v2/vendor/ubold/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.bootstrap.js"></script>

    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/buttons.bootstrap.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/jszip.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/vfs_fonts.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/responsive.bootstrap.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.scroller.min.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.colVis.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>

    <script src="/v2/vendor/ubold/assets/pages/datatables.init.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="/v2/js/handlebars.js"></script>

    <script src="/v2/vendor/ubold/assets/plugins/moment/moment.js"></script>
    <script src="/v2/vendor/ubold/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
@endsection

@section('inline_scripts')
    <script type="text/javascript">
        $('.select2').select2();

        $(function () {
            var datatable = $("#dataTables-networks").DataTable({
                searching: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{!! route('networks.dataTables') !!}',
                    data: function (d) {
                        d.name = $('input[name=name]').val();
                        d.status = $('select[name=status]').val();
                        d.auto = $('select[name=auto]').val();
                        d.is_sms_callback = $('select[name=is_sms_callback]').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'click_url', name: 'click_url'},
                    {data: 'auto', name: 'auto'},
                    {data: 'number_click_to_add_conversion', name: 'number_click_to_add_conversion'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, 'desc']]
            });

            $('#search-form').on('submit', function(e) {
                datatable.draw();
                e.preventDefault();
            });

            datatable.on('click', '[id^="btn-connect-"]', function (e) {
                e.preventDefault();

                var url = $(this).data('url');

                $.ajax({
                    url : url,
                    type : 'GET',
                    beforeSend: function (xhr) {
                        var token = $('meta[name="csrf_token"]').attr('content');
                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        }
                    }
                }).always(function (res) {
                    $('#show_howto').html(res.html);
                    $('#list-content-modal').modal({
                        show: 'true'
                    });
                });
            });

            datatable.on('click', '[id^="btn-delete-"]', function (e) {
                e.preventDefault();

                var url = $(this).data('url');

                swal({
                    title: "Bạn có muốn xóa network này?",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Accept!"
                }).then(function () {
                    $.ajax({
                        url : url,
                        type : 'DELETE',
                        beforeSend: function (xhr) {
                            var token = $('meta[name="csrf_token"]').attr('content');
                            if (token) {
                                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            }
                        }
                    }).always(function (data) {
                        datatable.draw();
                    });
                });
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
@endsection