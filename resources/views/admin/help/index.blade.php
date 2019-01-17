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
                <a href="javascript:;" class="box_1">
                    <img src="ui_resource/img/help_poor/help_active.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        扶贫活动
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/poor" class="box_2">
                    <img src="ui_resource/img/help_poor/poor_people.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        贫困人员信息
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="/help_back" class="box_4">
                    <img src="ui_resource/img/help_poor/helpback.png"/>
                    <div class="text-center" style="padding:10px 0;">
                        扶贫反馈
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection