<p id="document{{$content->id}}">
    Thông tin kết nối : <br>
    1. Khi có traffic bên mình sẽ chuyển người dùng sang đường link : {{$content->click_url}}&uid=xxxxxx<br/>

    Trong đó tham số <b>uid</b> là tham số hệ thống bên mình tự sinh cho mỗi lần click.<br/>

    @if ($content->is_sms_callback == 1)
        2. Khi có conversion success, bên bạn gọi đường link sau với method "GET" : <br/>

        {{url('smscallback?network_id='.$content->id.'&sign={sign}')}}<br/>

        Trong đó :<br/>
        - Tham số sign là tham số do bên bạn tự sinh unique với mỗi lần conversion success (Dùng cho mục đích đối xoát sản lượng giửa 2 bên sau này)<br/>
        3. Ví dụ :  <br/>

        - User A truy cập vào đường link dịch vụ bên mình , bên mình sẽ chuyển hướng user A sang đường link {{$content->click_url}}&uid=12345<br/>
        - Khi User A đăng ký sử dụng thành công bên bạn, bên bạn gọi tới URL :  {{url('smscallback?network_id='.$content->id.'&sign=Z123A')}}<br/>

        Trong đó "Z123A" là mã tự sinh (unique) của bên bạn cho lần đăng ký thành công của user A.<br/>

        * Lưu ý khi gọi callback URL không dùng redirect trực tiếp user sang mà gọi bằng file_get_contents từ server với IP : {{$content->callback_allow_ip}} đã cung cấp.
    @elseif ($content->is_sms_callback == 0)

        2. Khi có conversion success, bên bạn gọi đường link sau với method "GET" : <br/>

        {{url('callback?uid={uid}&sign={sign}')}}<br/>

        Trong đó :<br/>
        - Tham số uid là tham số mình truyền sang với lần click đó (đã mô tả ở mục 1).<br/>
        - Tham số sign là tham số do bên bạn tự sinh unique với mỗi lần conversion success (Dùng cho mục đích đối xoát sản lượng giửa 2 bên sau này)<br/>
        3. Ví dụ :  <br/>

        - User A truy cập vào đường link dịch vụ bên mình , bên mình sẽ chuyển hướng user A sang đường link {{$content->click_url}}&uid=12345<br/>
        - Khi User A đăng ký sử dụng thành công bên bạn, bên bạn gọi tới URL :  {{url('callback?uid=12345&sign=Z123A')}}<br/>

        Trong đó "Z123A" là mã tự sinh (unique) của bên bạn cho lần đăng ký thành công của user A.<br/>

        * Lưu ý khi gọi callback URL không dùng redirect trực tiếp user sang mà gọi bằng file_get_contents từ server với IP : {{$content->callback_allow_ip}} đã cung cấp.

    @else
        2. Khi có conversion success, bên bạn sẽ lưu trữ bên hệ thống và không gọi trực tiếp sang bên mình.<br/><br/>

        Bên mình sẽ tự sử dụng API với đường link <b>{{$content->cron_url}}</b> được cung cấp để lấy danh sách conversion theo một khoảng thời gian nhất dịnh.<br/><br/>

        *    Lưu ý hãy mở quyền truy cập cho IP Server : <b>42.112.31.173</b> cho API.


    @endif

</p>