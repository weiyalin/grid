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
        "sAjaxSource": '/gps_grid_manage_data',
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
            {"data": "short_name"},
            {"data": "parent_name"},
            {'data': "manager_name"},
            {'data': "grid_number"},

            {"data": "id", "bSortable": false}
            //{"data": ""}
        ],
        //"aaSorting": [[4, 'asc']],
        "fnServerData": function (sSource, aoData, fnCallback) {

            if(isAdvanceSearch){    //是否是高级搜索
                aoData['short_name'] = $('#short_name').val();
                aoData['level'] = $('#level').val();
                aoData['manager_name'] = $("#manager_name").find("option:selected").text();
                aoData['manager_id'] = $("#manager_name").val();
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
            $("input[type=search]").attr("placeholder", "输入名字");
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                var buttonsHtml =        //按钮的html拼接
                    "" +
                        //"<a class='btn btn-info btn-xs info_detail' v='" + aData.id + "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                    "<a class='btn btn-warning btn-xs info_edit' href='/gps_grid_manage_page?grid_id="+aData.id+ "'><i class='fa fa-edit'></i>编辑</a>&nbsp;" +
                    "<a class='click_delete btn btn-danger btn-xs info_delete' v='" + aData.id +"' n='"+aData.grid_number+"'><i class='fa fa-trash-o'></i> 删除 </a>"
                $(nRow).find("td:last").html(buttonsHtml);
            }
            return nRow;
        }
    });
}
//删除网格
$(document).on('click','.info_delete',function(){

    var grid_id = $(this).attr('v');

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
                    type: 'post',
                    url: "/gps_grid_manage_del",
                    dataType: "json",
                    data: {grid_id:grid_id},
                    success: function (data) {
                        if(data.code==0){
                            Notify(data.result, 'top-right', '3000', "success", 'fa-edit');
                            setTimeout(function(){
                                location.reload();
                            },3000)
                        }else{
                            Notify(data.result, 'top-right', '3000', "warning", 'fa-edit');
                        }
                    }
                });
            } else {

            }
        },
        title: "确认信息"
    });


})
