/**
 * Created by wangzhiyuan on 15/12/10.
 */
$(function () {

    GetData();
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        $(".caret").remove();
    }
});


function select_change(){
    //查询
    resultDataTable.fnFilter('',0)
}

var resultDataTable = null;
function GetData() {

    var $searchResult = $("#simpledatatable");

    if (resultDataTable) {
        resultDataTable.fnClearTable();
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
        "sAjaxSource": '/event_already_determine_list',
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
            {"data": "address"},
            {"data": "create_time"},
            {'data': "status"},
            {'data': "last_process_format"},

            {"data": "id", "bSortable": false}
            //{"data": ""}
        ],
        //"aaSorting": [[4, 'asc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            //console.log('aoData:');
            //console.log(aoData);
            aoData['status']=1;
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
            $("input[type=search]").attr("placeholder", "输入标题关键字");
            bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("" +
                    "<a class='btn btn-info btn-xs' id='info_edit' v='" + aData.id + "'><i class='fa fa-edit'></i> 处理 </a>&nbsp;" +
                    "<a class='click_delete btn btn-danger btn-xs info_delete' id='info_delete' v='" + aData.id + "'><i class='fa fa-trash-o'></i> 删除 </a>"
                );
            }
            return nRow;
            //添加双击事件
            //$(nRow).dblclick(function(){
            //    "use strict";
            //    alert("ddddd");
            //})

        }
    });
}
//var unique_id;
function bind() {

    $("#simpledatatable tbody").on("click", "tr #info_edit", function () {
        //alert('正在开发中...');
        var id = $(this).attr('v');
        location.href='/event_detail?type=1&id='+id;
    });

        //删除
    $("#simpledatatable tbody").on("click", "tr #info_delete", function () {
        var del_btn = $(this);
        var id = del_btn.attr("v");
        //var url = $("#deladdr").attr("value");
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
            message: '确定要删除吗？',
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "post",
                        url: '/event_delete',
                        data: {id: id},
                        success: function (data) {
                            if (data.code==0) {

                                Notify('删除成功', 'top-right', '3000', 'success', 'fa-check', true);
                                //del_btn.parent().parent().remove();
                                GetData();
                            } else {
                                Notify('删除失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                            }
                        }
                    });
                } else {

                }
            },
            title: "确认信息"
        });
    });


}




