@extends('layout')

@section('content')
    <style>
        .right-align{
            text-align: right;
        }
    </style>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">事件自动研判设置</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
                {{--<a href="#" data-toggle="dispose">--}}
                {{--<i class="fa fa-times"></i>--}}
                {{--</a>--}}
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div id="horizontal-form">
                {{--<div class="row margin-top-10">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="code" class="margin-top-10">编号</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="code" placeholder="" value="{{$category->code or null }}">
                        </div>
                    </div>
                </div>--}}
                <div class="row margin-top-10">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="name" class="margin-top-10">名称<span style="color: red;">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="" value="{{$category->name or null }}">
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10 parent_div">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="level" class="margin-top-10">级别<span style="color: red;">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select id="level" content="{{$category->level or 0}}" class="form-control">
                                <option value="0">选择级别</option>
                                <option value="1">一级分类</option>
                                <option value="2">二级分类</option>
                                <option value="3">三级分类</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10 top_type">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="top_type" class="margin-top-10">选择顶级分类<span style="color: red;">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select id="top_type" content="{{$category->top_type or 0}}" class="form-control">
                                <option content="0" value="0">选择一级分类</option>
                                @foreach($parent_code as $org)
                                    <option content="{{$org->code}}" value="{{$org->id}}">{{$org->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10 next_type">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="next_type" class="margin-top-10">选择次级分类<span style="color: red;">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select id="next_type" content="{{$category->next_type or 0}}" class="form-control">
                                <option value="0">选择二级分类</option>
                                @foreach($next_code as $org)
                                    <option content="{{$org->code}}" value="{{$org->id}}">{{$org->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="event_org" class="margin-top-10">受理部门</label>
                        </div>
                        <div class="col-sm-9">
                            <select id="event_org"  class="form-control">
                                <option value="0">选择受理部门</option>
                                @foreach($org_list as $org)
                                    <option value="{{$org->id}}">{{$org->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="form-group">
                        <div class="col-sm-2 right-align">
                            <label for="name" class="margin-top-10">事件隶属</label>
                        </div>
                        <div class="col-sm-9">
                            <select id="event_department"  class="form-control">
                                <option value="0">选择隶属</option>
                                @foreach($department_list as $department)
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <br/>
                <div class="row margin-top-10 margin-top-10 margin-bottom-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">

                            </div>
                            <div class="col-sm-3">
                                <button id="btnSave" type="button" class="btn btn-success" onclick="">保存</button>
                            </div>
                            <div class="col-sm-2">
                                <button id="btnReturn" type="button" class="btn btn-success" onclick="javascript:location.href='/event_auto_determine'">返回</button>

                            </div>

                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>

                <input type="hidden" id="org_id" value="{{$category->org_id or 0}}"/>
                <input type="hidden" id="department_id" value="{{$category->department_id or 0}}"/>

                <input type="hidden" id="category_id" value="{{$category->id or 0}}"/>
            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <script type="text/javascript">
        var category_id = $('#category_id').val();//对象ID

        $('#btnSave').click(function(){
//            var code = $('#code').val();
            var name = $('#name').val();//名称
            if(!name){
                Notify('名称不能为空！', 'top-right', '3000', 'danger', 'fa-edit', true);
                return;
            }
            var level = $('#level').val();//分类
            var org_id = $('#event_org').val();//受理部门
            var department_id = $('#event_department').val();//隶属部门
            var parent_id = 0;
            if(level==0){
                Notify('请选择级别！', 'top-right', '3000', 'danger', 'fa-edit', true);
                return;
            } else if(level==2){
                parent_id = $("#top_type").val();
                if(parent_id==0){
                    Notify('请选择父级分类！', 'top-right', '3000', 'danger', 'fa-edit', true);
                    return;
                }
            }else if(level==3){
                parent_id = $("#next_type").val();
                if(parent_id==0){
                    Notify('请选择父级分类！', 'top-right', '3000', 'danger', 'fa-edit', true);
                    return;
                }
            }else if(level == 1){
                parent_id = 0;
            }
            //alert(category_id);
            $.ajax({
                type: "post",
                url: '/event_auto_determine_save',
                data: {
                    category_id:category_id,
                    org_id:org_id,
                    department_id:department_id,

                    name:name,
                    parent_id:parent_id,
                    level:level,
                },
                success: function (data) {
                    if (data.code==0) {

                        Notify('设置成功', 'top-right', '3000', 'success', 'fa-check', true);
                        location.href='/event_auto_determine';
                    } else {
                        Notify('设置失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        })

        $('#level').change(function () {
            var level = $(this).val();
            set_type_select(level);
            set_option();
        })
        $("#top_type").change(function () {
            set_option();
        })

        function set_type_select(level) {
            if(level==0||level==1){
                $(".top_type").hide();
                $(".next_type").hide();
                return;
            }else if(level==2){
                $(".top_type").show();
                $(".next_type").hide();
            }else if (level==3){
                $(".top_type").show();
                $(".next_type").show();
            }
        }
        function set_option() {
            var level = $("#level").val();
            var code = $("#top_type").find("option:selected").attr('content');
            if(level<=2||code==0){
                return;
            }
            $("#next_type option[value!='0']").remove();
            $.ajax({
                type: "post",
                url: '/event_auto_determine_get_child',
                data: {
                    code:code,
                },
                success: function (data) {
                    var option = '';
                    for (var i = 0; i < data.msg.length; i++) {
                        option = '<option content="' + data.msg[i]['code'] + '" value="' + data.msg[i]['id'] + '">' + data.msg[i]['name'] + '</option>';
                        $("#next_type").append(option);
                    }
                },
                error:function () {
                    Notify('出错了！', 'top-right', '3000', 'danger', 'fa-edit', true);
                }
            });
        }

        //下拉框设置选中
        var old_level = $("#level").attr('content');
        set_type_select(old_level);
        $("#level").find("option[value="+old_level+"]").attr("selected",true);
        $("#top_type").find("option[content="+$("#top_type").attr('content')+"]").attr("selected",true);
        $("#next_type").find("option[content="+$("#next_type").attr('content')+"]").attr("selected",true);
        $('#event_org').val($('#org_id').val());
        $('#event_department').val($('#department_id').val());


    </script>


@endsection