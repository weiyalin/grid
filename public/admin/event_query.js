/**
 * Created by wangzhiyuan on 15/12/10.
 */
$(function () {

    $('#event_date').dateRangePicker({
            //batchMode:'week',
            showShortcuts:true,
            shortcuts:{
                'prev':['week'],
                'next':['week']
            },
            showWeekNumbers: true,
            startOfWeek: 'monday',
            separator: ' ~ '
        })
        .bind('datepicker-apply',function(event,obj)
        {
            /* This event will be triggered when second date is selected */
            console.log(obj);

            //var start = Date.parse(obj.date1);
            //var end = Date.parse(obj.date2);
            // console.log(end);
            //$('#currentQuery').val(start+","+end);
            //查询数据
            resultDataTable.fnFilter('',0)
        });

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
        "sAjaxSource": '/event_query_list',
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
            aoData['keyword']=$('#keyword').val();
            aoData['source']=$('#source').val();
            var event_date = $('#event_date').val();
            var begin_date = 0;
            var end_date = 0;
            if(event_date){
                var sale_arr = sale_date.split('~');
                begin_date = Date.parse(sale_arr[0]);
                end_date = Date.parse(sale_arr[1]);
            }
            aoData['begin_date']=begin_date;
            aoData['end_date']=end_date;
            aoData['status']=$('#status').val();
            //aoData['category_1']=$('#category_1').val();
            //aoData['category_2']=$('#category_2').val();
            //aoData['category_3']=$('#category_3').val();



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
                    "<a class='btn btn-info btn-xs' id='info_edit' v='" + aData.id + "' event_status='"+aData.event_status+"'><i class='fa fa-edit'></i> 查看详情 </a>&nbsp;" +
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
        var id = $(this).attr('v');
        var status = parseInt($(this).attr('event_status'));
        var type = 0;
        switch(status){//状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
            case 0:
                type=0;
                break;
            case 3:
                type=2;
                break;
            default:
                type=1;
                break;
        }
        location.href='/event_detail?type='+type+'&id='+id;

        return ;
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




