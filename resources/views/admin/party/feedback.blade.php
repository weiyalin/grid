@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .panel-body table td{padding:2px 5px;}
        .panel-body table td{width: 25%;}
        .input-group,.toolbar select{width: 100%;}
        #birth_date{padding: 10px;}
        #collapseOne select{height: 33px;}
        div.modal-dialog{width:700px;}
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
                                                <span class="input-group-addon">负责人</span>
                                                <input id="manager" type='text' maxlength="20" class="form-control" name="manager"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">负责人电话</span>
                                                <input id="manager_phone" type='text' maxlength="20" class="form-control" name="manager_phone"/>
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
                <a href="/party_feedback_set" class="btn btn-default" id="btn_add" type="button">
                    <i class="fa fa-plus-square"></i> 新增
                </a>
            </div>

            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>标题</th>
                        <th>负责人</th>
                        <th>负责人联系方式</th>
                        <th>处理时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <div id="myModal" style="display:none; width: 800px;">
        <div class="row">
            <div class="col-md-12">
                <div id="a1"></div>


            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/bootbox/bootbox.js"></script>
    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script type="text/javascript" src="libs/ckplayer6.8/ckplayer/ckplayer.js"></script>
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
                "sAjaxSource": '/party_feedback_data',
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
                    {"data": "title"},
                    {"data": "manager"},
                    {"data": "manager_phone"},
                    {"data": "record_time"},

                    {"data": "id", "bSortable": false}
                    //{"data": ""}
                ],
                //"aaSorting": [[4, 'asc']],
                "fnServerData": function (sSource, aoData, fnCallback) {

                    if(isAdvanceSearch){    //是否是高级搜索
//                        aoData['address'] = $.trim($('#address').val());
//                        aoData['phone'] = $.trim($('#phone').val());
                        aoData['manager'] = $.trim($('#manager').val());
                        aoData['manager_phone'] = $.trim($('#manager_phone').val());
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
                    $("input[type=search]").attr("placeholder", "输入标题");
                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $(nRow).find("td:eq(0)").attr("data-id", aData.id);
                    if ($(nRow).find(".btn-info").length == 0) {
                        var buttonsHtml =        //按钮的html拼接
                                "" +
                                "<a class='btn btn-primary btn-xs info_detail' href='javascript:void(0)' onclick='dialog()'><i class='fa fa-list'></i>实时</a>&nbsp;" +
                                "<a class='btn btn-info btn-xs info_detail' href='/party_feedback_set?type=see&feedback_id="+aData.id+ "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                                "<a class='btn btn-warning btn-xs info_edit' href='/party_feedback_set?type=edit&feedback_id="+aData.id+ "'><i class='fa fa-edit'></i>编辑</a>&nbsp;"+
                                "<a class='feedback_delete btn btn-danger btn-xs' v='" + aData.id +"'><i class='fa fa-trash-o'></i> 删除 </a>";
                        $(nRow).find("td:last").html(buttonsHtml);
                    }
                    return nRow;
                }
            });
            $(document).on('click','.feedback_delete',function(){
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
                                url: '/party_feedback_del',
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
        }

        function dialog(){
            //加载视频
            var flashvars={
                f:'assets/video/m050.mp4',
                c:0,
                b:1,
                // i:'http://www.ckplayer.com/static/images/cqdw.jpg'
            };
            var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
            CKobject.embedSWF('libs/ckplayer6.8/ckplayer/ckplayer.swf','a1','ckplayer_a1','600','400',flashvars,params);
            /*
             CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
             下面三行是调用html5播放器用到的
             */
            var video=['http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4->video/mp4'];
            var support=['iPad','iPhone','ios','android+false','msie10+false'];
            CKobject.embedHTML5('a1','ckplayer_a1',600,500,video,flashvars,support);

            bootbox.dialog({
                message: $("#myModal").html(),
                title: "矛盾调解实时画面",
                className: "modal-darkorange",
                buttons: {
                    success: {
                        label: "关闭",
                        className: "btn-blue",
                        callback: function () {
                            console.log('关闭');
                        }
                    },
//                    "关闭": {
//                        className: "btn-danger",
//                        callback: function () { }
//                    }
                }
            });
        }

    </script>
@endsection