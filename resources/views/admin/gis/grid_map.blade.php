@extends('layout')

@section('header')
    <style type="text/css">
        .reset_btn{
            position: absolute;
            z-index: 999;
            top: 10px;
            left: 20px;
        }
    </style>
    <script src="libs/echarts/echarts.min.js"></script>


@endsection

@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">网格地图</span>
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
            <div class="row">
                <div class="col-sm-6">
                    <a onclick="geo_Json(0);" href="javascript:void(0);" class="btn btn-default reset_btn"><i class="glyphicon glyphicon-refresh"></i></a>
                    <div id="main_map" style="width: 500px;height:500px;"></div>

                </div>
                <div class="col-sm-6" >
                    <div class="tabbable" >
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#org">
                                    机构人员
                                </a>
                            </li>

                            <li class="tab-red">
                                <a data-toggle="tab" href="#person">
                                    基础信息
                                </a>
                            </li>
                            <li class="tab-green">
                                <a data-toggle="tab" href="#event">
                                    业务信息
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div id="org" class="tab-pane in active" style="min-height: 500px;">
                                <table class="table table-hover">
                                    <thead class="bordered-darkorange">
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            下级单位
                                        </th>
                                        <th>
                                            单位数量
                                        </th>
                                        <th>
                                            职能部门数量
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="map_org">
                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            办事处/乡镇级
                                        </td>
                                        <td>
                                            19
                                        </td>
                                        <td>
                                            54
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            2
                                        </td>
                                        <td>
                                            社区/村组级
                                        </td>
                                        <td>
                                            200
                                        </td>
                                        <td>
                                            300
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            3
                                        </td>
                                        <td>
                                            网格级
                                        </td>
                                        <td>
                                            999
                                        </td>
                                        <td>
                                            12
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div id="org_detail" style="display: none;">
                                    <br/>
                                    <br/>
                                    <br/>

                                    <table class="table table-hover">
                                        <thead class="bordered-darkorange">
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                网格长
                                            </th>
                                            <th>
                                                所辖组户数量
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                1
                                            </td>
                                            <td>
                                                张xx
                                            </td>
                                            <td>
                                                125
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div id="person" class="tab-pane" style="min-height: 500px;">
                                <table class="table table-hover">
                                    <thead class="bordered-darkorange">
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            人口
                                        </th>
                                        <th>
                                            人口数量
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="map_person">
                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            实有人口
                                        </td>
                                        <td>
                                            199888
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            2
                                        </td>
                                        <td>
                                            流动人口
                                        </td>
                                        <td>
                                            200342
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>

                            <div id="event" class="tab-pane" style="min-height: 500px;">
                                <table class="table table-hover">
                                    <thead class="bordered-darkorange">
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            单位
                                        </th>
                                        <th>
                                            事件数量
                                        </th>
                                        <th>
                                            办结事件数量
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="map_event">
                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            xxx办事处
                                        </td>
                                        <td>
                                            1234
                                        </td>
                                        <td>
                                            1230
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            2
                                        </td>
                                        <td>
                                            xxx部门
                                        </td>
                                        <td>
                                            2003
                                        </td>
                                        <td>
                                            1999
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            3
                                        </td>
                                        <td>
                                            xxx
                                        </td>
                                        <td>
                                            999
                                        </td>
                                        <td>
                                            990
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <script type="text/javascript">
        var myChart = echarts.init(document.getElementById('main_map'));
//        myChart.setOption({
//            series: [{
//                type: 'map',
//                map: '河南'
//            }]
//        });
       // myChart.showLoading();
        //http://echarts.baidu.com/asset/map/json/province/henan.json

//        $.get('/gps_geo_json?id=1', function (geoJson) {
//
//            myChart.hideLoading();
//            echarts.registerMap('henan', geoJson);
//
//            myChart.setOption({
//                series: [{
//                    type: 'map',
//                    map: 'henan'
//                }]
//            })
//        });
        myChart.on('click', function (params) {
            console.log(params);
            //进入下级网格
            var id = params.data.grid_id;
            geo_Json(id);
//            $.get('/gps_sub_geo_json?id='+id, function (usaJson) {
//
//                //
//                if(id==3){
//                    $('#org_detail').css('display','block');
//                }
//
//                echarts.registerMap('USA', usaJson.json);
//                myChart.setOption({
//                    visualMap: {
//                        min: 0,
//                        max: 500000,
//                        text:['High','Low'],
//                        realtime: false,
//                        calculable: true,
//                        inRange: {
//                            color: ['lightskyblue','yellow', 'orangered']
//                        }
//                    },
//                    series: [{
//                        type: 'map',
//                        map: 'USA',
//                        data:usaJson.data
//                    }]
//                });
//            });

            });




        function geo_Json(id){
            myChart.showLoading();
            $.get('/gps_grid_map_sub_geo_json?id='+id, function (usaJson) {
                myChart.hideLoading();

                echarts.registerMap('USA', usaJson.json);
                myChart.setOption({
                    backgroundColor:'lightgreen',
                    tooltip: {
                        trigger: 'item',
                        formatter: '{b}<br/>固定人口数:{c} '
                    },
                    visualMap: {
                        min: 0,
                        max: 500000,
                        text:['High','Low'],
                        realtime: false,
                        calculable: true,
                        inRange: {
                            color: ['lightskyblue','yellow', 'orangered']
                        }
                    },
                    series: [{
                        type: 'map',
                        map: 'USA',
                        data:usaJson.data
                    }]
                });
                load_stat(id,0);

            });
        }


        function load_stat(grid_id,level){
            $.ajax({
                type: "get",
                url: '/gps_grid_map_org_query',
                data: {
                    grid_id:grid_id,
                    level:level
                },
                success: function (data) {
                    if (data.code==0) {
                        $('#map_org').empty();
                        data.result.forEach(function(item){
                            $('#map_org').append(" <tr> <td>"+item.level+"</td> <td>"+item.level_text+" </td> <td>"+item.org_count+" </td> <td>"+item.office_count+" </td> </tr>");
                        })

                    } else {
                        Notify('加载机构失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });

            $.ajax({
                type: "get",
                url: '/gps_grid_map_population_query',
                data: {
                    grid_id:grid_id,
                    level:level
                },
                success: function (data) {
                    if (data.code==0) {
                        $('#map_person').empty();

                        $('#map_person').append(" <tr> <td>1</td> <td> 实有人口</td> <td>"+data.result.fixed_count+" </td> </tr>");
                        $('#map_person').append(" <tr> <td>2</td> <td> 流动人口</td> <td>"+data.result.fluid_count+" </td> </tr>");



                    } else {
                        Notify('加载人口失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });

            $.ajax({
                type: "get",
                url: '/gps_grid_map_event_query',
                data: {
                    grid_id:grid_id,
                    level:level
                },
                success: function (data) {
                    if (data.code==0) {
                        $('#map_event').empty();
                        data.result.forEach(function(item){
                            $('#map_event').append(" <tr> <td>"+item.department_id+"</td> <td>"+item.department_name+" </td> <td>"+item.event_count+" </td> <td>"+item.event_completed_count+" </td> </tr>");
                        })


                    } else {
                        Notify('加载事件失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });

        }

        //loading
        geo_Json(0);
    </script>

@endsection