<div class="navbar-custom">
    <?php
        $currentUser = auth()->user();
    ?>
    <div class="container">
        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu">
                <li class="has-submenu">
                    <a href="/admin"><i class="md md-dashboard"></i>Trang chủ</a>
                </li>

                <li class="has-submenu">
                    <a href="{{ route('network_clicks.index')}}"><i class="md md-view-list"></i>Thống kê</a>
                </li>

                <li class="has-submenu">
                    <a href="{{ url('/reports/create')}}"><i class="md md-folder"></i>Nhập tay</a>
                </li>

                <li class="has-submenu">
                    <a href="{{ url('/reports')}}"><i class="md md-album"></i>Sản lượng nhập tay</a>
                </li>

                <li class="has-submenu">
                    <a href="{{ url('/traffics')}}"><i class="md md-color-lens"></i>Lưu lượng</a>
                </li>



            @if ($currentUser->isAdmin())

                <li class="has-submenu">
                    <a href="#"><i class="md md-class"></i>Hệ thống</a>
                    <ul class="submenu">
                        @if (auth()->user()->isAdmin())
                            <li><a href="{{ url('/users')}}">User</a></li>
                        @endif
                        <li><a href="{{ url('/networks')}}">Network</a></li>
                        <li><a href="{{ url('/connections')}}">Connection</a></li>
                    </ul>
                </li>
            @endif
            </ul>
            <!-- End navigation menu        -->
        </div>
    </div> <!-- end container -->
</div> <!-- end navbar-custom -->
