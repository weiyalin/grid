<!DOCTYPE HTML>
<html>
<head>
    <title>首页</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <style>
        a,a:hover{text-decoration: none; color:#fff;}
        .row a:hover{transform:scale(1.1,1.1)}
        .row a {display:block; padding:15px 0; color:#fff; border-radius: 5px;
            letter-spacing:6px; font:normal bold 16px "微软雅黑",sans-serif;
            margin:-5px 40px;}
        .box_1 a{background-color:#4E7EDC;}
        .box_2 a{background-color:#1BC1B1;}
        .box_3 a{background-color:#8BCF43;}
        .box_4 a{background-color:#9966FF;}
        .box_5 a{background-color:#EFBC00;}
        .box_6 a{background-color:#333399;}
        .box_7 a{background-color:#33CC99;}
        .box_8 a{background-color:#8BCF43;}
        .box_9 a{background-color:#CCB59D;}
        .row div{padding: 20px;}
        .header{margin:20px;}
        @media screen and (min-device-height: 900px) {
            .header{margin:100px 0;}
        }
    </style>
</head>
<body style="background-image:url('/ui_resource/img/bg.jpg')">
<div class="container" style="max-width:1200px; padding-top:10px; font-size:12px;">
    <div id="weather_box" style="float:left;border-radius: 5px;background-color:rgba(255,255,255,0.5); color:#fff; padding:1px 5px;">
        {{ date('Y-m-d',time()) }} 新乡：
    </div>
    <div style="float:right;color:#fff; text-align:right;">
        <i class="glyphicon glyphicon-user"></i>  欢迎：<a href="#">联动指挥中心</a>登录系统
        &nbsp;&nbsp;&nbsp;&nbsp;
        <i class="glyphicon glyphicon-bell"></i> <a href="#">消息</a>
        <!--i class="glyphicon glyphicon-picture"></i> <a href="#">更换皮肤</a-->
    </div>
</div>

<div class="container text-center" style="max-width:1100px;">
    <div class="row" >
        <img src="ui_resource/img/logo11.png" class="header"/>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-4 box_1">
            <a href="/event_index">
                <img src="ui_resource/img/index/event.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    业务中心
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4 box_2">
            <a href="gps_index">
                <img src="ui_resource/img/index/GIS.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    GIS中心
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4 box_3">
            <a href="/video_index">
                <img src="ui_resource/img/index/video.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    视频中心
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-4 box_4">
            <a href="/data_index">
                <img src="ui_resource/img/index/data.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    数据中心
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4 box_5">
            <a href="/">
                <img src="ui_resource/img/index/office.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    日常办公
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4 box_6">
            <a href="/">
                <img src="ui_resource/img/index/service.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    民生服务
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-4 box_7">
            <a href="/policy_index">
                <img src="ui_resource/img/index/determine.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    决策中心
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4 box_8">
            <a href="/">
                <img src="ui_resource/img/index/jixiao.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    绩效考核
                </div>
            </a>
        </div>
        <div class="col-md-4 col-lg-4 box_9">
            <a href="/">
                <img src="ui_resource/img/index/system.png" width="60"/>
                <div class="text-center" style="padding:10px 0 0;">
                    系统管理
                </div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script>
    $.ajax({
        url     : 'http://apis.baidu.com/thinkpage/weather_api/suggestion?location=xinxiang&language=zh-Hans&unit=c&start=0&days=1',
        headers : {
            'apikey'    : '8785573aa70c84cf2724a81825c5a3b0'
        },
        success : function(data){
            var data = data.results[0].daily[0];
            var str = data.text_day+'  '+data.low+'℃ ~ '+data.high+'℃';
            $('#weather_box').append(str);
        }
    })
</script>