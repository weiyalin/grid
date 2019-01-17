@extends('layout')
@section('header')
<style>
    a,a:hover{text-decoration: none;}
    .row a:hover{transform:scale(1.1,1.1)}
    .row a {display:block; padding:15px 0; color:#fff; border-radius: 5px;
            letter-spacing:6px; font:normal bold 16px "微软雅黑",sans-serif;
            margin:0 20px;}
    .box_1 a{background-color:#7c9bd7;}
    .box_2 a{background-color:#5be0d3;}
    .box_3 a{background-color:#9ad160;}
    .box_4 a{background-color:#9f79ac;}
    .box_5 a{background-color:#d3b441;}
    .box_6 a{background-color:#4242cd;}
    .box_7 a{background-color:#2fd39d;}
    .box_8 a{background-color:#8bda37;}
    .box_9 a{background-color:#df9950;}
    .box_10 a{background-color:#03a9f4;}
    .box_11 a{background-color:#CCB59D;}
    .box_12 a{background-color:#4caf50;}
    .row div{padding: 20px;}
    .box_10  img{
        width: 60px;
        height: 60px;
    }
</style>
@endsection
@section('content')
<div class="container text-center" style="max-width:1100px;">
    <div class="row">
        @if(is_permission('/event_index'))
            <div class="col-md-4 col-lg-3 box_1">
                <a href="/event_index">
                    <img src="ui_resource/img/index/event.png" width="60"/>
                    <div class="text-center" style="padding:10px 0 0;">
                        业务中心
                    </div>
                </a>
            </div>
        @endif
        @if(is_permission('/gps_index'))
            <div class="col-md-4 col-lg-3 box_2">
                <a href="gps_index">
                    <img src="ui_resource/img/index/GIS.png" width="60"/>
                    <div class="text-center" style="padding:10px 0 0;">
                        GIS中心
                    </div>
                </a>
            </div>
        @endif

        @if(is_permission('/video_index_map'))
            <div class="col-md-4 col-lg-3 box_3">
                <a href="/video_index_map">
                    <img src="ui_resource/img/index/video.png" width="60"/>
                    <div class="text-center" style="padding:10px 0 0;">
                        视频中心
                    </div>
                </a>
            </div>
        @endif

        @if(is_permission('/data_index'))
        <div class="col-md-4 col-lg-3 box_4">
            <a href="/data_index">
                <img src="ui_resource/img/index/data.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    数据中心
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/job_pre_event'))
        <div class="col-md-4 col-lg-3 box_5">
            <a href="/job_pre_event">
                <img src="ui_resource/img/index/office.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    日常办公
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/party_place'))
        <div class="col-md-4 col-lg-3 box_6">
            <a href="/party_place">
                <img src="ui_resource/img/index/service.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    党建中心
                </div>
            </a>
        </div>
        @endif

        @if(is_permission('/policy_stat'))
        <div class="col-md-4 col-lg-3 box_7">
            <a href="/policy_stat">
                <img src="ui_resource/img/index/determine.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    决策中心
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/exam_setting'))
        <div class="col-md-4 col-lg-3 box_8">
            <a href="/exam_setting">
                <img src="ui_resource/img/index/jixiao.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    绩效考核
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/sys_user_manage'))
        <div class="col-md-4 col-lg-3 box_9">
            <a href="/sys_user_manage">
                <img src="ui_resource/img/index/system.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    系统管理
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/sys_user_manage'))
        <div class="col-md-4 col-lg-3 box_10">
            <a href="/hawk_eye_video">
                <img src="ui_resource/img/index/eyes.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    鹰眼系统
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/sys_user_manage'))
        <div class="col-md-4 col-lg-3 box_11">
            <a href="/help_poor_index">
                <img src="ui_resource/img/index/help.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    扶贫中心
                </div>
            </a>
        </div>
        @endif
        @if(is_permission('/sys_user_manage'))
        <div class="col-md-4 col-lg-3 box_12">
            <a href="/visit_people_list">
                <img src="ui_resource/img/index/vister.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    信访人员
                </div>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection