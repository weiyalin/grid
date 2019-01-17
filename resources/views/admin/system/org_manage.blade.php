@extends('layout')
@section('header')
<style>
    .btn-group{  float:left;  z-index:999;  }
    #simpledatatable_filter label {  float: right;  margin-right: 70px;  }
    .table-striped > tbody > tr.tr-selected > td{background-color: #4dc1b6;}
    .table-striped > tbody > tr > td { cursor: pointer;}
</style>
@endsection
@section('content')
    <div class="widget">
        <div class="bg widget-header">
            <i class="widget-icon"></i>
            <span class="widget-caption">机构管理</span>
            <div class="widget-buttons">
                <a data-toggle="maximize" href="#">
                    <i class="fa fa-expand"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="btn-group">
                <a class="btn btn-default btn-add" href="/sys_org_manage_add">
                    <i class="fa fa-plus-square"></i>
                    新增机构
                </a>
                <button class="btn btn-default btn-del" data="/sys_org_manage_delete">
                    <i class="fa fa-minus-square"></i>
                    批量删除
                </button>
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>机构名称</th>
                        <th>详细地址</th>
                        <th>联系方式</th>
                        <th>机构类型</th>
                        <!--th>上级机构</th-->
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div><!--Widget body-->
    </div>
@endsection

@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    {{--<script src="assets/js/datetime/bootstrap-datepicker.js"></script>--}}
    <!--弹出框的两个js文件-->
    <script src="ui_resource/js/bootbox.js"></script>
    <script src="ui_resource/js/functions.js"></script>

    <script src="ui_resource/js/org_manage.js"></script>
@endsection