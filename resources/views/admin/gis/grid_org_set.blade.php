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
        .select2-container{
            width: 100%;
        }
        .select2-choice,.select2-arrow{
            background-color: #fbfbfb!important;
            border-radius: 4px;
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
                <a href="/gps_grid_org" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">机构名称:<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $org_info->name or null }}" maxlength="100" type="text" placeholder="请输入机构名称" name="name" class="form-control" id="name"/>
                        </div>
                        <label id="name-error" class="error" for="name">机构名称不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-2 control-label">机构类型:<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $org_info->type or null }}" maxlength="30" type="text" placeholder="机构类型" name="type" class="form-control" id="type"/>
                        </div>
                        <label id="type-error" class="error" for="type">机构类型不能为空</label>
                    </div>


                    <div class="form-group">
                        <label for="grid_1" class="col-sm-2 control-label">一级网格<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select id="grid_1" name="grid_1" tabindex="-1" class="select2-offscreen">
                                <option value="">请选择一级网格...</option>
                                @if(!empty($grid_info_1))
                                    @foreach($grid_info_1 as $val)
                                        @if($val->id == $grid_info->grid_1)
                                            <option selected value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @else
                                            <option value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            {{--<select name="parent_id" id="parent_id" data="{{ $grid_info->parent_id or null }}" class="form-control">--}}
                            {{--<option value="">请选择父级网格...</option>--}}
                            {{--</select>--}}
                        </div>
                        <label id="grid_1-error" class="error" for="grid_1">请选择二级网格</label>
                    </div>
                    <div class="form-group">
                        <label for="grid_2" class="col-sm-2 control-label">二级网格<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select id="grid_2" name="grid_2" tabindex="-1" class="select2-offscreen">
                                <option value="">请选择二级网格...</option>
                                @if(!empty($grid_info_1))
                                    @foreach($grid_info_2 as $val)
                                        @if($val->id == $grid_info->grid_2)
                                            <option selected value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @else
                                            <option value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            {{--<select name="parent_id" id="parent_id" data="{{ $grid_info->parent_id or null }}" class="form-control">--}}
                            {{--<option value="">请选择父级网格...</option>--}}
                            {{--</select>--}}
                        </div>
                        <label id="grid_2-error" class="error" for="grid_2">请选择二级网格</label>
                    </div>
                    <div class="form-group">
                        <label for="grid_3" class="col-sm-2 control-label">三级网格<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select id="grid_3" name="grid_3" tabindex="-1" class="select2-offscreen">
                                <option value="">请选择三级网格...</option>
                                @if(!empty($grid_info_1))
                                    @foreach($grid_info_3 as $val)
                                        @if($val->id == $grid_info->grid_3)
                                            <option selected value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @else
                                            <option value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            {{--<select name="parent_id" id="parent_id" data="{{ $grid_info->parent_id or null }}" class="form-control">--}}
                            {{--<option value="">请选择父级网格...</option>--}}
                            {{--</select>--}}
                        </div>
                        <label id="grid_3-error" class="error" for="grid_3">请选择三级网格</label>
                    </div>
                    <div class="form-group">
                        <label for="grid_4" class="col-sm-2 control-label">四级网格<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select id="grid_4" name="grid_4" tabindex="-1" class="select2-offscreen">
                                <option value="">请选择四级网格...</option>
                                @if(!empty($grid_info_1))
                                    @foreach($grid_info_4 as $val)
                                        @if($val->id == $grid_info->grid_id)
                                            <option selected value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @else
                                            <option value="{{ $val->id }}">{{ $val->short_name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            {{--<select name="parent_id" id="parent_id" data="{{ $grid_info->parent_id or null }}" class="form-control">--}}
                            {{--<option value="">请选择父级网格...</option>--}}
                            {{--</select>--}}
                        </div>
                        <label id="grid_4-error" class="error" for="grid_4">请选择四级网格</label>
                    </div>


                    <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">机构地址:<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $org_info->address or null }}" maxlength="500" type="text" placeholder="机构地址" name="address" class="form-control" id="address"/>
                        </div>
                        <label id="address-error" class="error" for="address">机构地址不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="mobile" class="col-sm-2 control-label">机构联系方式:<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $org_info->mobile or null }}" maxlength="20" type="text" placeholder="机构联系方式" name="mobile" class="form-control" id="mobile"/>
                        </div>
                        <label id="mobile-error" class="error" for="mobile">机构联系方式不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="manager" class="col-sm-2 control-label">负责人:<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $org_info->manager or null }}" type="text" maxlength="20" placeholder="负责人" name="manager" class="form-control" id="manager"/>
                        </div>
                        <label id="manager-error" class="error" for="manager">负责人不能为空</label>
                    </div>
                    <div class="form-group">
                        <label for="manager_mobile" class="col-sm-2 control-label">负责人电话:<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $org_info->manager_mobile or null }}" type="text" maxlength="20" placeholder="负责人电话" name="manager_mobile" class="form-control" id="manager_mobile"/>
                        </div>
                        <label id="manager_mobile-error" class="error" for="manager_mobile">负责人电话不能为空</label>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" org_id="{{ $org_info->id or 0 }}" id="org_set" type="button">
                            <i class="fa fa-save"></i>保存
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="assets/js/select2/select2.js"></script>
    <script>
        $(function(){
            function create_select2_obj(){
                $("#grid_1").select2();
                $("#grid_2").select2();
                $("#grid_3").select2();
                $("#grid_4").select2();
            }
            create_select2_obj();

            var obj = $("#org_set").attr('org_id');
            if(obj==0){
                ajax_post('data_bear_get_grid',0,'grid_1');
            }
            var post = false;
            $("#org_set").click(function(){
                post = true;
                var name = get_data($("#name"),false,true,'name-error');
                var type = get_data($("#type"),false,true,'type-error');
                var address = get_data($("#address"),false,true,'address-error');
                var mobile = get_data($("#mobile"),false,true,'mobile-error');
                var manager = get_data($("#manager"),false,true,'manager-error');
                var manager_mobile = get_data($("#manager_mobile"),false,true,'manager_mobile-error');

                var grid_1 = get_data($("#grid_1"),false,true,'grid_1-error');
                var grid_2 = get_data($("#grid_2"),false,true,'grid_2-error');
                var grid_3 = get_data($("#grid_3"),false,true,'grid_3-error');
                var grid_4 = get_data($("#grid_4"),false,true,'grid_4-error');

                if(!post){
                    return;
                }
                $(this).attr('disabled',true);
                $.ajax({
                    type: "post",
                    url: '/gps_grid_org_info_set',
                    data: {
                        obj:obj,
                        name:name,
                        type:type,
                        address:address,
                        mobile:mobile,
                        manager:manager,
                        manager_mobile:manager_mobile,
                        grid_id:grid_4,
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.msg, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/gps_grid_org";
                            },1000)
                        }else{
                            Notify(data.msg, 'top-right', '3000', "danger", 'fa-edit');
                            $("#org_set").attr('disabled',false);
                        }
                    },
                    error:function(){
                        Notify("系统错误，请刷新重试！", 'top-right', '3000', "danger", 'fa-edit');
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


            /**
             * 获取下拉信息
             * @param url 请求地址
             * @param post_data 参数
             */
            function ajax_post(url,post_data,obj){
                $.ajax({
                    type: "post",
                    url: url,
                    data: {data:post_data},
                    success: function (data) {
                        if (data.code==0) {
                            add_option(obj,data.msg);
                        }
                    }
                });
            }
            /**
             * 下拉框选项设置及选中
             * @param obj
             * @param data
             */
            function add_option(obj,data){
                $("#"+obj).find("option[value!='']").remove();
                for(var i in data){
                    $("#"+obj).append("<option value='"+data[i]['id']+"'>"+data[i]['short_name']+"</option>");
                }
                create_select2_obj();
            }
            //选择网格
            $("#grid_1").change(function(){
                var level = $(this).val();
                if(!level){
                    return;
                }
                $("#grid_2").find("option[value!='']").remove();
                $("#grid_3").find("option[value!='']").remove();
                $("#grid_4").find("option[value!='']").remove();
                ajax_post('data_bear_get_grid',level,'grid_2');
            })
            $("#grid_2").change(function(){
                var level = $(this).val();
                $("#grid_3").find("option[value!='']").remove();
                $("#grid_4").find("option[value!='']").remove();
                ajax_post('data_bear_get_grid',level,'grid_3');
            })
            $("#grid_3").change(function(){
                var level = $(this).val();
                $("#grid_4").find("option[value!='']").remove();
                ajax_post('data_bear_get_grid',level,'grid_4');
            })
            $("#grid_4").change(function(){

            })
        })
    </script>
@endsection