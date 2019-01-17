/**
 * Created by Administrator on 2016/6/25 0025.
 */

resultDataTable = null;
$(function () {
    var $searchResult = $("#simpledatatable");

    if (resultDataTable) {
        resultDataTable.fnClearTable();
        $searchResult.dataTable().fnDestroy();
        $("#simpledatatable tbody").empty();
        $('ul.toggle-table-columns').empty();
    } else {
        $searchResult.show();
    }

    resultDataTable = $("#simpledatatable").dataTable({
        "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
        "iDisplayLength": 10,
        "bAutoWidth": false,
        //"bSearchable": false,
        //"bFilter": true,
        "seraching" : true,
        "ordering" : false,
        "bSort": true,
        "bProcessing": true,
        'bStateSave': true,
        "bServerSide": true,
        "sAjaxSource": '/job_already_event_list',
        "language": {
            "sProcessing": "正在加载数据...",
            "sZeroRecords": "没有查到相关内容",
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

        "columns": [        //测试结构（不确定）：会发送到后台，可以当做获取字段，同时也是前端将渲染的字段名称
            {"data": "id"},
            {"data": "title"},    //对应数据表中的省份+城市+县区+街道
            {"data": 'address'},
            {"data": 'create_time'},
            {"data": 'source'},
            //{"data": 'get_time'},
            {"data":'event_status'},
            {"data": null},
        ],
        //"aaSorting": [[4, 'asc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData['status']=0;		//0:正常 1:已删除
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
        "fnInitComplete": function () {	//表格初始化完成后调用 在这里和服务器分页没关系可以忽略
            $("input[type=search]").attr("placeholder", "搜索事件标题");
            // bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) { // 当创建了行，但还未绘制到屏幕上的时候调用，通常用于改变行的class风格
            $(nRow).attr('id','item'+aData.id);
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html(
                    "<a href='/job_pre_event_view?type=3&id="+aData.id+"' class='btn btn-info btn-xs'><i class='fa fa-list'></i> 详细</a>&nbsp;"
                );
            }
            //bind(nRow); //单独为每一行添加点击事件，整体一块添加有时会失效
            return nRow;
        }
    });
});