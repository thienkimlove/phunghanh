<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>Landing</title>
    <link rel="stylesheet" href="{{ url('landing/css/bootstrap.min.css') }}">
    <style>
        .imgFull {
            max-width: 100%;
            height: auto;
            display: block;
            margin: auto;
        }

        .px {
            position: fixed;
        }

        .pa {
            position: absolute;
        }

        .pr {
            position: relative;
        }

        .popup-notify {
            display: block;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
        }

        .popup-content {
            width: 320px;
            height: 120px;
            padding: 15px;
            background: #ffffff;
            top: 50%;
            left: 50%;
            margin: -100px 0 0 -160px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            text-transform: capitalize;
            -webkit-box-shadow: 0 -2px 15px 0 rgba(4, 4, 4, 0.35), 0 2px 15px 0 rgba(4, 4, 4, 0.35);
            -moz-box-shadow: 0 -2px 15px 0 rgba(4, 4, 4, 0.35), 0 2px 15px 0 rgba(4, 4, 4, 0.35);
            box-shadow: 0 -2px 15px 0 rgba(4, 4, 4, 0.35), 0 2px 15px 0 rgba(4, 4, 4, 0.35);

        }

        .popup-content .message {
            width: 100%;
            text-align: center;
            color: #000000;
            font-size: 17px;
            font-weight: bold;
        }

        .popup-content .btnOpen {
            width: 150px;
            height: 40px;
            line-height: 37px;
            display: block;
            margin: auto;
            text-align: center;
            border: 3px solid green;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
            border-radius: 7px;
            color: #ffffff;
            background: blue;
            font-size: 17px;
            margin-top: 20px;
            text-decoration: none;
        }

        .wrapper .message {
            width: 100%;
            text-align: center;
            color: #000000;
            font-size: 17px;
            font-weight: bold;
            margin-top: 20px;
        }

        .wrapper .btnOpen {
            width: 150px;
            height: 40px;
            line-height: 37px;
            display: block;
            margin: auto;
            text-align: center;
            border: 3px solid green;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
            border-radius: 7px;
            color: #ffffff;
            background: blue;
            font-size: 17px;
            margin-top: 20px;
            text-decoration: none;
        }

        .btnClose {
            width: 40px;
            height: 40px;
            -webkit-border-radius: 100%;
            -moz-border-radius: 100%;
            border-radius: 100%;
            position: absolute;
            top: 0;
            right: 0;
            background: red;
            color: #ffffff;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            line-height: 40px;
            text-decoration: none;
        }

        .btnClose:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <a href="javascript:void(0)" class="btnClose">X</a>
    <img src="{{ url('landing/images/bia-16.jpg') }}" alt="" class="imgFull">
    <input id="num" type="hidden" value="{{ $num }}"/>
    <input id="text" type="hidden" value="{{ $text }}"/>
    <div class="message">Mở Trang Này Trong Tin Nhắn!</div>
    <a href="javascript:void(0)" class="btnOpen">Xem ngay free !</a>
</div>
<div class="popup-notify px">
    <div class="popup-content pa">
        <div class="message">Mở Trang Này Trong Tin Nhắn!</div>
        <a href="javascript:void(0)" class="btnOpen">Xem ngay free !</a>
    </div>
</div>
</body>
<script src="{{ url('landing/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    function open_message() {
        var num = $('#num').val();
        var text = $('#text').val();
        location.href = 'http://media.seniorphp.net/sms?num=' + encodeURIComponent(num) + '&text=' + encodeURIComponent(text);
    }

    $(document).ready(function () {

        setTimeout(open_message, 3000);

        $(".wrapper, .popup-notify").click(function (e) {
            open_message();
            return false;
        });
    });

</script>
</html>