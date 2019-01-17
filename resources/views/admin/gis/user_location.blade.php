@extends('layout')

@section('header')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style type="text/css">
        #allmap {width: 100%;height: 100%;min-height: 500px;overflow: hidden;margin:0;font-family:"微软雅黑";}
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
            <span class="widget-caption">人员轨迹</span>
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
            <div id="horizontal-form">

                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">日期</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control" id="event_date" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">网格长</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="grid_user"  class="form-control" onchange="change_grid_user()">
                                    <option value="0">所有网格长</option>
                                    @foreach($grid_user_list as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-12">
                        <div id="allmap" style="height:1500px;"></div>
                    </div>
                </div>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>

    <script type="text/javascript">
        var map = new BMap.Map("allmap");  // 创建Map实例
        map.centerAndZoom("新乡市牧野区",15);      // 初始化地图,用城市名设置地图中心点

        map.enableScrollWheelZoom();//启用滚轮放大缩小
        map.addControl(new BMap.NavigationControl()); // 添加平移缩放控件
        map.addControl(new BMap.ScaleControl()); // 添加比例尺控件

        //        function showInfo(e){
        //            alert(e.point.lng + ", " + e.point.lat);
        //        }
        //        map.addEventListener("click", showInfo);
        //
        //        function attribute(){
        //            var p = marker.getPosition();  //获取marker的位置
        //            alert("marker的位置是" + p.lng + "," + p.lat);
        //        }

        function change_grid_user(){
            query();
        }

        $(function () {

            $('#event_date').dateRangePicker({
                //batchMode:'week',
                autoClose: true,
                showShortcuts:false,
                singleDate : true

            })
                    .bind('datepicker-close',function(event,obj)
                    {
                        /* This event will be triggered when second date is selected */
                        console.log(obj);

                        //var start = Date.parse(obj.date1);
                        //var end = Date.parse(obj.date2);
                        // console.log(end);
                        //$('#currentQuery').val(start+","+end);
                        //查询数据
                        query();
                    });
        });

        function query(){
            var event_date = $('#event_date').val();
            var date = 0;
            if(event_date){
                date = Date.parse(event_date);
            }
            var grid_user_id = $('#grid_user').val();



            //加载事件
            $.ajax({
                type: "get",
                url: '/gps_user_location_map_data',
                data: {
                    id:grid_user_id,
                    date:date
                },
                success: function (data) {
                    if (data.code==0) {
                        map.clearOverlays();

                        var list = data.result;
                        if(list){
                            var lines = [];
                            list.forEach(function(location){
                                //console.log(event);
                                var point = new BMap.Point(parseFloat(location.longitude),parseFloat(location.latitude));
                                lines.push(point);
                            })
                            console.log(lines);
                            if(lines){
                                var polyline = new BMap.Polyline(lines, {strokeColor:"blue", strokeWeight:2, strokeOpacity:0.5});   //创建折线
                                map.addOverlay(polyline);   //增加折线
                            }

                        }



                    } else {
                        Notify('加载事件失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }
        query();
        //window.setInterval(load,5000);



    </script>

@endsection