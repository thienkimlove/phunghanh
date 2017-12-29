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
    <link href="/v2/js/admin/datetimepicker/build/jquery.datetimepicker.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="/reports/create"><button type="button" class="btn btn-default dropdown-toggle waves-effect" >Tạo mới <span class="m-l-5"><i class="fa fa-plus"></i></span></button></a>
            </div>

            <h4 class="page-title">Danh sách sản lượng nhập tay</h4>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-inline" role="form" id="search-form">



                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Network</label>
                                {!! Form::select('network_id', ['' => '--- Chọn Network ---'] + \App\Network::where('is_sms_callback', 2)->pluck('name','id')->all(), null, ['class' => 'form-control']) !!}
                            </div>


                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Theo ngày</label>
                                <input class="form-control" type="text" id="start-network-date" name="date" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" placeholder="Theo ngày" style="width: 200px;"/>
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
                <p class="text-muted font-13 m-b-30">Total : <span id="total_rows"></span></p>

                <table id="dataTables-reports" class="table table-striped table-bordered table-actions-bar">
                    <thead>
                    <tr>
                        <th width="20%">Date</th>
                        <th width="20%">Network</th>
                        <th width="20%">Phone</th>
                        <th width="20%">SentToPartner</th>
                        <th width="10%">Ngày tạo</th>
                        <th width="10%"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

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

    <script src="/v2/vendor/ubold/assets/plugins/moment/moment.js"></script>
    <script src="/v2/js/admin/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('inline_scripts')
    <script type="text/javascript">
        $('.select2').select2();

        $(function () {
            var datatable = $("#dataTables-reports").DataTable({
                searching: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{!! route('reports.dataTables') !!}',
                    data: function (d) {
                        d.date = $('input[name=date]').val();
                        d.network_id = $('select[name=network_id]').val();
                    }
                },
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'network_name', name: 'network_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'send_to_partner', name: 'send_to_partner'},
                    {data: 'created_at', name: 'created_at'},
                ],
                order: [[3, 'desc']],

                "drawCallback": function( settings) {
                    $('#total_rows').text(settings.fnRecordsDisplay());
                }
            });

            $('#search-form').on('submit', function(e) {
                datatable.draw();
                e.preventDefault();
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



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