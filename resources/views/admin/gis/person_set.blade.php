@extends('layout')
@section('header')
    <link href="jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <style>
        .error{color:#f45551;display: none;line-height: 34px;margin-bottom: 0px;}
        .row{margin:5px 0;}
        .help-block{color:#f00;}
        .bootstrap-tagsinput{
            float: left;
        }
        .hint{
            line-height: 34px;
            margin-left: 15px;
            color: gray;
        }
        .form-info{
            display: block;
            width: 100%;
            height: 34px;
            font-size: 13px;
            color: #858585;
            padding: 6px 0px;
        }
        .ui-autosearch-content{background: #fff;border:1px solid #ddd;max-height: 200px;overflow: auto;}
        .ui-autosearch-content .item{overflow: hidden;text-overflow:ellipsis;  white-space: nowrap; height: 24px;line-height: 24px;box-sizing:border-box;padding-left:10px;cursor: pointer;}
        .ui-autosearch-content .item:hover,.ui-autosearch-content .item.current{background-color: #f2f2f2;}
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
                <a href="/gps_employee" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--第1行-->
                <div class="row">
                    {{--<div class="form-group">
                        <label for="population_id" class="col-sm-2 control-label">证件号：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $person_info->card_code or null }}" content="{{ $person_info->population_id or 0 }}" type="text" name="population_id" class="form-control" id="population_id" placeholder="输入证件号"/>
                        </div>
                        <label id="population_id-error" class="error" for="population_id">请正确输入证件号</label>
                    </div>--}}
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">人员姓名：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $person_info->name or null }}" content="{{ $person_info->population_id or 0 }}"  maxlength="20" type="text" name="name" class="form-control" id="name"/>
                        </div>
                        <label id="name-error" class="error" for="name">人员姓名不可为空</label>
                    </div>
                    <div class="form-group">
                        <label id="default_sex" content="{{ $person_info->sex or 9 }}" for="sex" class="col-sm-2 control-label">性别：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="sex" value="1" data-bv-field="sex">
                                    <span class="text">男</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="sex" value="2" data-bv-field="sex">
                                    <span class="text">女</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="sex" value="0" data-bv-field="sex">
                                    <span class="text">未知</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="sex" value="9" data-bv-field="sex">
                                    <span class="text">未说明</span>
                                </label>
                            </div>
                        </div>
                        <label id="sex-error" class="error" for="sex">人员性别不可为空</label>
                    </div>
                    <div class="form-group">
                        <label for="contact_address" class="col-sm-2 control-label">家庭住址：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $person_info->contact_address or null }}" maxlength="20" type="text" placeholder="请输入家庭住址" name="contact_address" class="form-control" id="contact_address"/>
                        </div>
                        <label id="contact_address-error" class="error" for="contact_address">家庭住址不可为空</label>
                    </div>
                    <div class="form-group">
                        <label for="unit" class="col-sm-2 control-label">工作单位：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $person_info->unit or null }}" type="text" maxlength="200" placeholder="请输入工作单位" name="unit" class="form-control" id="unit"/>
                        </div>
                        <label id="unit-error" class="error" for="unit">工作单位不可为空</label>
                    </div>
                    <div class="form-group">
                        <label for="mobile" class="col-sm-2 control-label">联系方式：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $person_info->mobile or null }}" type="text" maxlength="20" name="mobile" class="form-control" id="mobile"/>
                        </div>
                        <label id="mobile-error" class="error" for="mobile">联系方式输入有误</label>
                    </div>
                    <div class="form-group">
                        <label id="default_is_party" content="{{ $person_info->is_party or 0 }}" for="is_party" class="col-sm-2 control-label">是否为党员：<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_party" value="1" data-bv-field="is_party">
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_party" value="0" data-bv-field="is_party">
                                    <span class="text">否</span>
                                </label>
                            </div>
                        </div>
                        {{--<label id="mobile-error" class="error" for="mobile">是否为党员设置出错</label>--}}
                    </div>
                    <div class="form-group">
                        <label for="is_party" class="col-sm-2 control-label">个人标签：</label>
                        <div class="col-sm-7" id="label_set">
                            <input id="label" type="text" value="{{ $label_data }}" class="form-control" name="label" data-role="tagsinput" style="display: none;">
                        </div>
                        <span class="hint">提示：个性标签间以空格分割</span>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" person_id="{{ $person_info->id or 0 }}" id="person_set" type="button">
                            <i class="fa fa-save"></i>保存
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="assets/js/tagsinput/bootstrap-tagsinput.js"></script>
    <script src="jquery-ui/jquery-ui.min.js"></script>
    <script>
        $(function(){
            $('input[name="sex"][value="'+$("#default_sex").attr('content')+'"]').click();
            $('input[name="is_party"][value="'+$("#default_is_party").attr('content')+'"]').click();
            var post = true;
            var obj = $("#person_set").attr('person_id');
            if(obj!=0){
                $("#name").attr('disabled',true);
            }
            $("#person_set").click(function(){
                post = true;
                var population_id = $("#name").attr('content');
                if(population_id==0){
                    Notify('请正确完整的填写信息', 'top-right', '3000', "danger", 'fa-edit');
                    return;
                }
                var name = get_data($("#name"),false,true,'name-error');
                var sex = $("input[name='sex']:checked").val();
                var contact_address = get_data($("#contact_address"),false,true,'contact_address-error');
                var unit = get_data($("#unit"),false,true,'unit-error');
                var mobile = get_data($("#mobile"),false,true,'mobile-error');
                var is_party = $("input[name='is_party']:checked").val();
                var label = get_data($("#label"),false,false,'');

                if(!post){
                    return;
                }
                $.ajax({
                    type: "post",
                    url: '/gps_population_info_set',
                    data: {
                        obj:obj,
                        population_id:population_id,
                        name:name,
                        sex:sex,
                        contact_address:contact_address,
                        unit:unit,
                        mobile:mobile,
                        is_party:is_party,
                        label:label,
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.msg, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/gps_population";
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

            $( "#name" ).autocomplete({// searchStreedRoad_auto 输入框id
                source: function( request, response ) {
                    $.ajax({
                        url: 'gps_employee_get_info', // 后台请求路径
                        dataType: "json",
                        type: "post",
                        data:{
                            name: request.term    // 获取输入框内容
                        },
                        success: function( data ) {
                            if(data['code']==0){
                                response( $.map(data['msg'], function( item ) { // 此处是将返回数据转换为 JSON对象，并给每个下拉项补充对应参数
                                    return {
                                        //设置item信息
                                        label: item.name +"    "+item.card_code+"    "+item.contact_address, // 下拉项显示内容
                                        value: item.name,   // 下拉项对应数值【名字】
                                        sex:item.sex, // 性别， 可以自定义
                                        is_party:item.is_party, // 是否是党员， 可以自定义
                                        content:item.id, // 人员id， 可以自定义
                                        contact_address:item.contact_address,//联系方式
                                        contact_phone:item.contact_phone,//联系方式
                                        population_label:item.label, // 其他参数， 可以自定义
                                    }
                                }));
                                $("#name-error").text('人员姓名不可为空');
                                $("#name-error").hide();
                            }else{
                                $("#name-error").text(data.msg);
                                $("#name-error").show();
                            }
                        }
                    });
                },
                minLength: 1,  // 输入框字符个等于2时开始查询
                select: function( event, ui ) { // 选中某项时执行的操作
                    // 存放选中选项的信息
                    $("#name").attr('content',ui.item.content);
                    $("#mobile").val(ui.item.contact_phone);
                    $("#contact_address").val(ui.item.contact_address);
                    var sex = ui.item.sex;
                    var is_party = ui.item.is_party;
                    $('input[name="sex"][value="'+sex+'"]').click();
                    $('input[name="is_party"][value="'+is_party+'"]').click();

                    $("#label_set").children().remove();
                    $("#label_set").prepend('<input id="label" type="text" value="'+ui.item.population_label+'" class="form-control" name="label" data-role="tagsinput" style="display: none;">')
                    var js = 'assets/js/tagsinput/bootstrap-tagsinput.js';
                    $.getScript(js);
                }
            });
        })
    </script>
@endsection