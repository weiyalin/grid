<!DOCTYPE html>


<html xmlns="http://www.w3.org/1999/xhtml">


<!-- Head -->
<head>
    <meta charset="utf-8" />
    <title>综治管理平台-{{ $title }}</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="description" content="blank page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    @include('partials.header')
    @yield('header')

    <style>
        /*.page-sidebar {*/
            /*width:150px !important;*/
        /*}*/
    </style>
</head>
<!-- /Head -->
<!-- Body -->
<body>
<!-- Loading Container -->
<div class="loading-container">
    <div class="loading-progress">
        <img src="assets/img/default_loading1.svg"/>
        {{--<div class="rotator">--}}
            {{--<div class="rotator">--}}
                {{--<div class="rotator colored">--}}
                    {{--<div class="rotator">--}}
                        {{--<div class="rotator colored">--}}
                            {{--<div class="rotator colored"></div>--}}
                            {{--<div class="rotator"></div>--}}
                        {{--</div>--}}
                        {{--<div class="rotator colored"></div>--}}
                    {{--</div>--}}
                    {{--<div class="rotator"></div>--}}
                {{--</div>--}}
                {{--<div class="rotator"></div>--}}
            {{--</div>--}}
            {{--<div class="rotator"></div>--}}
        {{--</div>--}}
        {{--<div class="rotator"></div>--}}
    </div>
</div>
<!--  /Loading Container -->

<!-- Navbar -->
@include('partials.navbar')
<!-- /Navbar -->

<!-- Main Container -->
<div class="main-container container-fluid">
    <!-- Page Container -->
    <div class="page-container">
        <!-- Page Sidebar -->
        <div class="page-sidebar" id="sidebar">
            <!-- Page Sidebar Header-->
            {{--<div class="sidebar-header-wrapper">--}}
                {{--<input type="text" class="searchinput" />--}}
                {{--<i class="searchicon fa fa-search"></i>--}}
                {{--<div class="searchhelper">Search Reports, Charts, Emails or Notifications</div>--}}
            {{--</div>--}}
            <!-- /Page Sidebar Header -->
            <!-- Sidebar Menu -->
            @include('partials.sidebar')
            <!-- /Sidebar Menu -->
        </div>
        <!-- /Page Sidebar -->
        <!-- Page Content -->
        <div class="page-content">
            <!-- Page Breadcrumb -->
            <div class="page-breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <!-- Sidebar Collapse -->
                        <div class="sidebar-collapse" id="sidebar-collapse">
                            <i class="collapse-icon fa fa-bars"></i>
                        </div>
                    </li>
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{$homeLink}}">{{$homeNav}}</a>
                    </li>
                    @if (empty($subNav) == false)
                        <li>
                            <a href="#">{{$subNav}}</a>
                        </li>
                    @endif
                    <li class="active">{{$activeNav}}</li>
                </ul>
            </div>
            <!-- /Page Breadcrumb -->
            <!-- Page Header -->
            {{--<div class="page-header position-relative">--}}
                {{--<div class="header-title">--}}
                    {{--<h1>--}}
                        {{--{{$homeNav}}--}}
                    {{--</h1>--}}
                {{--</div>--}}
                {{--<!--Header Buttons-->--}}
                {{--<div class="header-buttons">--}}
                    {{--<a class="sidebar-toggler" href="#">--}}
                        {{--<i class="fa fa-arrows-h"></i>--}}
                    {{--</a>--}}
                    {{--<a class="refresh" id="refresh-toggler" href="">--}}
                        {{--<i class="glyphicon glyphicon-refresh"></i>--}}
                    {{--</a>--}}
                    {{--<a class="fullscreen" id="fullscreen-toggler" href="#">--}}
                        {{--<i class="glyphicon glyphicon-fullscreen"></i>--}}
                    {{--</a>--}}
                {{--</div>--}}
                {{--<!--Header Buttons End-->--}}
            {{--</div>--}}
            <!-- /Page Header -->
            <!-- Page Body -->
            <div class="page-body">
                <!-- Your Content Goes Here -->
                @yield('content')
            </div>
            <!-- /Page Body -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Container -->
    <!-- Main Container -->
    @include('partials.footer')
    @yield('footer')
</div>


<!--Page Related Scripts-->

{{--视频轮播图--}}
<link href="/admin/video_center/css/owl.carousel.css" rel="stylesheet">
<link href="/admin/video_center/css/owl.theme.css" rel="stylesheet">
{{--<script src="http://www.sucaihuo.com/Public/js/other/jquery.js"></script>--}}
<script src="/admin/video_center/js/owl.carousel.js"></script>

</body>
<!--  /Body -->
</html>
