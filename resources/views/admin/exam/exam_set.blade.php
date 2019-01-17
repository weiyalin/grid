@extends('layout')

@section('header')
    <style type="text/css">
        label{padding:0 -15px;}
        .error{color:#f45551;display: none;line-height: 34px;margin-bottom: 0px;}
    </style>
@endsection

@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">{{ $title }}</span>
        </div><!--Widget Header-->
        <div class="widget-body">
            <div class="btn-group">
                <a href="/exam_setting" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="user_info" class="form-horizontal" style="margin:15px auto;width:90%;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">考核项<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $exam_data->name or null }}" maxlength="30" type="text" name="name" class="form-control" id="name" placeholder="输入考核项名（30字符以内）"/>
                        </div>
                        <label id="name-error" class="error" for="name">请输入考核项名</label>
                    </div>
                    <div class="form-group">
                        <label for="score" class="col-sm-2 control-label">分值<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $exam_data->score or null }}" type="number" min="0" name="score" class="form-control" id="score" placeholder="输入分值(不小于0)"/>
                        </div>
                        <label id="score-error" class="error" for="score">请仔细输入分值</label>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" exam_id="{{ $exam_data->id or null }}" id="exam_set" type="button">
                            <i class="fa fa-save"></i>保存
                        </button>
                    </div>
                </div>
            </form>
        </div><!--Widget Body-->
    </div><!--Widget-->

    <script>
        $(function(){
            /**
             * 考核项添加或修改
             */
            var exam_id = $("#exam_set").attr('exam_id');
            $("#exam_set").click(function(){
                var name = $.trim($("#name").val());
                var score = $.trim($("#score").val());

                if(name==''||name.length>30){
                    $("#name").focus();
                    $("#name-error").show();
                    return;
                }else{
                    $("#name-error").hide();
                }

                if(isNaN(score)||score<0){
                    $("#score").focus();
                    $("#score-error").show();
                    return;
                }else{
                    $("#score-error").hide();
                }

                $.ajax({
                    type: "post",
                    url: '/exam_setting_handle',
                    data: {
                        exam_id:exam_id,
                        name:name,
                        score:score,
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.result, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/exam_setting";
                            },1000)
                        }else{
                            Notify(data.result, 'top-right', '3000', "danger", 'fa-edit');
                        }
                    }
                });
            })

        })
    </script>

@endsection