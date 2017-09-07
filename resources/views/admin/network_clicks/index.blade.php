@extends('admin.template')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{strtoupper($model)}}</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-heading">

                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Network</th>
                                <th>Log StartPoint</th>
                                <th>Redirect EndPoint Url</th>
                                <th>EndPoint Sign</th>
                                <th>EndPoint IP</th>
                                <th>CallBack StartPoint Url</th>
                                <th>CallBack StartPoint Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contents as $content)
                                <tr>
                                    <td>{{$content->id}}</td>
                                    <td>{{$content->network->name}}</td>
                                    <td>{{$content->log_click_url}}</td>
                                    <td>{{$content->redirect_to_end_point_url}}</td>
                                    <td>{{$content->sign}}</td>
                                    <td>{{$content->callback_ip}}</td>
                                    <td>{{$content->call_start_point_url}}</td>
                                    <td>{{ ($content->call_start_point_status) ? 'Valid' : 'Invalid' }}</td>
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
