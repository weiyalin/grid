@extends('layout')

@section('content')
<div class="widget">
    <div class="widget">
        <div class="bg widget-header">
            <i class="widget-icon"></i>
            <span class="widget-caption">{{ $title }}</span>
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
                <button type="button" class="btn btn-default btn-save" data="/sys_auth_manage_saverole">
                    <i class="fa fa-save"></i> 保存
                </button>
            </div>
            <div style="padding:20px 0">
                <form class="col-sm-6">
                    <input type="hidden" id="role_id" value="{{ $role->id or null }}"/>
                    <div class="form-group">
                        <label for="role_name">
                            <span class="h3">角色名称</span>
                            <span style="color:#f00">(*)</span>
                            <small>少于45个字符</small>
                        </label>
                        <input class="form-control" id="role_name" type="text" value="{{ $role->name or null }}"/>
                    </div>
                    <div class="form-group">
                        <label for="role_desc">
                            <span class="h3">角色描述</span>
                            <span style="color:#f00">(*)</span>
                            <small>少于255个字符</small>
                        </label>
                        <textarea row="3" class="form-control" id="role_desc" type="text">{{ $role->desc or null }}</textarea>
                    </div>
                </form>

            </div>
            <div style="clear:both"></div>
        </div><!--Widget Body-->
    </div><!--Widget-->
</div>
<script src="ui_resource/js/bootbox.js"></script>
<script src="ui_resource/js/functions.js"></script>
<script>
$(function(){
    //点击保存按钮
    $('.btn-save').click(function(){
        var role_id = $.trim($('#role_id').val());      //角色ID
        var role_name = $.trim($('#role_name').val());  //角色名
        var role_desc = $.trim($('#role_desc').val());  //角色描述
        if(role_name.length==0 || role_name.length>45 || role_desc.length==0 || role_desc.length>255){
            bootMessage('danger','角色名称 和 角色描述 不能为空 且 长度必须符合要求');
            return;
        }

        //显示"加载中"
        $('.loading-container').show();
        $('.loading-progress').show();

        $.ajax({
            type : 'post',
            dataType : 'json',
            data : {role_id:role_id,role_name:role_name,role_desc:role_desc},
            url : $('.btn-save').attr('data'),
            async : false,
            success: function(data){
                //首先去掉"加载中"
                $('.loading-container').hide();
                if(data.code == 0){
                    Notify(data.msg,'top-left','4000','success','fa-check',true);
                    //bootMessage('success',data.msg);
                    location.href = '/sys_auth_manage';
                }
            },
            error : function(){
                $('.loading-container').hide();
                //alert('没有保存成功，请检查输入内容');
                Notify('something wrong','top-left','4000','warning','fa-warning',true);
            }
        })
    })
})

</script>
@endsection