@extends('layout')
@section('header')
    <style>
        .row div{padding:0 10px;}
        .row a{display:block;
            color:#fff; font-size:18px; padding:20px 0;
            -webkit-border-radius:10px;
            -moz-border-radius:10px;
            border-radius:10px;}
        .box_1 a{background-color:#5ED31A;}
        .box_2 a{background-color: #FDB538;}
        .box_3 a{background-color: #FB0B60;}
        .row div a:hover{
            -webkit-transform: scale(1.1,1.1);
            -moz-transform: scale(1.1,1.1);
            -ms-transform: scale(1.1,1.1);
            -o-transform: scale(1.1,1.1);
            transform: scale(1.1,1.1);}
    </style>
@endsection
@section('content')
    <div class="container text-center" style="max-width: 1000px;margin-top:100px;">
        <div class="row">
            <div class="col-sm-4 box_1">
                <a href="#">
                    <img src="ui_resource/img/video/safe_city.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        平安城市建设
                    </div>
                </a>
            </div>
            <div class="col-sm-4 box_2">
                <a href="#">
                    <img src="ui_resource/img/video/car.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        移动执法监管
                    </div>
                </a>
            </div>
            <div class="col-sm-4 box_3">
                <a href="#">
                    <img src="ui_resource/img/video/supervise.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        安全生产监控
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection