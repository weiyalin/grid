@extends('layout')
@section('header')
    <style>
        .error{color:#f45551;display: none;line-height: 34px;margin-bottom: 0px;}
        .row{margin:5px 0;}
        .help-block{color:#f00;}
        .bootstrap-tagsinput{
            width: 66%;
            float: left;
        }
        .hint{
            line-height: 34px;
            margin-left: 15px;
            color: gray;
        }
    </style>
@endsection
@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            <span class="widget-caption">信息采集</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
            </div>
        </div>

        <div class="widget-body">
            <div class="btn-group">
                <a href="/help_back" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">标题:</label>
                        <div class="col-sm-7">
                            <input value="{{ $feedback_info->title or null }}" maxlength="50" type="text" placeholder="请输入标题" name="title" class="form-control" id="title"/>
                        </div>
                        <label id="title-error" class="error" for="title">标题不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="manager" class="col-sm-2 control-label">负责人:</label>
                        <div class="col-sm-7">
                            <input value="{{ $feedback_info->manager or null }}" maxlength="20" type="text" placeholder="负责人" name="manager" class="form-control" id="manager"/>
                        </div>
                        <label id="manager-error" class="error" for="manager">负责人不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="manager_phone" class="col-sm-2 control-label">负责人联系方式:</label>
                        <div class="col-sm-7">
                            <input value="{{ $feedback_info->manager_phone or null }}" maxlength="20" type="text" placeholder="负责人联系方式" name="manager_phone" class="form-control" id="manager_phone"/>
                        </div>
                        <label id="manager_phone-error" class="error" for="manager_phone">负责人联系方式不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="content" class="col-sm-2 control-label">内容:</label>
                        <div class="col-sm-7">
                            <textarea style="height: 160px;" class="form-control" id="content" name="content">{{ $feedback_info->content or null }}</textarea>
                        </div>
                        <label id="content-error" class="error" for="content">内容</label>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" type="{{ $type or 'add' }}" feedback_id="{{ $feedback_info->id or 0 }}" id="feedback_set" type="button">
                            <i class="fa fa-save"></i>保存
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(function(){
            var obj = $("#feedback_set").attr('feedback_id');
            var type = $("#feedback_set").attr('type');
            if(obj!=0&&type=='see'){
                $('input').attr('disabled',true);
                $('textarea').attr('disabled',true);
                $(".widget-caption").text('详细信息');
                $("#feedback_set").hide();
            }
            var post = false;
            $("#feedback_set").click(function(){
                post = true;
                var title = get_data($("#title"),false,true,'title-error');
                var manager = get_data($("#manager"),false,true,'manager-error');
                var manager_phone = get_data($("#manager_phone"),false,true,'manager_phone-error');
                var content = get_data($("#content"),false,true,'content-error');

                if(!post){
                    return;
                }
                $(this).attr('disabled',true);
                $.ajax({
                    type: "post",
                    url: '/help_back_info_set',
                    data: {
                        obj:obj,
                        title:title,
                        manager:manager,
                        manager_phone:manager_phone,
                        content:content,
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.msg, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/help_back";
                            },1000)
                        }else{
                            Notify(data.msg, 'top-right', '3000', "danger", 'fa-edit');
                            $("#feedback_set").attr('disabled',false);
                        }
                    }
                });
            })

            /**
             * 获取数据并验证
             * @param obj
             * @param check
             * @param must 是否必须
             * @param hint 提示信息
             * @returns {string|*}
             */
            function get_data(obj,check,must,hint){
                var get_data = $.trim(obj.val());
                if(must){
                    if(get_data!=''){
                        $("#"+hint).hide();
                    }else{
                        post = false;
                        $("#"+hint).show();
                    }
                }
                if(get_data!=''&&check){
                    var condition = new RegExp(check);
                    if(!condition.test(get_data)){
                        post = false;
                        $("#"+hint).show();
                    }else{
                        $("#"+hint).hide();
                    }
                }
                return get_data.replace(/[<>&"]/g,function(c){
                    return {
                        '<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;'
                    }[c];
                });
            }
        })
    </script>
@endsection