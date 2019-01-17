@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .panel-body table td{padding:2px 5px;}
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

            {{--<div class="btn-group pull-left" style="z-index:999;">--}}
                {{--<a href="javascript:void(0);" class="btn btn-default" id="btn_add" type="button">--}}
                    {{--<i class="fa fa-plus-square"></i> 新增--}}
                {{--</a>--}}
            {{--</div>--}}

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>标签名</th>
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
    <script>
        $(function(){
            GetData(false);
            if (navigator.userAgent.indexOf("MSIE") > 0) {
                $(".caret").remove();
            }
        })

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
                "sAjaxSource": '/data_label_list',
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
                    {"data": "name"},

                    {"data": "id", "bSortable": false}
                    //{"data": ""}
                ],
                //"aaSorting": [[4, 'asc']],
                "fnServerData": function (sSource, aoData, fnCallback) {

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
                    $("input[type=search]").attr("placeholder", "输入标签关键字");
                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $(nRow).find("td:eq(0)").attr("data-id", aData.id);
                    if ($(nRow).find(".btn-info").length == 0) {
                        var buttonsHtml =        //按钮的html拼接
                                "" +
                                    //"<a class='btn btn-info btn-xs info_detail' v='" + aData.id + "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                                "<a class='btn btn-warning btn-xs info_edit' href='/data_label_info?label_id="+aData.id+ "'><i class='fa fa-edit'></i>详情</a>&nbsp;" +
                                "<a class='click_delete btn btn-danger btn-xs info_delete' v='" + aData.id +"'><i class='fa fa-trash-o'></i> 删除 </a>"
                        $(nRow).find("td:last").html(buttonsHtml);
                    }
                    return nRow;
                }
            });
        }
        $(document).on('click','.click_delete',function(){
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
        })
    </script>
@endsection