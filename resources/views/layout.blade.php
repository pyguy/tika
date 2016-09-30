<!DOCTYPE html>
<html lang="EN" dir="ltr">
<head>

    <meta charset="UTF-8" />
    <title>Tika server monitoring</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/menu.css" rel="stylesheet" type="text/css" />
    @yield('header')

</head>


<body>


<header id="topnav">

    <div class="topbar-main">

        <div class="container">

            <div class="logo">
                <a href="index.html" class="logo">
                    <i class="fa fa-dashboard"></i>
                    <span>Tika server monitoring</span>
                </a>
            </div>

            <div class="menu-extras">

                <ul class="nav navbar-nav navbar-right pull-right">

                    <li class="dropdown">
                        <a href="#"
                           class="dropdown-toggle waves-effect waves-light profile"
                           data-toggle="dropdown"
                           aria-expanded="true" >
                            <img src="uploads/avatars/alireza.jpg"
                                 alt="user-img"
                                 class="img-circle"
                            />
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="fa fa-gears"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="fa fa-lock"></i>
                                    Lock screen
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="fa fa-power-off"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>

        </div>
    </div>

    <div class="navbar-custom">
        <div class="container">
            <div id="navigation">

                <ul class="navigation-menu">

                    <li>
                        <a href="index.html">
                            <i class="fa fa-globe"></i>
                            Network Usage
                        </a>
                    </li>

                    <li>
                        <a href="index.html">
                            <i class="fa fa-renren"></i>
                            Traceroute
                        </a>
                    </li>

                    <li>
                        <a href="index.html">
                            <i class="fa fa-retweet"></i>
                            Services
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </div>
</header>

@yield('main-container')

<script src="/assets/js/jquery-2.1.0.min.js"></script>
<script src="/assets/js/tika.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/jquery.blockUI.js"></script>
@yield('footer')

</body>
</html>