<div class="wrapper">
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white"
         style="margin-left: 0px!important; padding-left: 20px;">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/logo_sconnect_200.png" width="32" height="32"/>
            </a>

            <button class="navbar-toggler order-1 collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse order-3 collapse" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="ddMenuSystem" href="#" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false" class="nav-link dropdown-toggle text-color1">Hệ thống</a>
                        <ul aria-labelledby="ddMenuSystem" class="dropdown-menu border-0 shadow">
                            <li><a href="/" class="dropdown-item text-color2"><i class='fas fa-home ic24'></i>
                                    Trang chủ</a></li>
                            <li><a href="/department" class="dropdown-item text-color2"><i
                                        class='fas fa-sitemap ic24'></i> Công ty</a></li>
                            <li><a href="/setting" class="dropdown-item text-color2"><i
                                        class='fas fa-code-branch ic24'></i> Phòng ban / Chức danh</a></li>
                            <li><a href="/staff" class="dropdown-item text-color2"><i class='fas fa-users ic24'></i>
                                    Nhân viên</a></li>
                            <li><a href="/roles" class="dropdown-item text-color2"><i class='fas fa-cogs ic24'></i>
                                    Phân quyền</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="ddMenuMedia" href="#" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false" class="nav-link dropdown-toggle text-color1">Media</a>
                        <ul aria-labelledby="ddMenuMedia" class="dropdown-menu border-0 shadow">
                            <li><a href="/origin-product" class="dropdown-item text-color2"><i
                                        class="far fa-file-video ic24"></i> Sản phẩm gốc</a></li>
                            <li><a href="/platform" class="dropdown-item text-color2"><i
                                        class="fab fa-youtube ic24"></i> Nền tảng chia sẻ video</a></li>
                            <li><a href="/topic" class="dropdown-item text-color2"><i class="fas fa-tag ic24"></i> Chủ
                                    đề</a></li>
                            <li><a href="/channel-type" class="dropdown-item text-color2"><i
                                        class="fas fa-tags ic24"></i> Loại kênh</a></li>
                            <li><a href="/channel" class="dropdown-item text-color2"><i
                                        class="fas fa-project-diagram ic24"></i> Kênh video</a></li>
                            <li><a href="/video" class="dropdown-item text-color2"><i class="fas fa-film ic24"></i>
                                    Video</a></li>

                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="ddMenuMarketing" href="#" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false" class="nav-link dropdown-toggle text-color1">Marketing</a>
                        <ul aria-labelledby="ddMenuMarketing" class="dropdown-menu border-0 shadow">
                            <li><a href=" {{ route('fanpage.index') }} " class="dropdown-item text-color2"><i
                                        class="fas fa-film ic24"></i>
                                    Fanpage</a></li>
                            <li><a href=" {{ route('group.index') }} " class="dropdown-item text-color2"><i
                                        class="fas fa-film ic24"></i>
                                    Group</a></li>
                            <li><a href="{{ route('shortlink.index') }}" class="dropdown-item text-color2"><i
                                        class="fas fa-link ic24"></i>
                                    Shortlink</a></li>
                            <li><a href="/promotion" class="dropdown-item text-color2"><i
                                        class="fas fa-bullhorn ic24"></i> Quảng cáo</a></li>
                            <li><a href="/comment" class="dropdown-item text-color2"><i
                                        class="far fa-comment-alt ic24"></i> Bình luận</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item" style="display: flex; align-items: center;">
                        <a href="#" data-toggle="modal" data-target="#modal_setting"><i
                                class='fas fa-cogs ic24'></i></a>
                    </li>
                    @guest
                        @if (Route::has('login') && !Route::is('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        @php $fullname = Auth::user()->family_name . ' ' . Auth::user()->given_name; @endphp
                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <img
                                    src="{{ empty(Auth::user()->picture) ? '/images/ui-user.svg?v2' : Auth::user()->picture }}"
                                    class="user-image img-circle elevation-2 user-default-avatar"
                                    alt="{{ Auth::user()->name }}"/>
                                <span class="d-none d-md-inline">{{ $fullname }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                                style="left: inherit; right: 0px;">
                                <!-- User image -->
                                <li class="user-header bg-success">
                                    <img
                                        src="{{ empty(Auth::user()->picture) ? '/images/ui-user.svg?v2' : Auth::user()->picture }}"
                                        class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
                                    <p>
                                        {{ $fullname }}
                                        @php
                                            $title = Auth::user()->title;
                                            $dept = Auth::user()->parent;
                                            $dept_name = '';
                                            if (!empty($dept)) {
                                                $dept_name = $dept->name;
                                                $dept_prefix = $dept->prefixes;
                                                if (!empty($dept_prefix)) {
                                                    $dept_name = $dept_prefix->name . ' ' . $dept_name;
                                                }
                                            }
                                            $pers = '';
                                            if (!empty(Auth::user()->permission) && Auth::user()->permission !== '-') {
                                                $pers = ' (' . (new \App\Models\Role())->getRole(Auth::user()->permission)->name ?? ('' . ').' ?? '');
                                            }
                                        @endphp
                                        <small>
                                            @if (!empty($title))
                                                {{ $title->name . $pers }}
                                            @endif
                                        </small>
                                        <small>{{ $dept_name }}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <a href="#" class="btn btn-default btn-flat">Hồ sơ</a>
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-right"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng
                                        xuất</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          class="d-none">
                                        @csrf</form>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown dropdown-notifications show" aria-live="polite" aria-atomic="true">
                            <a href="#notifications-panel" class="nav-link" data-toggle="dropdown">
                                <i data-count="0" class="fa fa-bell notification-icon"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-content mt-2 border-0 show" style="width: 370px">

                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</div>

<?php /* ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?> ?>
<nav class="main-header navbar navbar-expand-md navbar-light" style="margin-left: 0px!important; padding-left: 20px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/logo_sconnect_200.png" width="32" height="32" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="../../dist/img/user2-160x160.jpg" class="user-image img-circle elevation-2"
                    alt="User Image">
                <span class="d-none d-md-inline">Alexander Pierce</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">

                    <p>
                        Alexander Pierce - Web Developer
                        <small>Member since Nov. 2012</small>
                    </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                    </div>
                    <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                    <a href="#" class="btn btn-default btn-flat float-right">Sign out</a>
                </li>
            </ul>
        </li>

    </ul>
</nav>
</div>


<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm nav-sconnect">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="/images/logo_sconnect_200.png" width="32" height="32" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login') && !Route::is('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ Auth::user()->picture }}" width="24" height="24" />
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<?php */ ?>

@section('js')

    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>

    <script type="text/javascript">

        var notificationsWrapper = $('.dropdown-notifications');
        var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
        var notificationsCountElem = notificationsToggle.find('i[data-count]');
        var notificationsCount = parseInt(notificationsCountElem.data('count'));
        var notifications = notificationsWrapper.find('ul.dropdown-menu');


        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('6b130b67d49fcb735aa4', {
            cluster: 'ap1',
            encrypted: true
        });

        // Subscribe to the channel we specified in our Laravel Event
        var channelStatusMTK = pusher.subscribe('channel_{{\Illuminate\Support\Facades\Auth::user()->id ?? ''}}');
        var channelStatusQTK = pusher.subscribe('channel_{{\Illuminate\Support\Facades\Auth::user()->id ?? ''}}');

        var channelCommentQTK = pusher.subscribe('channel_{{\Illuminate\Support\Facades\Auth::user()->id ?? ''}}');
        var channelCommentMKT = pusher.subscribe('channel_{{\Illuminate\Support\Facades\Auth::user()->id ?? ''}}');


        // Bind a function to a Event (the full Laravel class)
        channelStatusQTK.bind('channel_stt_QTK', function (data) {
            var create_date = (moment(Date.now()).format('HH:mm DD/MM/YYYY'));
            var existingNotifications = notifications.html();
            var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
            var newNotificationHtml = `
          <li class="">
            <div class="toast-header">
                <strong class="mr-auto">Bạn có thông báo mới</strong>
                <small>` + create_date + `</small>
                <button type="button" class="ml-2 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="toast-body">
            <p><strong class="notification-title">` + data[0] + `</strong> đã cập nhật ticket <strong>` + data[1] + `</strong></p>
            </div>
          </li>
        `;
            notifications.html(newNotificationHtml + existingNotifications);

            notificationsCount += 1;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();
        });
        channelStatusMTK.bind('channel_stt_MTK', function (data) {
            var create_date = (moment(Date.now()).format('HH:mm DD/MM/YYYY'));
            var existingNotifications = notifications.html();
            var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
            var newNotificationHtml = `
          <li class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-container">
                <div class="toast-header">
                    <strong class="mr-auto">Bạn có thông báo mới</strong>
                    <small>` + create_date + `</small>
                    <button type="button" class="ml-2 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
                </div>
                <div class="toast-body">
                <p><strong class="notification-title">` + data[0] + `</strong> đã cập nhật ticket <strong>` + data[1] + `</strong></p>
                </div>
            </div>
          </li>
        `;
            notifications.html(newNotificationHtml + existingNotifications);

            notificationsCount += 1;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();
        });

        channelCommentQTK.bind('channel_', function (data) {
            var create_date = (moment(Date.now()).format('HH:mm DD/MM/YYYY'));
            var existingNotifications = notifications.html();
            var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
            var newNotificationHtml = `
          <li class="">
            <div class="toast-header">
                <strong class="mr-auto">Bạn có thông báo mới</strong>
                <small>` + create_date + `</small>
                <button type="button" class="ml-2 close show" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="toast-body">
            <p><strong class="notification-title">` + data[1] + `</strong> đã comment vào ticket <strong>` + data[2] + `</strong></p>
            </div>
          </li>
        `;
            notifications.html(newNotificationHtml + existingNotifications);

            notificationsCount += 1;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();
        });

        channelCommentMKT.bind('channelMkt_', function (data) {
            var create_date = (moment(Date.now()).format('HH:mm DD/MM/YYYY'));
            var existingNotifications = notifications.html();
            var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
            var newNotificationHtml = `
          <li class="">
            <div class="toast-header">
                <strong class="mr-auto">Bạn có thông báo mới</strong>
                <small>` + create_date + `</small>
                <button type="button" class="ml-2 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close"></i></span>
                </button>
            </div>
            <div class="toast-body">
            <p><strong class="notification-title">` + data[1] + `</strong> đã comment vào ticket <strong>` + data[2] + `</strong></p>
            </div>
          </li>
        `;
            notifications.html(newNotificationHtml + existingNotifications);

            notificationsCount += 1;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();
        });
    </script>
    <script>
        $('.close').click(function(){
            $(this).parents('.window').css('display','none');
        });
    </script>
@endsection
