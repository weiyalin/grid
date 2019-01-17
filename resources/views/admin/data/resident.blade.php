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
                                                <div class='input-group'>
                                                    <span class="input-group-addon">姓名</span>
                                                    <input type="text" id="name" class="form-control" name="name"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">身份证号</span>
                                                    <input type="text" id="card_code" class="form-control" name="card_code"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">手机号</span>
                                                    <input type="text" id="contact_phone" class="form-control" name="contact_phone"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">出生日期</span>
                                                    <input type="text" id="birthday" class="form-control" name="birthday"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">一级网格</span>
                                                    <select type="text" id="grid_1" class="form-control" name="grid_1" style="width: 136px">
                                                        <option value="">请选择...</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">二级网格</span>
                                                    <select type="text" id="grid_2" class="form-control" name="grid_2" style="width: 163px;">
                                                        <option value="">请选择...</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">三级网格</span>
                                                    <select type="text" id="grid_3" class="form-control" name="grid_3" style="width: 150px;">
                                                        <option value="">请选择...</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">四级网格</span>
                                                    <select type="text" id="grid_4" class="form-control" name="grid_4" style="width: 163px;">
                                                        <option value="">请选择...</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <div class="input-group pull-left">
                                                    <button type="reset" class="btn btn-warning" for="#advanced_search">重置</button>
                                                </div>
                                        </form>
                                                <div class="input-group pull-left" style="margin-left:20px;">
                                                    <button id="btnQuery" type="button" class="btn btn-success" onclick="select_change()">查询</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="btn-group pull-left" style="z-index:999;">
                <a href="/data_population_add" class="btn btn-default" id="btn_add" type="button">
                    <i class="fa fa-plus-square"></i> 新增
                </a>
                <button class="btn btn-default btn-add" id="import_excel" type="button">
                    <i class="fa fa-download"></i> 导入
                </button>
                <button onclick="exportExcel()" class="btn btn-default" id="btn_getExcel" type="button">
                    <i class="fa  fa-share"></i> 导出
                </button>
                <a class="btn btn-default" id="btn_recycleBin" type="button" href="/data_population_recycle_bin">
                    <i class="fa fa-trash-o"></i> 回收站
                </a>
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>姓名</th>
                        <th>身份证号</th>
                        <th>出生日期</th>
                        <th>联系电话</th>
                        <th>联系地址</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <!-- 人口信息详情页面 弹出框-->
    <script id="myModal" type="text/html">
        <div class="form-horizontal">
            <div class="form-title">基本信息</div>
            <div class="row">
                <div class="col-sm-3"><label class="col-sm-4">姓名:</label><span class="text"><{name}></span></div>
                <div class="col-sm-3"><label class="col-sm-4">性别:</label><span class="text"><{sex}></span></div>
                <div class="col-sm-2"><label class="col-sm-6">国籍:</label><span class="text"><{nationality}></span></div>
                <div class="col-sm-4"><label class="col-sm-4">民族:</label><span class="text"><{nation}></span></div>
            </div>
            <div class="row">
                <div class="col-sm-3"><label class="col-sm-4"><{card_category}>:</label><span class="text"><{card_code}></span></div>
                <div class="col-sm-3"><label class="col-sm-4">婚姻:</label><span class="text"><{marital_status}></span></div>
                <div class="col-sm-2"><label class="col-sm-6">学历:</label><span class="text"><{culture_degree}></span></div>
                <div class="col-sm-4"><label class="col-sm-4">联系方式:</label><span class="text"><{contact_phone}></span></div>
            </div>
            <div class="form-title">户籍信息</div>
            <div class="row">
                <div class="col-sm-4"><label class="col-sm-5">人口状态:</label><span class="text"><{is_fixed}></span></div>
                <div class="col-sm-4"><label class="col-sm-5">户籍类型:</label><span class="text"><{family_type}></span></div>
                <div class="col-sm-4"><label class="col-sm-5">是否为户主:</label><span class="text"><{is_householder}></span></div>
            </div>
            <div class='row'>
                {{--<div class="col-sm-4"><label class="col-sm-5">是否为户主:</label><span class="text"><{is_householder}></span></div>--}}
                {{--<div class="col-sm-4"><label class="col-sm-5">与户主关系:</label><span class="text"><{relation}></span></div>--}}
            </div>
            <div class="row">
                <div class="col-sm-6"><span class="col-sm-3">居住地</span> <span class="col-sm-8"> <{domicile_address}> </span></div>
                <div class="col-sm-6"><span class="col-sm-3">户籍地</span> <span class="col-sm-8"> <{family_address}> </span></div>
            </div>
            <div class="row">
                <div class="col-sm-6"><span class="col-sm-3">联系地址:</span> <span class="col-sm-8"><{contact_address}></span></div>
                <div class="col-sm-6"><span class="col-sm-3">邮编:</span><span class="col-sm-7"><{contact_postcode}></span></div>
            </div>
            <div class="row">

            </div>
            <div class="form-title">补充信息</div>
            <div class='row'>
                <div class="col-sm-4"><span class="col-sm-6">特殊人群：</span><span class="col-sm-6"><{is_special}></span></div>
                <div class="col-sm-4"><span class="col-sm-6">重点人群：</span><span class="col-sm-6"><{is_emphases}></span></div>
                <div class="col-sm-4"><span class="col-sm-6">低保人群：</span><span class="col-sm-6"><{is_allowance}></span></div>
            </div>
            <div class='row'>
                <div class="col-sm-4"><span class="col-sm-6">伤残人群：</span><span class="col-sm-6"><{is_invalidism}></span></div>
                <div class="col-sm-4"><span class="col-sm-6">老龄人群：</span><span class="col-sm-6"><{is_older}></span></div>
                <div class="col-sm-4"><span class="col-sm-6">退伍军人：</span><span class="col-sm-6"><{is_veteran}></span></div>
            </div>
            <div class="row">
                <div class="col-sm-12"><span class="col-sm-2">备注:</span><span class="text"><{memo}></span></div>
            </div>
            <div class="form-title">网格信息</div>
            <div class="row">
                <div class='col-sm-3'><span class="col-sm-5">一级:</span><span class="col-sm-7"><{grid_1}></span></div>
                <div class='col-sm-3'><span class="col-sm-5">二级:</span><span class="col-sm-7"><{grid_2}></span></div>
                <div class='col-sm-3'><span class="col-sm-5">三级:</span><span class="col-sm-7"><{grid_3}></span></div>
                <div class='col-sm-3'><span class="col-sm-5">四级:</span><span class="col-sm-7"><{grid_4}></span></div>
            </div>
            <div class="form-title">家庭成员信息</div>
                <div class="row" style="padding: 0 30px;">
                    <{if family_info}>
                        <table class="table">
                            <tr>
                                <th>姓名</th>
                                <th>与户主关系</th>
                                <th>证件</th>
                                <th>民族</th>
                                <th>文化程度</th>
                                <th>其他</th>
                            </tr>
                            <{each family_info as value i}>
                            <tr>
                                <td><{value.name}></td>
                                <td><{value.relation}></td>
                                <td><{value.card_category}>:<{value.card_code}></td>
                                <td><{value.nation}></td>
                                <td><{value.culture_degree}></td>
                                <td><{value.extra}></td>
                            </tr>
                            <{/each}>
                        </table>
                    <{else}>
                        <h5>无</h5>
                    <{/if}>
                </div>
        </div>

    </script>

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
    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/template/template.js"></script>
    <script src="assets/js/bootbox/bootbox.js"></script>

    <script src="admin/resident_query.js"></script>
    <script src="ui_resource/js/data_select_grid.js"></script>
    <script src="libs/upload/jquery.ocupload-1.1.2.js"></script>
    <script src="admin/import_population.js"></script>
@endsection