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
            <h1 class="page-header">{{strtoupper($model)}}</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
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
