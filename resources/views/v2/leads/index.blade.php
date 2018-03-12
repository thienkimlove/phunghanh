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

@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <h4 class="page-title">Danh sách Clicks</h4>

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
                                {!! Form::select('network_id', \App\Site::getNetworks(), null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Theo ngày</label>
                                <input class="form-control input-daterange-datepicker" type="text" name="date" value="{{ \Carbon\Carbon::today()->format('d/m/Y') }} - {{ \Carbon\Carbon::today()->format('d/m/Y') }}" placeholder="Theo ngày" style="width: 200px;"/>
                            </div>


                            <div class="form-group m-l-10">
                                <label class="sr-only" for="">Conversion</label>
                                {!! Form::select('conversion', ['' => '--- Chọn Conversion ---'] + [1 => 'Có Conversion', 0 => 'Không có conversion'], null, ['class' => 'form-control']) !!}
                            </div>


                            <button type="submit" class="btn btn-success waves-effect waves-light m-l-15">Tìm kiếm</button>

                        </form>

                        <div class="form-group pull-right">
                            {!! Form::open(['route' => 'network_clicks.export', 'method' => 'get', 'role' => 'form', 'class' => 'form-inline', 'id' => 'export-form']) !!}


                            {{Form::hidden('filter_network_id', null)}}
                            {{Form::hidden('filter_date', null)}}
                            {{Form::hidden('filter_conversion', null)}}

                            <button class="btn btn-danger waves-effect waves-light m-t-15" value="export" type="submit" name="export">
                                <i class="fa fa-download"></i>&nbsp; Xuất Excel
                            </button>
                            {!! Form::close() !!}

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <p class="text-muted font-13 m-b-30">Total : <span id="total_rows"></span></p>
                <table id="dataTables-clicks" class="table table-striped table-bordered table-actions-bar">
                    <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Network</th>
                        <th width="15%">StartToCamp</th>
                        <th width="20%">CampToEnd</th>
                        <th width="20%">EndToCamp</th>
                        <th width="15%">CampToStart</th>
                        <th width="15%">StartResponse</th>
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
@endsection

@section('inline_scripts')
    <script type="text/javascript">
        $('.select2').select2();

        $(function () {
            var datatable = $("#dataTables-clicks").DataTable({
                searching: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{!! route('network_clicks.dataTables') !!}',
                    data: function (d) {
                        d.date = $('input[name=date]').val();
                        d.network_id = $('select[name=network_id]').val();
                        d.conversion = $('select[name=conversion]').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'network_name', name: 'network_name'},
                    {data: 'start_to_camp', name: 'start_to_camp'},
                    {data: 'camp_to_end', name: 'camp_to_end'},
                    {data: 'end_to_camp', name: 'end_to_camp'},
                    {data: 'camp_to_start', name: 'camp_to_start'},
                    {data: 'start_response', name: 'start_response'}
                ],
                order: [[1, 'desc']],
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

        $('.input-daterange-datepicker').daterangepicker({
            autoUpdateInput: false,
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-default',
            cancelClass: 'btn-white',
            separator: ' to ',
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Submit',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        });

        $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('.input-daterange-datepicker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('#export-form').on('submit', function (e) {
            $('input[name=filter_network_id]').val($('select[name=network_id]').val());
            $('input[name=filter_conversion]').val($('select[name=conversion]').val());
            $('input[name=filter_date]').val($('input[name=date]').val());

            $(this).submit();
            datatable.draw();
            e.preventDefault();
        });

    </script>
@endsection