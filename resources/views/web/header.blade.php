<div class="header">
    <div class="header-top">
        <div class="container">
            <div class="search">
                <form>
                    <input type="text" value="Search " onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}">
                    <input type="submit" value="Go">
                </form>
            </div>
            <div class="header-left">
                <ul>
                    <li ><a href="{{url('dang-nhap')}}">Login</a></li>
                    <li><a  href="{{url('dang-ky')}}">Register</a></li>

                </ul>
                <div class="cart box_1">
                    <a href="{{url('checkout')}}">
                        <h3> <div class="total">
                                <span class="simpleCart_total"></span> (<span id="simpleCart_quantity" class="simpleCart_quantity"></span> items)</div>
                            <img src="{{url('web/images/cart.png')}}" alt=""/></h3>
                    </a>
                    <p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>

                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="container">
        <div class="head-top">
            <div class="logo">
                <a href="{{url('/')}}"><img src="{{url('web/images/logo.png')}}" alt=""></a>
            </div>
            <div class=" h_menu4">
                <ul class="memenu skyblue">
                    <li class="active grid"><a class="color8" href="{{url('/')}}">Trang chu</a></li>
                    @foreach (\App\Site::headerCategory() as $headerCategory)
                    <li>
                        <a class="color1" href="{{url(\App\Site::getCategoryUrl($headerCategory))}}">{{$headerCategory->name}}</a>
                    </li>
                    @endforeach

                    <li><a class="color6" href="{{url('lien-he')}}">Liên hệ</a></li>
                </ul>
            </div>

            <div class="clearfix"> </div>
        </div>
    </div>

</div>