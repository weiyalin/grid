@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .right-align{  text-align: right;  }
        .panel-body table td{padding:2px 5px;}
        .date-picker-wrapper{z-index:999;}
        .modal-darkorange .import-btn{
            cursor: pointer;
            /*margin-bottom: 10px;*/
        }
        .modal-darkorange .file-bar{
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .modal-darkorange p{
            color:#777;
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
                        <div id="collapseOne" class="panel-collapse collapse" style="">
                            <!--用来判断所在页面：常住(默认)？组户？特殊？重点。。。等人群管理页面-->
                            <input type="hidden" id="where" data-name="{{ $where['key'] or null }}"/>
                            <div class="panel-body">
                                <table>
                                    <form id="advanced_search">
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">姓名</span>
                                                    <input type="text" id="time" class="form-control" name="time">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <div class="input-group pull-left">
                                                    <button type="reset" class="btn btn-warning" for="#advanced_search">重置</button>
                                                </div>
                                                <div class="input-group pull-left" style="margin-left:20px;">
                                                    <button id="btnQuery" type="button" class="btn btn-success" onclick="select_change()">查询</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="horizontal-form">

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <div id="import-modal" style="display:none;">
        <input type="file" name="import" class="import-submit" style="display: none">
        <a class="import-btn">上传文件</a>
        <div class="file-bar"></div>
        <p>请上传规定格式的Excel文件,<a id="download-template">下载模板</a></p>
    </div>
    <input value="/data_population_template_download" id="download-template-url" type="hidden">
    <input value="/data_population_import_excel" id="import-url" type="hidden">
@endsection

@section('footer')
    {{--<script src="assets/js/datetime/jquery.daterangepicker.js"></script>--}}
    {{--<script src="assets/js/datatable/jquery.dataTables.min.js"></script>--}}
    {{--<script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>--}}
    {{--<script src="assets/js/template/template.js"></script>--}}
    {{--<script src="assets/js/bootbox/bootbox.js"></script>--}}

    {{--<script src="admin/resident_query.js"></script>--}}
    {{--<script src="ui_resource/js/data_select_grid.js"></script>--}}
    {{--<script src="libs/upload/jquery.ocupload-1.1.2.js"></script>--}}
    {{--<script src="admin/import_population.js"></script>--}}
@endsection