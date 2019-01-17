@extends('layout')
@section('header')
    <style type="text/css">
        .btn_tools button{ margin-left:20px; }
        .tools{  margin-left:20px;  }
    </style>
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">业务总体统计</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div class="row">
                <div class="btn_tools col-sm-5">
                    <button class="btn btn-info" data-value="0" title="总体">总体情况</button>
                    <button class="btn" data-value="1" title="近一年">近一年</button>
                    <button class="btn" data-value="2" title="本月">本月</button>
                    <button class="btn" data-value="3" title="本周">本周</button>
                    <button class="btn" data-value="4" title="当天">当天</button>
                </div>
                <div class="col-sm-4 form-horizontal">
                    <label class="control-label col-sm-4">事件类型:</label>
                    <div class="col-sm-8">
                        <select id="summary_department" name="department"  class="form-control">
                            <option value="0" selected>所有部门</option>
                            @foreach($depart as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-sm-5">
                    <div id="chart_summary_1" style="width:100%;height:500px;"></div>
                </div>
                <div class="col-sm-7" >
                    <div id="chart_summary_2" style="width:100%;height:500px;"></div>

                </div>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->
    <!--第二行-->
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">业务办结率</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div class="row">
                <form class="form-horizontal">
                    <div class="col-sm-4">
                        <label for="name" class="control-label col-sm-3">日期</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control event_date" id="completion_date"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-3">
                                <span>事件类型:</span>
                            </div>
                            <div class="col-sm-9">
                                <select id="completion_depart" class="form-control">
                                    <option value="0" selected>所有</option>
                                    @foreach($depart as $v)
                                        <option value="{{ $v->id }}" data-name="{{ $v->name }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-3">
                                <span>事件来源:</span>
                            </div>
                            <div class="col-sm-9">
                                <select id="completion_source"  class="form-control">
                                    <option value="-1" data-name="全部来源" selected>全部来源</option>
                                    <option value="0" data-name="指挥中心">指挥中心</option>
                                    <option value="1" data-name="网格员">网格员</option>
                                    <option value="2" data-name="微信用户">微信用户</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="btn btn-primary" id="completion_btn">检索</div>
                        <div class="btn btn-warning" id="completion_reset">重置</div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div id="chart_complete_1" style="width:100%;height:500px;"></div>
                </div>
                <div class="col-sm-7" >
                    <div id="chart_complete_2" style="width:100%;height:500px;"></div>
                </div>
            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->
    <!--第三行-->
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">事件地图分布</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div class="row">
                <form>
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="col-sm-3 right-align">
                            <label for="name">事件日期:</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control event_date" id="map_date" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-3">
                            <span>事件类型:</span>
                        </div>
                        <div class="col-sm-9">
                            <select id="map_depart"  class="form-control">
                                <option value="-1">全部</option>
                                @foreach($depart as $v)
                                    <option value="{{ $v->id }}" data-name="{{ $v->name }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-3">
                            <span>事件来源:</span>
                        </div>
                        <div class="col-sm-9">
                            <select id="map_source"  class="form-control">
                                <option value="-1">全部来源</option>
                                <option value="0">指挥中心</option>
                                <option value="1">网格员</option>
                                <option value="2">微信用户</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-sm-2">
                    <span class="btn btn-primary" id="chart_map_search">检索</span>
                    <span class="btn btn-warning" id="chart_map_reset">重置</span>
                </div>
                </form>
            </div>

            <div class="row">
                <div class='col-sm-12' id="chart_map" style="width:100%;height:500px;"></div>
            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <div class="widget">
        <div class="widget-header bg-default">
            <span class="widget-caption">业务统计分析</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div class="tabbable" >
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active">
                        <a data-toggle="tab" data-type="1" href="#source_stat">来源统计</a>
                    </li>
                    <li class="tab-red">
                        <a data-toggle="tab" data-type="2" href="#type">类型统计</a>
                    </li>
                    <li class="tab-green">
                        <a data-toggle="tab" data-type="3" href="#org">办事处统计</a>
                    </li>
                    <li class="tab-blue">
                        <a data-toggle="tab" data-type="4" href="#department">职能部门统计</a>
                    </li>
                    <li class="tab-yellow">
                        <a data-toggle="tab" data-type="5" href="#redyellow">红黄牌统计</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!--来源统计-->
                    <div id="source_stat" class="tab-pane in active" style="min-height: 500px;">
                        <div class="row form-horizontal">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-3">日期:</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control event_date" id="source_date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="source_search">检索</button>
                                <button class="btn btn-warning" id="source_reset">重置</button>
                            </div>

                        </div>
                        <div class="row margin-top-10">
                            <div id="chart_tab_source" style="width:1000px;height:500px;margin:0 auto;">
                            </div>
                        </div>
                    </div>
                    <!--类型统计-->
                    <div id="type" class="tab-pane" style="min-height: 500px;">
                        <div class="row form-horizontal">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-3">日期:</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control event_date" id="type_date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="type_search">检索</button>
                                <button class="btn btn-warning" id="type_reset">重置</button>
                            </div>

                        </div>
                        <div class="row margin-top-10">
                            <div id="chart_tab_type" style="width:1000px;height:500px;margin:0 auto;">
                            </div>
                        </div>
                    </div>
                    <!--办事处统计-->
                    <div id="org" class="tab-pane" style="min-height: 500px;">
                        <div class="row form-horizontal">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-3">日期:</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control event_date" id="office_date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="office_search">检索</button>
                                <button class="btn btn-warning" id="office_reset">重置</button>
                            </div>

                        </div>
                        <div class="row margin-top-10">
                            <div id="chart_tab_office" style="width:1000px;height:500px;margin:0 auto;">
                            </div>
                        </div>
                    </div>
                    <!--职能部门统计-->
                    <div id="department" class="tab-pane" style="min-height: 500px;">
                        <div class="row form-horizontal">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-3">日期:</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control evet_date" id="org_date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="org_search">检索</button>
                                <button class="btn btn-warning" id="org_reset">重置</button>
                            </div>

                        </div>
                        <div class="row margin-top-10">
                            <div id="chart_tab_org" style="width:1000px;height:500px;margin:0 auto;">
                            </div>
                        </div>
                    </div>
                    <div id="redyellow" class="tab-pane" style="min-height: 500px;">
                        <div class="row form-horizontal">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-3">日期:</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control event_date" id="redyellow_date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" id="redyellow_search">检索</button>
                                <button class="btn btn-warning" id="redyellow_reset">重置</button>
                            </div>
                        </div>
                        <div class="row margin-top-10">
                            <div id="chart_tab_xx" style="width:1000px;height:500px;margin:0 auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->
@endsection
@section('footer')
    <script src="libs/echarts/echarts.min.js"></script>
    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script src="ui_resource/js/policy_stat.js"></script>
@endsection