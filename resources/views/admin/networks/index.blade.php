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
                                <th>Allow IPs</th>
                                <th>Click Url</th>
                                <th>CallBack Url</th>
                                <th>Map Params</th>
                                <th>Extend Params</th>
                                <th>Link Give to Click Partner</th>
                                <th>Link Give to Callback Partner</th>
                                <th>Is SMS Callback</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contents as $content)
                                <tr>
                                    <td>{{$content->id}}</td>
                                    <td>{{$content->name}}</td>
                                    <td>{{$content->callback_allow_ip}}</td>
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

                                      <a document-id="document{{$content->id}}" class="document-button">Toggle Document</a><br/>

                                      <p id="document{{$content->id}}" style="display: none">
                                          Thông tin kết nối : <br>
                                          1. Khi có traffic bên mình sẽ chuyển người dùng sang đường link : {{$content->click_url}}&uid=xxxxxx<br/>

                                          Trong đó tham số <b>uid</b> là tham số hệ thống bên mình tự sinh cho mỗi lần click.<br/>

                                          @if ($content->is_sms_callback)
                                              2. Khi có conversion success, bên bạn gọi đường link sau với method "GET" : <br/>

                                              {{url('smscallback?network_id='.$content->id.'&sign={sign}')}}<br/>

                                              Trong đó :<br/>
                                              - Tham số sign là tham số do bên bạn tự sinh unique với mỗi lần conversion success (Dùng cho mục đích đối xoát sản lượng giửa 2 bên sau này)<br/>
                                              3. Ví dụ :  <br/>

                                              - User A truy cập vào đường link dịch vụ bên mình , bên mình sẽ chuyển hướng user A sang đường link {{$content->click_url}}&uid=12345<br/>
                                              - Khi User A đăng ký sử dụng thành công bên bạn, bên bạn gọi tới URL :  {{url('smscallback?network_id='.$content->id.'&sign=Z123A')}}<br/>

                                              Trong đó "Z123A" là mã tự sinh (unique) của bên bạn cho lần đăng ký thành công của user A.<br/>
                                          @else

                                          2. Khi có conversion success, bên bạn gọi đường link sau với method "GET" : <br/>

                                          {{url('callback?uid={uid}&sign={sign}')}}<br/>

                                          Trong đó :<br/>
                                          - Tham số uid là tham số mình truyền sang với lần click đó (đã mô tả ở mục 1).<br/>
                                          - Tham số sign là tham số do bên bạn tự sinh unique với mỗi lần conversion success (Dùng cho mục đích đối xoát sản lượng giửa 2 bên sau này)<br/>
                                          3. Ví dụ :  <br/>

                                          - User A truy cập vào đường link dịch vụ bên mình , bên mình sẽ chuyển hướng user A sang đường link {{$content->click_url}}&uid=12345<br/>
                                          - Khi User A đăng ký sử dụng thành công bên bạn, bên bạn gọi tới URL :  {{url('callback?uid=12345&sign=Z123A')}}<br/>

                                          Trong đó "Z123A" là mã tự sinh (unique) của bên bạn cho lần đăng ký thành công của user A.<br/>

                                          @endif

                                          * Lưu ý khi gọi callback URL không dùng redirect trực tiếp user sang mà gọi bằng file_get_contents từ server với IP : {{$content->callback_allow_ip}} đã cung cấp.

                                      </p>
                                    </td>

                                    <td>{{$content->is_sms_callback ? 'Using' : 'Not Use'}}</td>
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

            $('a.document-button').click(function(){
                var document_id = $(this).attr('document-id');
                $('p#' + document_id).toggle();
            });
        });
    </script>
@endsection