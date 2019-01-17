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
                <a href="/data_bear" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="woman_id" class="col-sm-2 control-label">女方证件号<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->woman_id or null }}" content="{{ $bear_info->population_id or 0 }}" type="text" name="woman_id" class="form-control" id="woman_id" placeholder="输入女方证件号"/>
                        </div>
                        <label id="woman_id-error" class="error" for="woman_id">请正确输入女方证件号</label>
                    </div>
                    <div class="form-group">
                        <label for="woman_name" class="col-sm-2 control-label">女方姓名<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->woman_name or null }}" maxlength="20" type="text" disabled name="woman_name" class="form-control" id="woman_name"/>
                        </div>
                        <label id="woman_name-error" class="error" for="woman_name">女方姓名不可为空</label>
                    </div>
                    <div class="form-group">
                        <label for="woman_age" class="col-sm-2 control-label">女方出生日期<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->woman_age or null }}" type="text" disabled name="woman_age" class="form-control" id="woman_age"/>
                        </div>
                        <label id="woman_age-error" class="error" for="woman_age">女方出生日期不可为空</label>
                    </div>
                    <div class="form-group">
                        <label for="man_name" class="col-sm-2 control-label">男方姓名</label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->man_name or null }}" maxlength="20" type="text" placeholder="请输入男方姓名" name="man_name" class="form-control" id="man_name"/>
                        </div>
                        <label id="man_name-error" class="error" for="man_name">男方姓名输入有误</label>
                    </div>
                    <div class="form-group">
                        <label for="man_age" class="col-sm-2 control-label">男方出生日期</label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->man_age or null }}" type="text" placeholder="格式：1990-01-01" name="man_age" class="form-control" id="man_age"/>
                        </div>
                        <label id="man_age-error" class="error" for="man_age">男方出生日期输入有误</label>
                    </div>
                    <div class="form-group">
                        <label for="merry_type" class="col-sm-2 control-label">婚姻类型<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select id="merry_type" data-content="{{ $bear_info->merry_type or null }}" class="form-control" name="merry_type">
                                <option value=''>--请选择婚姻类型--</option>
                                <option value='10'>未婚</option>
                                <option value='20'>已婚</option>
                                <option value='21'>初婚</option>
                                <option value='22'>再婚</option>
                                <option value='23'>复婚</option>
                                <option value='30'>丧偶</option>
                                <option value='40'>离婚</option>
                                <option value='90'>未说明</option>
                            </select>
                        </div>
                        <label id="merry_type-error" class="error" for="merry_type">请选择婚姻类型</label>
                    </div>
                    <div class="form-group">
                        <label for="gravidity_type" class="col-sm-2 control-label">孕育说明</label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->gravidity_type or null }}" maxlength="50" type="text" placeholder="孕育说明" name="gravidity_type" class="form-control" id="gravidity_type"/>
                        </div>
                        <label id="gravidity_type-error" class="error" for="gravidity_type">孕育说明输入有误</label>
                    </div>
                    <div class="form-group">
                        <label for="birth_date" class="col-sm-2 control-label">待产日期<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->birth_date or null }}" type="text" placeholder="格式：1990-01-01" name="birth_date" class="form-control" id="birth_date"/>
                        </div>
                        <label id="birth_date-error" class="error" for="birth_date">预产日期输入有误</label>
                    </div>
                    <div class="form-group">
                        <label for="children" class="col-sm-2 control-label">当前子女数<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $bear_info->children or null }}" type="number" min="0" name="children" class="form-control" id="children"/>
                        </div>
                        <label id="children-error" class="error" for="children">子女人数输入有误</label>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" bear_id="{{ $bear_info->id or 0 }}" id="bear_set" type="button">
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
            var time_check = /^(\d{4})\-(\d{2})\-(\d{2})$/;
            var post = true;
            var obj = $("#bear_set").attr('bear_id');
            if(obj!=0){
                $("#merry_type option[value="+$("#merry_type").data('content')+"]").attr("selected", true);
                $("#woman_id").attr('disabled',true);
            }
            $("#bear_set").click(function(){
                post = true;
                var population_id = $("#woman_id").attr('content');
                if(population_id==0){
                    Notify('请正确完整的填写信息', 'top-right', '3000', "danger", 'fa-edit');
                    return;
                }
                var woman_name = get_data($("#woman_name"),false,true,'woman_name-error');
                var woman_age = get_data($("#woman_age"),time_check,true,'woman_age-error');
                var man_name = get_data($("#man_name"),false,false,'man_name-error');
                var man_age = get_data($("#man_age"),time_check,false,'man_age-error');
                var merry_type = $("#merry_type").val();
                if(!merry_type){
                    $('#merry_type-error').show();
                    post=false;
                }else{
                    $('#merry_type-error').hide();
                }
                var gravidity_type = get_data($("#gravidity_type"),false,false,'gravidity_type-error');
                var birth_date = get_data($("#birth_date"),time_check,true,'birth_date-error');
                var children = get_data($("#children"),false,true,'children-error');

                if(!post){
                    return;
                }
                $.ajax({
                    type: "post",
                    url: '/data_bear_add_save',
                    data: {
                        obj:obj,
                        population_id:population_id,
                        woman_name:woman_name,
                        woman_age:woman_age,
                        man_age:man_age,
                        man_name:man_name,
                        merry_type:merry_type,
                        gravidity_type:gravidity_type,
                        birth_date:birth_date,
                        children:children,
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.msg, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/data_bear";
                            },1000)
                        }else{
                            Notify(data.msg, 'top-right', '3000', "danger", 'fa-edit');
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

            $("#woman_id").blur(function(){
                var woman_id = $.trim($(this).val());
                if(woman_id){
                    $.ajax({
                        type: "post",
                        url: '/data_bear_get_women',
                        data: {card_code:woman_id},
                        success: function (data) {
                            if(data['code']==0){
                                $("#woman_name").val(data['msg']['name']);
                                $("#woman_age").val(data['msg']['birthday']);
                                $("#woman_id").attr('content',data['msg']['id']);
                                $("#woman_id-error").hide();
                            }else{
                                $("#woman_id-error").text(data.msg);
                                $("#woman_id-error").show();
                            }
                        }
                    });
                }else{
                    $("#woman_id-error").text('请正确输入女方证件号');
                    $("#woman_id-error").show();
                }
            })
        })
    </script>
@endsection