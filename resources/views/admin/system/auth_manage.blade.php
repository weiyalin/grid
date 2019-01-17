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
            <span class="widget-caption">权限管理</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <!--a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>     最小化按钮
                </a-->
                {{--<a href="#" data-toggle="dispose">--}}
                {{--<i class="fa fa-times"></i>--}}
                {{--</a>--}}
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">

            <div class="btn-group">
                <a class="btn btn-default btn-add" href="/sys_auth_manage_addrole">
                    <i class="fa fa-plus-square"></i>
                    新增角色
                </a>
                <!--权限管理、编辑暂时隐藏，感觉用处不大-->
                <button class="btn btn-default btn-auth hidden" data="/sys_auth_manage_assign">
                    <i class="fa fa-pencil-square"></i>
                    权限管理
                </button>
                <button class="btn btn-default btn-edit hidden" data="/sys_auth_manage_editrole">
                    <i class="fa fa-minus-square"></i>
                    编辑
                </button>
                <!--以上两个隐藏-->
                <button class="btn btn-default btn-del" data="/sys_auth_manage_delrole">
                    <i class="fa fa-minus-square"></i>
                    批量删除
                </button>
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>角色名称</th>
                        <th>角色描述</th>
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
    <script src="assets/js/datetime/bootstrap-datepicker.js"></script>
    <!--弹出框的两个js文件-->
    <script src="ui_resource/js/bootbox.js"></script>
    <script src="ui_resource/js/functions.js"></script>

    <script src="ui_resource/js/auth_manage.js"></script>
@endsection