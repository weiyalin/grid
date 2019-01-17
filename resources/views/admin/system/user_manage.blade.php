@extends('layout')
@section('header')
    <style>
        .btn-group{  float:left;  z-index:999;  }
        #simpledatatable_filter label {  float: right;  margin-right: 70px;  }
        .table-striped > tbody > tr.tr-selected > td{background-color: #4dc1b6;}
        .table-striped > tbody > tr > td { cursor: pointer;}
        .input-group span{
            height: 34px!important;
        }
        .input-group input,.input-group select{
            width: 150px!important;
            height: 34px!important;
        }
    </style>
@endsection
@section('content')
    <div class="widget">
        {{-- 头部信息 --}}
        <div class="widget-header">
            <span class="widget-caption">
                <i class="fa fa-table"></i>
                {{ $title }}
            </span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
            </div>
        </div>
        {{--主体部分--}}
        <div class="widget-body">

            <!--高级搜索部分-->
            <div class="toolbar">
                <div class="panel-group accordion" id="accordion" style="margin-bottom: 8px;">
                    <div class="panel panel-default">
                        <div class="panel-heading ">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    <i class="fa fa-search"></i> 高级搜索
                                </a>
                            </h4>
                        </div>

                        <div style="" id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table>
                                    <form id="advanced_search">
                                    <tbody><tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-college">姓名</span>
                                                <input id="name" type='text' class="form-control" name="name"/>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-deparment">登录名字</span>
                                                <input id="login_name" type="text" class="form-control" name="login_name"/>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-specialty">职务</span>
                                                <input id="title" type="text" name="title" class="form-control"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-specialty">电话</span>
                                                <input id="phone" type="text" name="phone" class="form-control"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="height: 4px;"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-collection">角色</span>
                                                <select id="role_id" name="role_id">
                                                    <option value="">全部</option>
                                                    @foreach($data['role_list'] as $v)
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-collection">组织机构</span>
                                                <select id="org_id" name="org_id">
                                                    <option value="">全部</option>
                                                    @foreach($data['org_list'] as $v)
                                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-name">状态</span>
                                                <select id="status" name="status">
                                                    <option value=''>全部</option>
                                                    <option value="0">正常</option>
                                                    <option value="1">禁用</option>
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
                                    </form>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--高级搜索部分结束-->


            <div class="btn-group">
                <a class="btn btn-default btn-add" href="/sys_user_manage_view">
                    <i class="fa fa-plus-square"></i>
                    添加用户
                </a>
                <!--button class="btn btn-default btn-change-status" data="/sys_user_manage_delete">
                    <i class="fa fa-minus"></i>
                    批量禁用/启用
                </button-->
                <button class="btn btn-default btn-del" data="/sys_user_manage_delete">
                    <i class="fa fa-trash-o"></i>
                    批量删除
                </button>
            </div>


            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>姓名</th>
                        <th>登录名</th>
                        <th>联系电话</th>
                        <th>职务</th>
                        <th>角色</th>
                        <th>所属组织机构</th>
                        <th>状态</th>
                        <!--th>上级机构</th-->
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    {{--<script src="assets/js/datetime/bootstrap-datepicker.js"></script>--}}
            <!--弹出框的两个js文件-->
    <script src="ui_resource/js/bootbox.js"></script>
    <script src="ui_resource/js/functions.js"></script>

    <script src="ui_resource/js/user_manage.js"></script>
@endsection