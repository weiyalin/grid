@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .right-align{
            text-align: right;
        }
    </style>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">事件查询</span>
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
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">关键字</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="keyword" placeholder="标题关键字">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件来源</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="source"  class="form-control" onchange="select_change()">
                                    <option value="-1">全部</option>
                                    <option value="0">呼叫中心</option>
                                    <option value="1">网格员</option>
                                    <option value="2">微信用户</option>

                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件日期</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control" id="event_date" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">组织机构</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="event_org"  class="form-control" onchange="">
                                    <option value="0">选择机构</option>
                                    @foreach($org_list as $org)
                                        <option value="{{$org->id}}">{{$org->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件状态</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="status"  class="form-control" onchange="select_change()">
                                    <option value="-1">全部</option>
                                    <option value="0">待研判</option>
                                    <option value="1">待办理</option>
                                    <option value="2">办理中</option>
                                    <option value="3">办结待审核</option>
                                    <option value="4">已办结</option>
                                    <option value="5">已挂起</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">一级分类</label>
                            </div>
                            <div class="col-sm-9">
                                <select id="category_1"  class="form-control" onchange="category_1_change()">
                                    <option value="0">选择一级分类</option>
                                    @foreach($category_1 as $category)
                                        <option value="{{$category->code}}">{{$category->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-3 right-align">
                                    <label for="name" class="margin-top-10">二级分类</label>
                                </div>
                                <div class="col-sm-9">
                                    <select id="category_2"  class="form-control" onchange="category_2_change()">
                                        <option value="0">选择二级分类</option>

                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-sm-3 right-align">
                                    <label for="name" class="margin-top-10">三级分类</label>
                                </div>
                                <div class="col-sm-9">
                                    <select id="category_3"  class="form-control" onchange="">
                                        <option value="0">选择三级分类</option>

                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row margin-top-10 margin-top-10 margin-bottom-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-3">
                                <button id="btnQuery" type="button" class="btn btn-success" onclick="select_change()">查询</button>
                            </div>
                            <div class="col-sm-6">
                                {{--<p class="help-block">查看全部比价结果，请直接点击查询。</p>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>

                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>事件标题</th>
                        <th>事件地址</th>
                        <th>事件发生时间</th>
                        <th>事件状态</th>
                        <th>最后处理人</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    {{--<script src="assets/js/datetime/bootstrap-datepicker.js"></script>--}}

    <script src="admin/event_query.js"></script>


    <script type="text/javascript">
        function category_1_change(){
            var code = $('#category_1').val();
            $.ajax({
                type: "get",
                url: '/common_category',
                data: {
                    level:2,
                    code:code
                },
                success: function (data) {
                    if (data.code==0) {
                        var list = data.result;
                        $('#category_2 option').remove();
                        $('#category_2').append('<option value="0">选择二级分类</option>');
                        list.forEach(function(item){
                            $('#category_2').append("<option value='"+item.code+"'>"+item.name+"</option>");
                        })


                    } else {
                        Notify('获取事件类型失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }

        function category_2_change(){
            var code = $('#category_2').val();
            $.ajax({
                type: "get",
                url: '/common_category',
                data: {
                    level:3,
                    code:code
                },
                success: function (data) {
                    if (data.code==0) {
                        var list = data.result;
                        $('#category_3 option').remove();
                        $('#category_3').append('<option value="0">选择三级分类</option>');
                        list.forEach(function(item){
                            $('#category_3').append("<option value='"+item.code+"'>"+item.name+"</option>");
                        })


                    } else {
                        Notify('获取事件类型失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });

        }
    </script>
@endsection