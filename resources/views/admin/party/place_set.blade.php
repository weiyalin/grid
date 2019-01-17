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
                <a href="/party_place" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">场所名称:</label>
                        <div class="col-sm-7">
                            <input value="{{ $place_info->name or null }}" maxlength="50" type="text" placeholder="请输入场所名称" name="name" class="form-control" id="name"/>
                        </div>
                        <label id="name-error" class="error" for="name">场所名称不能为空</label>
                        <label id="name-error1" class="error" for="name">场所已存在</label>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">场所地址:</label>
                        <div class="col-sm-7">
                            <input value="{{ $place_info->address or null }}" maxlength="500" type="text" placeholder="场所地址" name="address" class="form-control" id="address"/>
                        </div>
                        <label id="address-error" class="error" for="address">场所地址不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">场所联系方式:</label>
                        <div class="col-sm-7">
                            <input value="{{ $place_info->phone or null }}" maxlength="20" type="text" placeholder="场所联系方式" name="phone" class="form-control" id="phone"/>
                        </div>
                        <label id="gravidity_type-error" class="error" for="gravidity_type">场所联系方式不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="manager" class="col-sm-2 control-label">负责人{{--<span style="color:#f00">*</span>--}}</label>
                        <div class="col-sm-7">
                            <input value="{{ $place_info->manager or null }}" type="text" maxlength="20" placeholder="负责人" name="manager" class="form-control" id="manager"/>
                        </div>
                        <label id="manager-error" class="error" for="manager">负责人不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="manager_phone" class="col-sm-2 control-label">负责人电话{{--<span style="color:#f00">*</span>--}}</label>
                        <div class="col-sm-7">
                            <input value="{{ $place_info->manager_phone or null }}" type="text" maxlength="20" placeholder="负责人电话" name="manager_phone" class="form-control" id="manager_phone"/>
                        </div>
                        <label id="manager_phone-error" class="error" for="manager_phone">负责人电话不能为空</label>
                    </div>


                    <div class="form-group">
                        <label for="manager" class="col-sm-2 control-label">场所分类{{--<span style="color:#f00">*</span>--}}</label>
                        <div class="col-sm-2">
                            <select name="type" class="form-control">
                                @foreach($type_list as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label id="manager-error" class="error" for="manager">需选择一个场所分类</label>
                    </div>
                    

                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button disabled class="btn btn-primary btn-lg" place_id="{{ $place_info->id or 0 }}" id="place_set" type="button">
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
            var obj = $("#place_set").attr('place_id');
            if(obj!=0){
                $("#place_set").attr('disabled',false);
            }
            var post = false;
            $("#place_set").click(function(){
                post = true;
                var name = get_data($("#name"),false,true,'name-error');
                var address = get_data($("#address"),false,true,'address-error');
                var phone = get_data($("#phone"),false,false,'phone-error');
                var manager = get_data($("#manager"),false,false,'manager-error');
                var manager_phone = get_data($("#manager_phone"),false,false,'manager_phone-error');

                if(!post){
                    return;
                }
                $(this).attr('disabled',true);
                $.ajax({
                    type: "post",
                    url: '/party_place_info_set',
                    data: {
                        obj:obj,
                        name:name,
                        address:address,
                        phone:phone,
                        manager:manager,
                        manager_phone:manager_phone,
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.msg, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/party_place";
                            },1000)
                        }else{
                            Notify(data.msg, 'top-right', '3000', "danger", 'fa-edit');
                            $("#place_set").attr('disabled',false);
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

            $("#name").change(function(){
                $("#name").blur(function(){
                    var name = get_data($("#name"),false,true,'name-error');
                    if(!name){
                        return;
                    }
                    $.ajax({
                        type: "post",
                        url: '/party_place_name_check',
                        data: {
                            obj:obj,
                            name:name,
                        },
                        success: function (data) {
                            if (data.code==1) {
                                $("#name-error1").show();
                                $("#place_set").attr('disabled',true);
                            }else{
                                $("#name-error1").hide();
                                $("#place_set").attr('disabled',false);
                            }
                        }
                    });
                })
            })
        })
    </script>
@endsection