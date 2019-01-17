@extends('layout')

@section('header')
    <style type="text/css">
        #allmap {width: 100%;height: 100%;min-height: 800px;overflow: hidden;margin:0;font-family:"微软雅黑";}
        label.BMapLabel{
            max-width:2000px !important;
            display: block;
        }
        div.modal-dialog{
            width:700px;
        }
    </style>
    <script type="text/javascript" src="libs/ckplayer6.8/ckplayer/ckplayer.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ONMZAsjQwi66QAWVcwKe5ELFS35ZVFkp"></script>



@endsection

@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">视频中心</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
                {{--<a href="#" data-toggle="dispose">--}}
                {{--<i class="fa fa-times"></i>--}}
                {{--</a>--}}
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div id="allmap"></div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <div id="myModal" style="display:none; width: 800px;">
        <div class="row">
            <div class="col-md-12">
                <div id="a1"></div>


            </div>
        </div>
    </div>

    <script type="text/javascript">
        var map = new BMap.Map("allmap");  // 创建Map实例
        map.centerAndZoom("新乡市牧野区",15);      // 初始化地图,用城市名设置地图中心点


        function load(){
            //加载事件
            $.ajax({
                type: "get",
                url: '/video_index_map_data',
                data: {
                },
                success: function (data) {
                    if (data.code==0) {
                        map.clearOverlays();

                        var list = data.result;
                        list.forEach(function(car){
                            //console.log(event);
                            var point = new BMap.Point(car.last_longitude,car.last_latitude);
                            var gridman = new BMap.Icon("/ui_resource/img/logic/monitor.png", new BMap.Size(32,32));
                            var marker = new BMap.Marker(point,{icon:gridman});  // 创建标注
                            marker.addEventListener('click',attribute);
                            map.addOverlay(marker);              // 将标注添加到地图中


                        })


                    } else {
                        Notify('加载事件失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }
        load();
       // window.setInterval(load,5000);


        function attribute(){
//            var p = this.getPosition();  //获取marker的位置
//            alert("marker的位置是" + p.lng + "," + p.lat);
            //createPlayer();
            dialog();
        }

        function dialog(){
            //加载视频
            var flashvars={
                f:'assets/video/m050.mp4',
                c:0,
                b:1,
                // i:'http://www.ckplayer.com/static/images/cqdw.jpg'
            };
            var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
            CKobject.embedSWF('libs/ckplayer6.8/ckplayer/ckplayer.swf','a1','ckplayer_a1','600','400',flashvars,params);
            /*
             CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
             下面三行是调用html5播放器用到的
             */
            // var video=['http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4->video/mp4'];
            var video=['assets/video/m050.mp4->video/mp4'];
            var support=['iPad','iPhone','ios','android+false','msie10+false'];
            CKobject.embedHTML5('a1','ckplayer_a1',600,500,video,flashvars,support);

            bootbox.dialog({
                message: $("#myModal").html(),
                title: "固定摄像视频监控",
                className: "modal-darkorange",
                buttons: {
                    success: {
                        label: "关闭",
                        className: "btn-blue",
                        callback: function () {
                            console.log('关闭');
                        }
                    },
//                    "关闭": {
//                        className: "btn-danger",
//                        callback: function () { }
//                    }
                }
            });
        }




    </script>

    <script type="text/javascript">



    </script>


@endsection