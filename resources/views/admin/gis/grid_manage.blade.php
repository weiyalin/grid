@extends('layout')

@section('header')
    <style type="text/css">
        label{padding:0 -15px;}
        .error{color:#f45551;display: none;line-height: 34px;margin-bottom: 0px;}
        .map_div{
            height: 403px;
        }
        .big{
            z-index: 9999;
            width: 100%;
            height: 1100px;
            position: absolute;
            left: 0px;
            top: 20px;
        }
        #big_small{
            z-index: 1000;
            position: absolute;
            right: 13%;
            bottom: 17%;
            font-size: 25px;
            cursor: pointer;
            background: ghostwhite;
            width: 35px;
            line-height: 35px;
            text-align: center;
            border: 1px solid ghostwhite;
            border-radius: 5px;
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
    <textarea style="display: none;" id="map">
        <?php
            if(isset($grid_info->map)){
                echo $grid_info->map;
            }
        ?>
    </textarea>
    <script>
        var map_obj = $.trim($("#map").text());
    </script>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">{{ $title }}</span>
            <div class="widget-buttons">
                {{--<a href="#" data-toggle="maximize">--}}
                    {{--<i class="fa fa-expand"></i>--}}
                {{--</a>--}}
                {{--<a href="#" data-toggle="collapse">--}}
                    {{--<i class="fa fa-minus"></i>--}}
                {{--</a>--}}
                {{--<a href="#" data-toggle="dispose">--}}
                {{--<i class="fa fa-times"></i>--}}
                {{--</a>--}}
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div class="btn-group">
                <a href="/gps_grid_manage" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="user_info" class="form-horizontal" style="margin:15px auto;width:90%;">
                <!--第1行-->
                <div class="row">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">网格名称<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $grid_info->name or null }}" type="text" name="name" class="form-control" id="name" placeholder="输入网格名称"/>
                        </div>
                        <label id="name-error" class="error" for="name">请输入网格名称</label>
                    </div>
                    <div class="form-group">
                        <label for="short_name" class="col-sm-2 control-label">网格简称<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input value="{{ $grid_info->short_name or null }}" type="text" name="short_name" class="form-control" id="short_name" placeholder="输入网格简称"/>
                        </div>
                        <label id="short_name-error" class="error" for="short_name">请输入网格简称</label>
                    </div>
                    <div class="form-group">
                        <label for="level" class="col-sm-2 control-label">网格等级<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select name="level" id="level" data="{{ $grid_info->level or null }}" class="form-control">
                                <option value="">请选择等级...</option>
                                <option value="1">一级网格</option>
                                <option value="2">二级网格</option>
                                <option value="3">三级网格</option>
                                <option value="4">四级网格</option>
                            </select>
                        </div>
                        <label id="level-error" class="error" for="level">请选择等级</label>
                    </div>
                    <div class="form-group">
                        <label for="parent_id" class="col-sm-2 control-label">父级网格<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select id="parent_id" name="parent_id" data="{{ $grid_info->parent_id or null }}" tabindex="-1" class="select2-offscreen">
                                <option value="">请选择父级网格...</option>
                            </select>
                            {{--<select name="parent_id" id="parent_id" data="{{ $grid_info->parent_id or null }}" class="form-control">--}}
                                {{--<option value="">请选择父级网格...</option>--}}
                            {{--</select>--}}
                        </div>
                        <label id="parent_id-error" class="error" for="parent_id">请选择父级网格</label>
                    </div>
                    <div class="form-group">
                        <label for="manager_id" class="col-sm-2 control-label">网格长<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <select name="manager_id" id="manager_id" data="{{ $grid_info->manager_id or null }}" class="form-control">
                                <option value="">请选择网格长...</option>
                                @foreach($user as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label id="manager_id-error" class="error" for="manager_id">请选择网格长</label>
                    </div>

                    <div class="form-group">
                        <label for="grid_number" class="col-sm-2 control-label">直属网格数<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input min="0" value="{{ $grid_info->grid_number or null }}" type="number" name="grid_number" class="form-control" id="grid_number" placeholder="输入直属网格数"/>
                        </div>
                        <label id="grid_number-error" class="error" for="grid_number">请输入直属网格数</label>
                    </div>
                    <div class="form-group">
                        <label for="family_number" class="col-sm-2 control-label">所辖组户数量<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input min="0" value="{{ $grid_info->family_number or null }}" type="number" name="family_number" class="form-control" id="family_number" placeholder="输入所辖组户数量"/>
                        </div>
                        <label id="family_number-error" class="error" for="family_number">请输入所辖组户数量</label>
                    </div>
                    <div class="form-group">
                        <label for="fixed_population_number" class="col-sm-2 control-label">固定人口数量<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input min="0" value="{{ $grid_info->fixed_population_number or null }}" type="number" name="fixed_population_number" class="form-control" id="fixed_population_number" placeholder="输入固定人口数量"/>
                        </div>
                        <label id="fixed_population_number-error" class="error" for="fixed_population_number">请输入固定人口数量</label>
                    </div>
                    <div class="form-group">
                        <label for="fluid_population_number" class="col-sm-2 control-label">流动人口数量<span style="color:#f00">*</span></label>
                        <div class="col-sm-7">
                            <input min="0" value="{{ $grid_info->fluid_population_number or null }}" type="number" name="fluid_population_number" class="form-control" id="fluid_population_number" placeholder="输入流动人口数量"/>
                        </div>
                        <label id="fluid_population_number-error" class="error" for="fluid_population_number">请输入流动人口数量</label>
                    </div>

                    <div class="form-group">
                        <label for="manager_id" class="col-sm-2 control-label">划分区域</label>
                        <div class="col-sm-7 map_div">
                            <iframe src="/gps_grid_manage_map" id="grid_map" name="grid_map" style="width: 100%;border: 0px;height: 100%;"></iframe>
                            <span id="big_small"><i class="fa fa-expand"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" grid_id="{{ $grid_info->id or null }}" id="grid_set" type="button">
                            <i class="fa fa-save"></i>保存
                        </button>
                    </div>
                </div>
            </form>
        </div><!--Widget Body-->
    </div><!--Widget-->

    <script src="assets/js/select2/select2.js"></script>

    <script>
        $(function(){
            $("#parent_id").select2();
            var grid_id = $("#grid_set").attr("grid_id");
            if(grid_id!=0){
                $("#level,#parent_id").attr('disabled',true);
            }
            //下拉框对应选中
            if($("#level").attr('data')){
                $("#level option[value="+$("#level").attr('data')+"]").attr("selected", true);
                ajax_post('gps_grid_manage_parent',$("#level").attr('data'),'parent_id');
            }
            if($("#manager_id").attr('data')){
                $("#manager_id option[value="+$("#manager_id").attr('data')+"]").attr("selected", true);
            }

            $("#level").change(function(){
                var level = $(this).val();
                $("#parent_id").find("option[value!='']").remove();
                if(level!=''&&level!=1){
                    ajax_post('gps_grid_manage_parent',level,'parent_id');
                }
            })

            /**
             * 获取下拉信息
             * @param url 请求地址
             * @param post_data 参数
             * @param obj 操作对象
             */
            function ajax_post(url,post_data,obj){
                $.ajax({
                    type: "post",
                    url: url,
                    data: {data:post_data},
                    success: function (data) {
                        if (data.code==0) {
                            add_option(obj,data.result);
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
                    $("#"+obj).append("<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>");
                }
                if($("#parent_id").attr('data')){
                    $("#parent_id option[value="+$("#parent_id").attr('data')+"]").attr("selected", true);
                    $("#parent_id").attr('data',null);
                }
                if(obj=='parent_id'){
                    $("#parent_id").select2();
                }
            }


            /**
             * 添加网格
             */
            var post = true;
            $("#grid_set").click(function(){
                post = true;
                var name = get_data($("#name"),false,true,'name-error');
                var short_name = get_data($("#short_name"),false,true,'short_name-error');
                var level = get_data($("#level"),false,true,'level-error');
                var parent_id = get_data($("#parent_id"),false,false,'parent_id-error');
                var manager_id = get_data($("#manager_id"),false,true,'manager_id-error');
                var manager_name = $("#manager_id option[value="+manager_id+"]").text();


                var grid_number = get_data($("#grid_number"),true,true,'grid_number-error');
                var family_number = get_data($("#family_number"),true,true,'family_number-error');
                var fixed_population_number = get_data($("#fixed_population_number"),true,true,'fixed_population_number-error');
                var fluid_population_number = get_data($("#fluid_population_number"),true,true,'fluid_population_number-error');

                if(level!=1&&parent_id==''){//如果不是一级网格则父级网格不能为空
                    $("#parent_id-error").show();
                    post=false;
                }else{
                    $("#parent_id-error").hide();
                }
                if(level==1){//如果是一级网则父级网格设置为0
                    parent_id=0;
                }
                var map = document.getElementById('grid_map').contentWindow.map_obj;
                if (map.substring(map.length -1, map.length) == ";") {
                    map = map.substring(0,map.length-1);
                }
                if(!post){
                    return;
                }
                $.ajax({
                    type: "post",
                    url: 'gps_grid_manage_set',
                    data: {
                        grid_id:grid_id,
                        name:name,
                        short_name:short_name,
                        level:level,
                        parent_id:parent_id,
                        manager_id:manager_id,
                        manager_name:manager_name,

                        grid_number:grid_number,
                        family_number:family_number,
                        fixed_population_number:fixed_population_number,
                        fluid_population_number:fluid_population_number,

                        map:map
                    },
                    success: function (data) {
                        if (data.code==0) {
                            Notify(data.result, 'top-right', '3000', 'success', 'fa-check');
                            setTimeout(function(){
                                window.location.href="/gps_grid_manage";
                            },1000)
                        }else{
                            Notify(data.result, 'top-right', '3000', "danger", 'fa-edit');
                        }
                    }
                });
            })

            /**
             * 获取数据并验证
             * @param obj
             * @param neq_zero 不小于0验证
             * @param must 是否必须
             * @param hint 提示信息
             * @returns {string|*}
             */
            function get_data(obj,neq_zero,must,hint){
                var get_data = $.trim(obj.val());
                if(must){
                    if(get_data!=''){
                        $("#"+hint).hide();
                    }else{
                        post = false;
                        $("#"+hint).show();
                    }
                }
                if(get_data!=''&&neq_zero){
                    if(isNaN(get_data)||get_data<0){
                        post = false;
                        $("#"+hint).show();
                    }else{
                        $("#"+hint).hide();
                    }
                }
                return get_data;
            }

            //地图放大缩小
            var big = true;
            $("#big_small").click(function(){
                $(".map_div").toggleClass('big');
                $(this).find('i').toggleClass('fa-compress');
                $(this).find('i').toggleClass('fa-expand');
                if(!big){
                    document.getElementById('grid_map').contentWindow.recover();
                    big = true;
                }else{
                    document.getElementById('grid_map').contentWindow.full_screen(100,1000);
                    big = false;
                }
            })
            //ESC缩小地图
            document.onkeydown = function(e){
                if(e.keyCode==27&&!big){
                    $("#big_small").click();
                }
            }

            //获取父级网格及同级网格并划到地图上
            $("#parent_id").change(function(){
                var parent_id = $(this).val();
                if(!parent_id){
                    return;
                }
                $.ajax({
                    type: "post",
                    url: '/gps_grid_manage_parent_info',
                    data: {parent_id:parent_id},
                    success: function (data) {
                        if (data.code==0) {
                            if(data.result!=''){
                                document.getElementById('grid_map').contentWindow.set_map(data.result);
                            }
                        }
                    }
                });
            })

        })
    </script>

@endsection