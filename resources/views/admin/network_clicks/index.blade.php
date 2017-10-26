@extends('admin.template')
@section('content')

    <style>
        .qbreak {
            word-wrap: break-word;
            display: inline-block;
            width: 300px;
        }
    </style>



    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Danh sách clicks</h1>
        </div>
        <div class="col-lg-6">
            <h2>Thống kê theo network</h2>
            {!! Form::open(['method' => 'GET', 'url' => url('admin/network_clicks')]) !!}

            <div class="form-group">
                {!! Form::label('network_id', 'Network') !!}
                {!! Form::select('network_id', [ '' => '=== Chọn Network ==='] + $networks, isset($networkId) ? $networkId : null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('start', 'Ngày bắt đầu') !!}
                {!! Form::text('start', $start, ['class' => 'form-control', 'id' => 'start-network-date']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('end', 'Ngày kết thúc') !!}
                {!! Form::text('end',  $end, ['class' => 'form-control', 'id' => 'end-network-date']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('conversion', 'Conversion') !!}
                {!! Form::select('conversion', [ '' => '=== Chọn loại ===', 1 => 'Có', 0 => 'Không'], isset($conversion) ? $conversion : null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Thống kê', ['class' => 'btn btn-primary form-control']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @foreach ($networkConversions as $networkConversion)
                        <p class="fa fa-fighter-jet"><b>{{ $networkConversion->network_name }}</b> : {{ $networkConversion->total }}</p>
                    @endforeach
                        @if ($showExport)
                        <a href="{{ Request::fullUrl().'&export=true' }}" class="btn download"><button type="button" class="btn btn-info">Export</button></a>
                        @endif
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
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
                            <tbody>
                            @foreach($contents as $content)
                                <tr>
                                    <td width="5%">{{$content->id}}</td>

                                    <td width="10%">{{$content->network->name}}</td>
                                    <td width="15%">
                                        <p class="qbreak">
                                            Link : {!! \App\Site::displayJson($content->log_click_url) !!}<br/>
                                            Time : {{$content->camp_time}}<br/>
                                            IP : {{$content->camp_ip}}<br/>
                                        </p>
                                    </td>
                                    <td width="20%">
                                        <p class="qbreak">{{$content->redirect_to_end_point_url}}</p>

                                    </td>
                                    <td width="20%">
                                        <p class="qbreak">
                                            Link : {!! \App\Site::displayJson($content->log_callback_url) !!}<br/>
                                            Time : {{$content->callback_time}}<br/>
                                            IP : {{$content->callback_ip}}<br/>
                                        </p>
                                    </td>
                                    <td width="15%">
                                        <p class="qbreak">{!! \App\Site::displayJson($content->call_start_point_url) !!}</p>
                                    </td>
                                    <td width="15%">
                                        <p class="qbreak"> {{$content->callback_response}}</p>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">{!!$contents->render()!!}</div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function(){
            jQuery.datetimepicker.setLocale('vi');

            jQuery('#start-network-date, #end-network-date').datetimepicker({
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
                timepicker:true,
                minDate:'1970-01-02',
                format:'Y-m-d H:i:s'
            });
        });
    </script>
@endsection
