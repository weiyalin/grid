@extends('layout')
@section('header')
    <style>
        .row div{padding:0 10px;}
        .row a{display:block;
            color:#fff; font-size:18px; padding:20px 0;
            -webkit-border-radius:10px;
            -moz-border-radius:10px;
            border-radius:10px;}
        .box_1 a{background-color:#FB0B60;}
        .box_2 a{background-color: #FDB538;}
        .box_3 a{background-color: #5ED31A;}
        .box_4 a{background-color: #4382BC;}
        .box_5 a{background-color: #BF9000;}
        .row div a:hover{
            -webkit-transform: scale(1.1,1.1);
            -moz-transform: scale(1.1,1.1);
            -ms-transform: scale(1.1,1.1);
            -o-transform: scale(1.1,1.1);
            transform: scale(1.1,1.1);}
    </style>
@endsection
@section('content')
    <div class="container text-center" style="max-width: 1100px;margin-top:100px;">
        <div class="row">
            <div class="col-sm-2 col-sm-offset-1 box_1">
                <a href="/policy_stat">
                    <img src="ui_resource/img/policy/zongtijuece.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        总体决策统计
                    </div>
                </a>
            </div>
            <div class="col-sm-2 box_2">
                <a href="#">
                    <img src="ui_resource/img/policy/shijianyewu.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        事件业务统计
                    </div>
                </a>
            </div>
            <div class="col-sm-2 box_3">
                <a href="#">
                    <img src="ui_resource/img/policy/zhinengbumen.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        职能部门业务统计
                    </div>
                </a>
            </div>
            <div class="col-sm-2 box_4">
                <a href="#">
                    <img src="ui_resource/img/policy/zhongdianlingyu.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        重点领域排查统计
                    </div>
                </a>
            </div>
            <div class="col-sm-2 box_5">
                <a href="#">
                    <img src="ui_resource/img/policy/banshichu.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        办事处业务统计
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection