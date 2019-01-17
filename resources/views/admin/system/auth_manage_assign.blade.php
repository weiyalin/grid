@extends('layout')

@section('content')
    <link href="/ui_resource/css/zTreeStyle.css" rel="stylesheet"/>

    <div class="widget">
        <div class="bg widget-header">
            <i class="widget-icon"></i>
            <span class="widget-caption">权限管理</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">

            <div class="btn-group">
                <a class="btn btn-default btn-add" href="/sys_auth_manage">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
                <button type="button" class="btn btn-default btn-save" data="/sys_auth_manage_saveauth">
                    <i class="fa fa-save"></i> 保存
                </button>
            </div>

            <input type="hidden" name='role_id' value="{{ $role['id'] }}"/>    <!--所编辑的角色ID-->
            <input type="hidden" name="oldAuthNodeIds" value="{{ $oldAuthNodeIds }}"/> <!--所编辑角色原本具有的权限-->
            <div style="width: 400px;margin-top: 10px;">
                <div class="widget flat radius-bordered">
                    <div class="widget-header bg-lightred">
                        <span class="widget-caption" style="color:#fff">[<b> {{ $role['name'] }}</b>]权限列表 </span>
                    </div>
                    <div class="widget-body" style="background-color: #eee;">
                        <ul id="authList" class="ztree">

                        </ul>
                    </div>
                </div>
            </div>
        </div><!--Widget Body-->
    </div><!--Widget-->

    <script src="ui_resource/js/jquery.ztree.core.min.js"></script>
    <script src="ui_resource/js/jquery.ztree.excheck.min.js"></script>
    <script src="ui_resource/js/bootbox.js"></script>
    <script src="ui_resource/js/functions.js"></script>
    <script src="ui_resource/js/auth_manage_assign.js"></script>
@endsection
