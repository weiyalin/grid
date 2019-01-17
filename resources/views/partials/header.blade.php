<style>
    body:before {
        background: url(/ui_resource/img/b55g.png) center top;
        background-size: cover;
        background-repeat: no-repeat;
        background-size: 100%;
    }

    .page-body, .page-sidebar a {
        background: transparent !important;
    }

    .page-sidebar:before {
        /*background: url(http://218.29.138.111/resources/login/images/00-.jpg) center top;*/
    }

    .page-slidebar .menu-text {
        /*color: #fff !important;*/
    }

    .page-sidebar .sidebar-menu a {
        /*color: #fff !important;*/
    }

    .page-sidebar .sidebar-menu .submenu {
        /*background: transparent !important;*/
    }
</style>
<link rel="shortcut icon" href="assets/img/icon.ico" type="image/x-icon">

<!--Basic Styles-->
<link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
<link id="bootstrap-rtl-link" href="" rel="stylesheet"/>
<link href="assets/css/font-awesome.min.css" rel="stylesheet"/>
<link href="assets/css/weather-icons.min.css" rel="stylesheet"/>

<!--Fonts-->
{{--<link href="http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300"--}}
{{--rel="stylesheet" type="text/css">--}}

<!--Beyond styles-->
<link id="beyond-link" href="/assets/css/beyond.min.css" rel="stylesheet"/>
<link href="ui_resource/css/main.css" rel="stylesheet"/>
{{--<link href="assets/css/demo.min.css" rel="stylesheet" />--}}
<link href="assets/css/typicons.min.css" rel="stylesheet"/>
<link href="assets/css/animate.min.css" rel="stylesheet"/>

<link id="skin-link" href="assets/css/skins/qianlv.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="ui_resource/css/custom.css" rel="stylesheet"/>


<!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/datetime/moment.min.js"></script>
{{--<script src="http://cdn.bootcss.com/moment.js/2.5.1/lang/zh-cn.js"></script>--}}
<script src="assets/js/toastr/toastr.js"></script>

<script src="assets/js/skins.min.js"></script>
<script src="assets/js/bootbox/bootbox.js"></script>

{{--<script type="text/javascript" src="assets/js/qiniu_plupload/plupload/plupload.full.min.js"></script>--}}
{{--<script type="text/javascript" src="assets/js/qiniu_plupload/plupload/i18n/zh_CN.js"></script>--}}
{{--<script type="text/javascript" src="assets/js/qiniu_plupload/qiniu.js"></script>--}}

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })
</script>


