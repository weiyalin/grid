@extends('layout')
@section('header')
    <style>
        .row{padding:20px 50px;}
        .container .row img{height:100px;}
        .container .row a{display: inline-block; height:100%; color:#333; font-size:16px;
                        width:150px; padding:20px 10px 10px 5px;border-radius:5px; color:#fff;}
        .container .row a div{padding:20px 0;}
        .container .row a:hover{
            -webkit-transform: scale(1.1,1.1);
            -moz-transform: scale(1.1,1.1);
            -ms-transform: scale(1.1,1.1);
            -o-transform: scale(1.1,1.1);
            transform: scale(1.1,1.1);
        }
        .desc{text-align:left; color:#fff;margin-top:10px;}

        .box_1{background-color:#4E7EDC;}
        .box_2{background-color:#1BC1B1;}
        .box_3{background-color:#8BCF43;}
        .box_4{background-color:#CCB59D;}
        .box_5{background-color:#EFBC00;}
    </style>
@endsection
@section('content')
    <div class="container text-center" style="margin-top:70px;">
        <div class="row show-grid">
            <div class="col-sm-2 col-sm-offset-1">
                <a href="/data_population" class="box_1">
                    <img src="ui_resource/img/data_center/changzhurenkou.png"/>
                    <div>
                        常驻人口管理
                    </div>
                </a>
                <div class="desc">
                    {{--查看常住人口分布图，根据网格区域、姓名、身份证号、手机号等方式查看人口信息，并提供对常住人口进行新增、属性修改、删除等功能。--}}
                </div>
            </div>
            <div class="col-sm-2">
                <a href="/data_special_list"  class="box_2">
                    <img src="ui_resource/img/data_center/teshurenqun.png"/>
                    <div>
                        特殊人群管理
                    </div>
                </a>
                <div class="desc">
                    {{--查看特殊人群分布图，根据网格区域、姓名、身份证号、人群类别等方式筛选特殊人群、查看该用户的详细信息。--}}
                </div>
            </div>
            <div class="col-sm-2">
                <a href="/data_emphases_list"  class="box_3">
                    <img src="ui_resource/img/data_center/zhongdianrenqun.png"/>
                    <div>
                        重点人群管理
                    </div>
                </a>
                <div class="desc">
                    {{--查看重点人群分布图，根据网格区域、姓名、身份证号、人群类别等方式筛选重点人群列表、查看某一用户的详细信息。--}}
                </div>
            </div>
            <div class="col-sm-2">
                <a href="/data_fixed_list"  class="box_4">
                    <img src="ui_resource/img/data_center/liudongrenkou.png"/>
                    <div>
                        流动人口管理
                    </div>
                </a>
                <div class="desc">
                    {{--查看流动人群分布图，根据网格区域、姓名、身份证号等方式筛选流动人口列表、查看某一用户的详细信息。--}}
                </div>
            </div>
            {{--<div class="col-sm-2">--}}
                {{--<a href="#"  class="box_5">--}}
                    {{--<img src="ui_resource/img/data_center/weixin.png"/>--}}
                    {{--<div>--}}
                        {{--流动人口微信平台--}}
                    {{--</div>--}}
                {{--</a>--}}
                {{--<div class="desc">--}}
                    {{--建立流动人口自主信息申报微信平台，实现对流动人口的自主新增、修改和管理。--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@endsection