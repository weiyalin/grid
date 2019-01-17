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
            <div class="col-sm-4 box_1">
                <a href="/gps_event_map">
                    <img src="ui_resource/img/gis/event_map.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        事件分布
                    </div>
                </a>
            </div>
            <div class="col-sm-4 box_2">
                <a href="/gps_location_map">
                    <img src="ui_resource/img/gis/event_location.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        人员定位
                    </div>
                </a>
            </div>
            <div class="col-sm-4 box_3">
                <a href="/gps_grid_map">
                    <img src="ui_resource/img/gis/grid_map.png" width="80"/>
                    <div class="text-center" style="padding:10px 0;">
                        网格地图
                    </div>
                </a>
            </div>
            {{--<div class="col-sm-3 box_4">--}}
                {{--<a href="#">--}}
                    {{--<img src="ui_resource/img/gis/shuziminzheng.png" width="80"/>--}}
                    {{--<div class="text-center" style="padding:10px 0;">--}}
                        {{--数字民政--}}
                    {{--</div>--}}
                {{--</a>--}}
            {{--</div>--}}
        </div>
    </div>
@endsection