@extends('layout')
@section('header')
    <style>
        .container .row a img{width:80px;}
        .container .row a{display: inline-block;padding:25px 40px;
                            color:#fff;margin:20px; border-radius: 5px; font-size:16px;}
        .container .row a:hover{box-shadow:0 0 5px #03b3b2; transform:scale(1.1,1.1)}
        .box_1{background-color:#4E7EDC;}
        .box_2{background-color:#1BC1B1;}
        .box_3{background-color:#8BCF43;}
        .box_4{background-color:#CCB59D;}
        .box_5{background-color:#EFBC00;}
        .box_6{background-color:#6666FF ;}
        .box_7{background-color:#9999FF;}
        .box_8{background-color:#9966FF;}
    </style>
@endsection
@section('content')
    <div class="container text-center" style="max-width: 1100px;margin-top:20px;">
        <div class="row">
            <div class="col-sm-3">
                <a href="/event_pre_determine" class="box_1">
                    <img src="ui_resource/img/event/event_determine.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        事件研判
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/event_already_determine" class="box_2">
                    <img src="ui_resource/img/event/already_determine.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        已研判事件
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/event_auto_determine" class="box_3">
                    <img src="ui_resource/img/event/auto_determine.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        自动研判管理
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/event_recycle_bin" class="box_4">
                    <img src="ui_resource/img/event/recycle_bin.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        回收站
                    </div>
                </a>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-sm-3">
                <a href="/event_feedback_determine" class="box_5">
                    <img src="ui_resource/img/event/feedback_determine.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        反馈处理
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/event_query" class="box_6">
                    <img src="ui_resource/img/event/event_query.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        事件查询
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/event_map" class="box_7">
                    <img src="ui_resource/img/event/event_map.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        地图分布
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/event_union" class="box_8">    <!--没有链接-->
                    <img src="ui_resource/img/event/union_supervise.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        联合执法
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection