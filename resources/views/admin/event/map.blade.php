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
            <span class="widget-caption">地图分布</span>
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

//        function showInfo(e){
//            alert(e.point.lng + ", " + e.point.lat);
//        }
//        map.addEventListener("click", showInfo);
//
//        function attribute(){
//            var p = marker.getPosition();  //获取marker的位置
//            alert("marker的位置是" + p.lng + "," + p.lat);
//        }

        function load(){
            //加载事件
            $.ajax({
                type: "get",
                url: '/event_map_data',
                data: {},
                success: function (data) {
                    if (data.code==0) {
                        map.clearOverlays();

                        var list = data.result;
                        list.forEach(function(event){
                            //console.log(event);
                            var point = new BMap.Point(event.longitude,event.latitude);
                            var marker = new BMap.Marker(point);  // 创建标注
                            map.addOverlay(marker);              // 将标注添加到地图中
                            var label = new BMap.Label(event.title,{offset:new BMap.Size(20,-10)});
                            label.setStyle({
                                display:"block",
                                maxWidth:'2000px !important',
                            });

                            marker.setLabel(label);

                            addClickHandler(event,marker);


                        })


                    } else {
                        Notify('加载事件失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }
        load();
        window.setInterval(load,5000);

        function addClickHandler(event,marker){
            marker.addEventListener("click",function(e){
                openInfo(event,e)}
            );
        }
        function openInfo(event,e){

            //构造info


            var opts = {
                width : 250,     // 信息窗口宽度
                height: 320,     // 信息窗口高度
                title : "<div style='font-size:14px;font-weight:bold;'>"+event.title+"</div>" , // 信息窗口标题
                enableMessage:true//设置允许信息窗发送短息
            };

            //构造content

            var desc = event.desc ? event.desc : event.title;

            //img
            var img='';
            if(event.img){
                img = "<img src='"+event.img+"' style='margin:10px 0px;height:120px;'/>";
            }
            //img = "<img src='/event_attachment?path=1-1466821489105.jpg' style='margin:10px 0px;height:120px;'/>";


            var content = "<div style='font-size:12px;font-weight:normal;margin:0;padding:0;line-height:1.8em;color:dimgrey'>"
                            +img + "<br/>"
                            +"事件详细:"+desc+"<br/>"
                            +"发生位置:"+event.address+"<br/>"
                            +"上报人:"+event.reporter_name+"<br/>"
                            +"上报人电话:"+event.reporter_phone+"<br/>"
                            +"事件来源:"+event.source_format+"<br/>"
                            +"事件发生时间:"+event.create_time+"<br/>"
                            + "</div>"
                    ;

            //var content = [grid_user.longitude,grid_user.latitude,html];

            var p = e.target;
            var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
            var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
            map.openInfoWindow(infoWindow,point); //开启信息窗口
        }

    </script>

@endsection