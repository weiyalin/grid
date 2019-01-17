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
            <span class="widget-caption">事件分布-环保事件</span>
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
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">关键字</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="keyword" placeholder="标题关键字">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件来源</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="source"  class="form-control" onchange="select_change()">
                                    <option value="-1">全部</option>
                                    <option value="0">呼叫中心</option>
                                    <option value="1">网格员</option>
                                    <option value="2">微信用户</option>

                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件日期</label>
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
                                <label for="name" class="margin-top-10">组织机构</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="org"  class="form-control" onchange="">
                                    <option value="0">选择机构</option>
                                    @foreach($org_list as $org)
                                        <option value="{{$org->id}}">{{$org->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件状态</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="status"  class="form-control" onchange="select_change()">
                                    <option value="-1">全部</option>
                                    <option value="0">待研判</option>
                                    <option value="1">待办理</option>
                                    <option value="2">办理中</option>
                                    <option value="3">办结待审核</option>
                                    <option value="4">已办结</option>
                                    <option value="5">已挂起</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">一级分类</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="category_1"  class="form-control" onchange="category_1_change()">
                                    <option value="0">选择一级分类</option>
                                    @foreach($category_1 as $category)
                                        <option value="{{$category->code}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">二级分类</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="category_2"  class="form-control" onchange="category_2_change()">
                                    <option value="0">选择二级分类</option>

                                </select>


                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">三级分类</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="category_3"  class="form-control" onchange="">
                                    <option value="0">选择三级分类</option>

                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10 margin-top-10 margin-bottom-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-3">
                                <button id="btnQuery" type="button" class="btn btn-success" onclick="query()">查询</button>
                            </div>
                            <div class="col-sm-6">
                                {{--<p class="help-block">查看全部比价结果，请直接点击查询。</p>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>

                <div id="allmap"></div>

            </div>


        </div><!--Widget Body-->
    </div><!--Widget-->

    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>

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

        $(function () {

            $('#event_date').dateRangePicker({
                        //batchMode:'week',
                        showShortcuts:true,
                        shortcuts:{
                            'prev':['week'],
                            'next':['week']
                        },
                        showWeekNumbers: true,
                        startOfWeek: 'monday',
                        separator: ' ~ '
                    })
                    .bind('datepicker-apply',function(event,obj)
                    {
                        /* This event will be triggered when second date is selected */
                        console.log(obj);

                        //var start = Date.parse(obj.date1);
                        //var end = Date.parse(obj.date2);
                        // console.log(end);
                        //$('#currentQuery').val(start+","+end);
                        //查询数据

                    });
        });

        function query(){
            var keyword = $('#keyword').val();
            var source = $('#source').val();
            var event_date = $('#event_date').val();
            var begin_date = 0;
            var end_date = 0;
            if(event_date){
                var sale_arr = sale_date.split('~');
                begin_date = Date.parse(sale_arr[0]);
                end_date = Date.parse(sale_arr[1]);
            }
            var org = $('#org').val();
            var status = $('#status').val();
            var category_1 = $('#category_1').val();
            var category_2 = $('#category_2').val();
            var category_3 = $('#category_3').val();


            //加载事件
            $.ajax({
                type: "get",
                url: '/gps_event_map_data',
                data: {
                    keyword:keyword,
                    source:source,
                    begin_date:begin_date,
                    end_date:end_date,
                    org:org,
                    status:status,
                    category_1:category_1,
                    category_2:category_2,
                    category_3:category_3,
                    type:2//全部事件
                },
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

                        })


                    } else {
                        Notify('加载事件失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }
        query();
        //window.setInterval(load,5000);

        function category_1_change(){
            var code = $('#category_1').val();
            $.ajax({
                type: "get",
                url: '/common_category',
                data: {
                    level:2,
                    code:code
                },
                success: function (data) {
                    if (data.code==0) {
                        var list = data.result;
                        $('#category_2 option').remove();
                        $('#category_2').append('<option value="0">选择二级分类</option>');
                        list.forEach(function(item){
                            $('#category_2').append("<option value='"+item.code+"'>"+item.name+"</option>");
                        })


                    } else {
                        Notify('获取事件类型失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }

        function category_2_change(){
            var code = $('#category_2').val();
            $.ajax({
                type: "get",
                url: '/common_category',
                data: {
                    level:3,
                    code:code
                },
                success: function (data) {
                    if (data.code==0) {
                        var list = data.result;
                        $('#category_3 option').remove();
                        $('#category_3').append('<option value="0">选择三级分类</option>');
                        list.forEach(function(item){
                            $('#category_3').append("<option value='"+item.code+"'>"+item.name+"</option>");
                        })


                    } else {
                        Notify('获取事件类型失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });

        }

    </script>

@endsection