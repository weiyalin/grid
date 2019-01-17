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
                                    <tbody><tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">二级网格</span>
                                                <select id="level_two" name="level_two">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">三级网格</span>
                                                <select id="level_there" name="level_there">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">四级网格</span>
                                                <select id="level_four" name="level_four">
                                                    <option value=''>全部</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">婚姻类型</span>
                                                <select id="merry_type" name="merry_type">
                                                    <option value=''>全部</option>
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
                                        </td>
                                    </tr><tr>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">孕育说明</span>
                                                <input id="gravidity_type" type='text' class="form-control" name="gravidity_type"/>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">待产时间</span>
                                                <input type="text" class="form-control event_date" name="birth_date" id="birth_date" />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">子女数量</span>
                                                <input id="children" type='number' min="0" class="form-control" name="children"/>
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
                <a href="/data_bear_add" class="btn btn-default" id="btn_add" type="button">
                    <i class="fa fa-plus-square"></i> 新增
                </a>
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>男方姓名</th>
                        <th>男方年龄</th>
                        <th>女方姓名</th>
                        <th>女方年龄</th>
                        <th>婚姻状况</th>
                        <th>结婚时间</th>
                        <th>孕育说明</th>
                        <th>待产时间</th>
                        <th>子女数量</th>
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
                "sAjaxSource": '/data_bear_list',
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
                    {"data": "man_name"},
                    {"data": "man_age"},
                    {"data": "woman_name"},
                    {"data": "woman_age"},
                    {"data": "merry_type"},
                    {"data": "merry_date"},
                    {"data": "gravidity_type"},
                    {"data": "birth_date"},
                    {"data": "children"},

                    {"data": "id", "bSortable": false}
                    //{"data": ""}
                ],
                //"aaSorting": [[4, 'asc']],
                "fnServerData": function (sSource, aoData, fnCallback) {

                    if(isAdvanceSearch){    //是否是高级搜索
                        aoData['level_two'] = $.trim($('#level_two').val());
                        aoData['level_there'] = $.trim($('#level_two').val());
                        aoData['level_four'] = $.trim($('#level_four').val());
                        aoData['merry_type'] = $.trim($('#merry_type').val());
                        aoData['gravidity_type'] = $.trim($('#gravidity_type').val());
                        aoData['birth_date'] = $.trim($('#birth_date').val());
                        aoData['children'] = $.trim($('#children').val());
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
                    $("input[type=search]").attr("placeholder", "输入女方姓名");
                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $(nRow).find("td:eq(0)").attr("data-id", aData.id);
                    if ($(nRow).find(".btn-info").length == 0) {
                        var buttonsHtml =        //按钮的html拼接
                                "" +
                                    //"<a class='btn btn-info btn-xs info_detail' v='" + aData.id + "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                                "<a class='btn btn-warning btn-xs info_edit' href='/data_bear_add?bear_id="+aData.id+ "'><i class='fa fa-edit'></i>编辑</a>&nbsp;";
//                                "<a class='click_delete btn btn-danger btn-xs info_delete' v='" + aData.id +"'><i class='fa fa-trash-o'></i> 删除 </a>"
                        $(nRow).find("td:last").html(buttonsHtml);
                }
                    return nRow;
                }
            });
        }


        $("#level_two").change(function(){
            var level = $(this).val();
            $("#level_there").find("option[value!='']").remove();
            $("#level_four").find("option[value!='']").remove();
            ajax_post('data_bear_get_grid',level,'level_there');
        })
        $("#level_there").change(function(){
            var level = $(this).val();
            $("#level_four").find("option[value!='']").remove();
            ajax_post('data_bear_get_grid',level,'level_four');
        })
        $("#level_four").change(function(){

        })
        ajax_post('data_bear_get_grid',1,'level_two');

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

        /**
         * 获取下拉信息
         * @param url 请求地址
         * @param post_data 参数
         */
        function ajax_post(url,post_data,obj){
            if(!post_data){
                return;
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

        dateRangePicker("#birth_date");
        //daterangpicker设置
        function dateRangePicker(id){
            $(id).dateRangePicker({
                showShortcuts   : true,
                shortcuts       : {
                    'prev': ['month']
                },
                showWeekNumbers : true,
                startOfWeek     : 'monday',
                separator       : '--',
            })
        }

        /*$(document).on('click','.click_delete',function(){
            var label_id = $.trim($(this).attr('v'));
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
                            url:'/data_label_del',
                            type: 'post',
                            dataType: "json",
                            data: {id:label_id},
                            success: function (data) {
                                if(data.code == 0){
                                    Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag');
                                    location.href = '/data_label';
                                }else{
                                    Notify(data.msg, 'top-right', '5000', 'warning', 'fa-warning');
                                }
                            }
                        })
                    } else {}
                },
                title: "确认信息"
            });
        })*/
    </script>
@endsection