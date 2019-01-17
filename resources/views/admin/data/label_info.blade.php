@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .panel-body table td{padding:2px 5px;}
        label{padding:0 -15px;line-height: 34px;}
        .error{
            display: none;
            color: red;
        }
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

            <div class="row">
                <div class="btn-group margin-left-30">
                    <a class="btn btn-default btn-add" href="/data_label">
                        <i class="fa fa-chevron-left"></i> 返回
                    </a>
                    <button type="button" class="save_info btn btn-default btn-save">
                        <i class="fa fa-save"></i> 保存 <!--通过jquery.validate提交验证-->
                    </button>
                    <button type="button" class="del_label btn btn-default btn-save">
                        <i class="fa fa-trash-o"></i> 删除 <!--通过jquery.validate提交验证-->
                    </button>
                </div>
            </div>

            <div class="row margin-top-30 margin-bottom-20">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label text-align-right">标签名称<span style="color:#f00">*</span></label>
                    <div class="col-sm-5">
                        <input value="{{ $label_info->name or null }}" type="text" name="name" class="form-control" id="name" placeholder="输入标签名称"/>
                    </div>
                    <label id="name-error" class="error" for="name">请输入标签名称</label>
                </div>
            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">人员信息</span>
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
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>姓名</th>
                        <th>性别</th>
                        <th>证件号</th>
                        <th>联系电话</th>
                        <th>联系地址</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <input type="hidden" value="{{ $label_id }}" id="label_id" />

@endsection

@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/bootbox/bootbox.js"></script>
    <script>

        var label_id = $("#label_id").val();

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
                "sAjaxSource": '/data_label_use_info',
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
                    {"data": "sex"},
                    {"data": "card_code"},
                    {"data": "contact_phone"},
                    {"data": "contact_address"},


                    {"data": "id", "bSortable": false}
                ],
                //"aaSorting": [[4, 'asc']],
                "fnServerData": function (sSource, aoData, fnCallback) {

                    aoData['id'] = label_id;

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
                    $("input[type=search]").attr("placeholder", "输入人名关键字");
                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $(nRow).find("td:eq(0)").attr("data-id", aData.id);
                    if ($(nRow).find(".btn-info").length == 0) {
                        var buttonsHtml =        //按钮的html拼接
                                "" +
                                "<a class='label_use_delete btn btn-danger btn-xs info_delete' u='" + aData.id +"'><i class='fa fa-trash-o'></i> 取消使用 </a>"
                        $(nRow).find("td:last").html(buttonsHtml);
                    }
                    return nRow;
                }
            });
        }
        $(".del_label").click(function(){
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
        $(".save_info").click(function(){
            var label_name = $.trim($("#name").val());
            if(label_name==''){
                $("#name-error").show();
                return;
            }
            $.ajax({
                url:'/data_label_save',
                type: 'post',
                dataType: "json",
                data: {id:label_id,name:label_name},
                success: function (data) {
                    if(data.code == 0){
                        Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag');
                    }else{
                        Notify(data.msg, 'top-right', '5000', 'warning', 'fa-warning');
                    }
                }
            })
        })
        $(document).on('click',".label_use_delete",function(){
            var population = $.trim($(this).attr('u'));
            $.ajax({
                url:'/data_label_use_del',
                type: 'post',
                dataType: "json",
                data: {label_id:label_id,population:population},
                success: function (data) {
                    if(data.code == 0){
                        Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag');
                        GetData(false);
                    }else{
                        Notify(data.msg, 'top-right', '5000', 'warning', 'fa-warning');
                    }
                }
            })
        })
    </script>
@endsection