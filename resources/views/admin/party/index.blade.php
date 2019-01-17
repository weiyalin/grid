@extends('layout')
@section('header')
<style>
    .row{padding:20px 50px;}
    .container .row img{height:100px;}
    .container .row a{display: inline-block; height:100%; color:#333; font-size:16px;
        width:150px; padding:20px 10px 10px 5px;border-radius:5px; color:#fff;}
    .container .row a div{padding:20px;}
    .container .row a:hover{
        -webkit-transform: scale(1.1,1.1);
        -moz-transform: scale(1.1,1.1);
        -ms-transform: scale(1.1,1.1);
        -o-transform: scale(1.1,1.1);
        transform: scale(1.1,1.1);
    }
    .row>div{
        margin: 0 20px;
    }
    .desc{text-align:left; color:#fff;margin-top:10px;}

    .box_1{background-color:#4E7EDC;}
    .box_2{background-color:#1BC1B1;}
    .box_3{background-color:#8BCF43;}
    .box_4{background-color:#CCB59D;}
    .box_5{background-color:#EFBC00;}
    a:hover{
        text-decoration: none;
    }
</style>
@endsection
@section('content')
<div class="container text-center" style="margin-top:70px;">
    <div class="row show-grid">
        <div class="col-sm-2 col-sm-offset-1">
            <a href="/party_place" class="box_1">
                <img src="ui_resource/img/data_center/changzhurenkou.png"/>
                <div>
                    场所管理
                </div>
            </a>
            <!--<div class="desc">
                {{--查看常住人口分布图，根据网格区域、姓名、身份证号、手机号等方式查看人口信息，并提供对常住人口进行新增、属性修改、删除等功能。--}}
            </div>-->
        </div>
        <div class="col-sm-2">
            <a href="/party_expenditure"  class="box_2">
                <img src="ui_resource/img/data_center/teshurenqun.png"/>
                <div>
                    经费管理
                </div>
            </a>
            <!--<div class="desc">
                {{--查看特殊人群分布图，根据网格区域、姓名、身份证号、人群类别等方式筛选特殊人群、查看该用户的详细信息。--}}
            </div>-->
        </div>

        <div class="col-sm-2">
            <a href="/party_peace"  class="box_3">
                <img src="ui_resource/img/data_center/changzhurenkou.png"/>
                <div>
                    平安建设
                </div>
            </a>
        </div>


        <div class="col-sm-2">
            <a href="/party_twins"  class="box_4">
                <img src="ui_resource/img/data_center/teshurenqun.png"/>
                <div>
                    双城创建
                </div>
            </a>
        </div>
    </div>
</div>
@endsection