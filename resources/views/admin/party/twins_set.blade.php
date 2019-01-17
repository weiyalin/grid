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
                <a href="/party_twins" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">名称:</label>
                        <div class="col-sm-7">
                            <input value="{{ $twins_info->name or null }}" maxlength="50" type="text" placeholder="请输入名称" name="name" class="form-control" id="name"/>
                        </div>
                        <label id="title-error" class="error" for="name">名称不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="url" class="col-sm-2 control-label">地址:</label>
                        <div class="col-sm-7">
                            <input value="{{ $twins_info->url or null }}" maxlength="80" type="text" placeholder="请输入地址地址" name="url" class="form-control" id="url"/>
                        </div>
                        <label id="url-error" class="error" for="url">地址不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="longitude" class="col-sm-2 control-label">经度</label>
                        <div class="col-sm-7">
                            <input value="{{ $twins_info->longitude or null }}" maxlength="18" type="text" placeholder="请输入经度" name="longitude" class="form-control" id="longitude"/>
                        </div>
                        <label id="longitude-error" class="error" for="longitude">经度不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="latitude" class="col-sm-2 control-label">纬度</label>
                        <div class="col-sm-7">
                            <input value="{{ $twins_info->latitude or null }}" maxlength="18" type="text" placeholder="请输入经度" name="latitude" class="form-control" id="latitude"/>
                        </div>
                        <label id="latitude-error" class="error" for="latitude">纬度不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="last_view_time" class="col-sm-2 control-label">最后查看时间</label>
                        <div class="col-sm-7">
                            <input value="{{ $twins_info->last_view_time or null }}" maxlength="20" type="text" placeholder="最后查看时间" name="last_view_time" class="form-control" id="last_view_time"/>
                        </div>
                        <label id="last_view_time-error" class="error" for="last_view_time">最后查看时间</label>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" type="{{ $type or 'add' }}" twins_id="{{ $twins_info->id or 0 }}" id="twins_set" type="button">
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
            var obj = $("#twins_set").attr('twins_id');
            var type = $("#twins_set").attr('type');

            if(obj!=0&&type=='see'){
                $('input').attr('disabled',true);
                $("#twins_set").hide();
            }else{
                $("#last_view_time").parent().parent().hide();
            }
            var post = false;
            $("#twins_set").click(function(){

                post = true;
                var name = get_data($("#name"),false,true,'name-error');
                var url = get_data($("#url"),false,true,'url-error');
                var longitude = get_data($("#longitude"),false,true,'longitude-error');
                var latitude = get_data($("#latitude"),false,true,'latitude-error');
                if(!post){
                    return;
                }
                $(this).attr('disabled',true);
                $.ajax({
                    type: "post",
                    url: '/party_twins_info_set',
                    data: {
                        obj:obj,
                        name:name,
                        url:url,
                        longitude:longitude,
                        latitude:latitude
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.msg, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/party_twins";
                            },1000)
                        }else{
                            //Notify(data.msg, 'top-right', '3000', "danger", 'fa-edit');
                            $("#twins_set").attr('disabled',false);
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
                return get_data;
            }
        })
    </script>
@endsection