@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .panel-body table td{padding:2px 5px;}
        .input-group span{
            height: 34px!important;
        }
        .input-group input,.input-group select{
            width: 150px!important;
            height: 34px!important;
        }
    </style>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">{{ $title }}</span>
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
            <div class="toolbar">
                <div id="accordion" class="panel-group accordion" style="margin-bottom: 8px;">
                    <div class="panel panel-default">
                        <div class="panel-heading ">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" href="#collapseOne" data-parent="#accordion" data-toggle="collapse">
                                    <i class="fa fa-search"></i>
                                    高级搜索
                                </a>
                            </h4>
                        </div>

                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table>
                                    <tbody><tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-college">简称</span>
                                                <input id="short_name" type='text' class="form-control" name="short_name"/>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-name">网格级别</span>
                                                <select id="level" name="level">
                                                    <option value=''>全部</option>
                                                    <option value="1">一级</option>
                                                    <option value="2">二级</option>
                                                    <option value="3">三级</option>
                                                    <option value="4">四级</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-specialty">网格长</span>
                                                <select id="manager_name" name="manager_name">
                                                    <option value=''>全部</option>
                                                    @foreach($user as $v)
                                                        <option value="{{ $v->manager_id }}">{{ $v->manager_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="btn-search" class="btn btn-primary">
                                                <i class="fa fa-search"></i>搜索
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="btn-group pull-left" style="z-index:999;">
                <a href="/gps_grid_manage_page" class="btn btn-default" id="btn_add" type="button">
                    <i class="fa fa-plus-square"></i> 新增
                </a>
                {{--<button class="btn btn-default btn-add" type="button">--}}
                    {{--<i class="fa fa-download"></i> 导入--}}
                {{--</button>--}}
                {{--<button onclick="exportExcel()" class="btn btn-default" id="btn_getExcel" type="button">--}}
                    {{--<i class="fa  fa-share"></i> 导出--}}
                {{--</button>--}}
                {{--<a class="btn btn-default" id="btn_recycleBin" type="button" href="/data_recycle_bin">--}}
                    {{--<i class="fa fa-trash-o"></i> 回收站--}}
                {{--</a>--}}
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>名字</th>
                        <th>简称</th>
                        <th>父级网格</th>
                        <th>网格长</th>
                        <th>直属网格(个)</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

@endsection

@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/bootbox/bootbox.js"></script>

    <script src="admin/grid_list.js"></script>
@endsection