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
                    <div class="input-group custom-search-form">
                        {!! Form::open(['method' => 'GET', 'route' =>  [$model.'.index'] ]) !!}
                        <span class="input-group-btn">
                            <input type="text" value="{{$searchContent}}" name="q" class="form-control" placeholder="Search ..">

                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Click Url</th>
                                <th>CallBack Url</th>
                                <th>Map Params</th>
                                <th>Extend Params</th>
                                <th>Link Give to Click Partner</th>
                                <th>Link Give to Callback Partner</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contents as $content)
                                <tr>
                                    <td>{{$content->id}}</td>
                                    <td>{{$content->name}}</td>
                                    <td>{{$content->click_url}}</td>
                                    <td>{{$content->callback_url}}</td>

                                    <td>
                                      {{$content->map_params}}
                                    </td>

                                    <td>
                                        {{$content->extend_params}}
                                    </td>

                                    <td>
                                        <?php $detailUrl = ''; foreach (explode(',',$content->map_params) as $couple) {
                                            $tempCouple = explode(':', $couple);
                                            $detailUrl .='&'.$tempCouple[0].'={'.$tempCouple[0].'}';
                                        } ?>
                                        {{url('camp?network_id='.$content->id.$detailUrl)}}
                                    </td>

                                    <td>
                                        {{url('callback?uid={uid}')}}
                                    </td>

                                    <td>{{$content->status ? 'Active' : 'Inactive'}}</td>

                                    <td>
                                        <button id-attr="{{$content->id}}"
                                                content-attr="{{$model}}"
                                                class="btn btn-primary btn-sm edit-content"
                                                type="button">
                                            Edit
                                        </button>&nbsp;

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">{!!$contents->render()!!}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-primary add-content" content-attr="{{$model}}" type="button">Add</button>
                        </div>
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
        $(function(){
            $('.add-content').click(function(){
                window.location.href = window.baseUrl + '/admin/'+$(this).attr('content-attr')+'/create';
            });
            $('.edit-content').click(function(){
                window.location.href = window.baseUrl + '/admin/'+$(this).attr('content-attr')+'/' + $(this).attr('id-attr') + '/edit';
            });
        });
    </script>
@endsection