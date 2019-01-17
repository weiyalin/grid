@extends('layout')

@section('header')
    <style type="text/css">
        #allmap {width: 100%;height: 100%;min-height: 800px;overflow: hidden;margin:0;font-family:"微软雅黑";}
        label.BMapLabel{
            max-width:2000px !important;
            display: block;
        }
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ONMZAsjQwi66QAWVcwKe5ELFS35ZVFkp"></script>

@endsection

@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">人员定位-环保人员</span>
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


    <script type="text/javascript">
        var map = new BMap.Map("allmap");  // 创建Map实例
        map.centerAndZoom("新乡市牧野区",15);      // 初始化地图,用城市名设置地图中心点


        function load(){
            //加载事件
            $.ajax({
                type: "get",
                url: '/gps_location_map_data',
                data: {
                    type:2
                },
                success: function (data) {
                    if (data.code==0) {
                        map.clearOverlays();

                        var list = data.result;
                        list.forEach(function(user){
                            //console.log(event);
                            var point = new BMap.Point(user.last_longitude,user.last_latitude);
                            var gridman = new BMap.Icon("/ui_resource/img/logic/grid2.gif", new BMap.Size(28,35));
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
            var p = this.getPosition();  //获取marker的位置
            alert("marker的位置是" + p.lng + "," + p.lat);
        }


    </script>

@endsection