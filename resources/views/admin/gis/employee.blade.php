@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .panel-body table td{padding:2px 5px;}
        .panel-body table td{width: 25%;}
        .input-group,.toolbar select{width: 100%;}
        #birth_date{padding: 10px;}
        #collapseOne select{height: 33px;}
    </style>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">{{ $title }}</span>
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

            <div class="toolbar">
                <div id="accordion" class="panel-group accordion" style="margin-bottom: 8px;">
                    <div class="panel panel-default">
                        <div class="panel-heading ">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" href="#collapseOne" data-parent="#accordion" data-toggle="collapse">
                                    <i class="fa fa-search"></i>
                                    高级搜索
                                </a>
                            </h4>
                        </div>

                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">一级网格</span>
                                                <select id="grid_1" name="grid_1">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">二级网格</span>
                                                <select id="grid_2" name="grid_2">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">三级网格</span>
                                                <select id="grid_3" name="grid_3">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">四级网格</span>
                                                <select id="grid_4" name="grid_4">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">工作岗位</span>
                                                <input id="post" type='text' maxlength="20" class="form-control" name="post"/>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">年限</span>
                                                <input id="age_limit_min" type='number' maxlength="11" class="form-control" name="age_limit_min"/>
                                                <span class="input-group-addon">~</span>
                                                <input id="age_limit_max" type='number' maxlength="11" class="form-control" name="age_limit_max"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">联系方式</span>
                                                <input id="mobile" type='text' maxlength="20" class="form-control" name="mobile"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div id="btn-search" class="btn btn-primary">
                                                <i class="fa fa-search"></i>搜索
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="btn-group pull-left" style="z-index:999;">
                <a href="/gps_employee_set" class="btn btn-default" id="btn_add" type="button">
                    <i class="fa fa-plus-square"></i> 新增
                </a>
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>证件号</th>
                        <th>姓名</th>
                        <th>性别</th>
                        <th>工作岗位</th>
                        <th>工作单位</th>
                        <th>工作年限</th>
                        <th>联系方式</th>
                        <th>是否为党员</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

@endsection

@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/bootbox/bootbox.js"></script>
    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script>
        $(function(){
            GetData(false);
            if (navigator.userAgent.indexOf("MSIE") > 0) {
                $(".caret").remove();
            }
        })

        $("#btn-search").click(function(){
            select_change();
        })

        function select_change(){
            GetData(true);
        }

        var resultDataTable = null;
        function GetData(isAdvanceSearch) {

            var $searchResult = $("#simpledatatable");

            if (resultDataTable) {
                resultDataTable.fnClearTable(false);
                $searchResult.dataTable().fnDestroy();
                $("#simpledatatable tbody").empty();
                $('ul.toggle-table-columns').empty();
            } else {
                $searchResult.show();
            }

            resultDataTable = $searchResult.dataTable({
                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                "iDisplayLength": 10,
                "bAutoWidth": false,
                "bSearchable": false,
                "bFilter": true,
                "bSort": false,
                "bProcessing": true,
                'bStateSave': true,
                "bServerSide": true,
                "sAjaxSource": '/gps_employee_data',
                "language": {
                    "sProcessing": "正在加载数据...",
                    "sZeroRecords": "没有您要搜索的内容",
                    "sInfo": "从_START_ 到 _END_ 条记录　总记录数为 _TOTAL_ 条",
                    "sInfoEmpty": "记录数为0",
                    "sInfoFiltered": "(全部记录数 _MAX_  条)",
                    "sInfoPostFix": "",
                    "search": "",
                    "sLengthMenu": "_MENU_",
                    "oPaginate": {
                        "sPrevious": "上一页",
                        "sNext": "下一页"
                    }
                },

                "columns": [
                    {"data": "id"},
                    {"data": "card_code"},
                    {"data": "name"},
                    {"data": "sex"},
                    {"data": "post"},
                    {"data": "unit"},
                    {"data": "age_limit"},
                    {"data": "mobile"},
                    {"data": "is_party"},

                    {"data": "id", "bSortable": false}
                    //{"data": ""}
                ],
                //"aaSorting": [[4, 'asc']],
                "fnServerData": function (sSource, aoData, fnCallback) {

                    if(isAdvanceSearch){    //是否是高级搜索
                        aoData['mobile'] = $.trim($('#mobile').val());
                        aoData['post'] = $.trim($('#post').val());
                        aoData['age_limit_min'] = $.trim($('#age_limit_min').val());
                        aoData['age_limit_max'] = $.trim($('#age_limit_max').val());
                        aoData['grid_1'] = $.trim($('#grid_1').val());
                        aoData['grid_2'] = $.trim($('#grid_2').val());
                        aoData['grid_3'] = $.trim($('#grid_3').val());
                        aoData['grid_4'] = $.trim($('#grid_4').val());
                    }

                    $.ajax({
                        type: 'get',
                        url: sSource,
                        dataType: "json",
                        data: aoData,
                        success: function (resp) {
                            fnCallback(resp);
                            //console.log(resp);
                        }
                    });
                },
                "fnInitComplete": function () {
                    $("input[type=search]").attr("placeholder", "输入职员名称");
                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $(nRow).find("td:eq(0)").attr("data-id", aData.id);
                    if ($(nRow).find(".btn-info").length == 0) {
                        var buttonsHtml =        //按钮的html拼接
                                "" +
                                    //"<a class='btn btn-info btn-xs info_detail' v='" + aData.id + "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                                "<a class='btn btn-warning btn-xs info_edit' href='/gps_employee_set?employee_id="+aData.id+ "'><i class='fa fa-edit'></i>编辑</a>&nbsp;"+
                                "<a class='org_delete btn btn-danger btn-xs' v='" + aData.id +"'><i class='fa fa-trash-o'></i> 删除 </a>";
                        $(nRow).find("td:last").html(buttonsHtml);
                    }
                    return nRow;
                }
            });
        }
        /**
         * 获取下拉信息
         * @param url 请求地址
         * @param post_data 参数
         */
        function ajax_post(url,post_data,obj){
            if(!post_data&&post_data!=0){
                return
            }
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
        }
        //选择网格
        $("#grid_1").change(function(){
            var level = $(this).val();
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
        ajax_post('data_bear_get_grid',0,'grid_1');
        $(document).on('click','.org_delete',function(){
            var obj = $(this).attr('v');
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: '确定',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: '取消',
                        className: 'btn-default'
                    }
                },
                message: '你确定要删除吗？',
                callback: function (result) {
                    if (result) {
                        $.ajax({
                            type: "post",
                            url: '/gps_employee_del',
                            data: {
                                obj:obj,
                            },
                            success: function (data) {
                                if (data.code==0) {
                                    Notify('删除成功', 'top-right', '3000', 'success', 'fa-check');
                                    setTimeout(function(){
                                        select_change();
                                    },1000)
                                }else{
                                    Notify('删除失败，请重试！', 'top-right', '3000', 'danger', 'fa-check');
                                }
                            }
                        });
                    } else {

                    }
                },
                title: "确认信息"
            });
        })
    </script>
@endsection