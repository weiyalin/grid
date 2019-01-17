/**
 * Created by Administrator on 2016/6/24 0024.
 */
//定义各种操作的url
var getEventListUrl = '/job_pre_event_list';    //获取组织机构列表
var eventBackUrl    = '/job_pre_event_back';        //退回事件

var resultDataTable = null;
$(function () {
    var searchResult = $("#simpledatatable");

    if (resultDataTable) {
        resultDataTable.fnClearTable();
        searchResult.dataTable().fnDestroy();
        $("#simpledatatable tbody").empty();
        $('ul.toggle-table-columns').empty();
    } else {
        searchResult.show();
    }

    resultDataTable = searchResult.dataTable({
        "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
        "iDisplayLength": 15,
        "bAutoWidth": false,
        "bSearchable": false,
        "bFilter": true,
        "bSort": false,
        "bProcessing": true,
        'bStateSave': true,
        "bServerSide": true,
        "sAjaxSource": getEventListUrl,
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

        "columns": [        //测试结构（不确定）：会发送到后台，可以当做获取字段，同时也是前端将渲染的字段名称
            {"data": "id"},
            {"data": "title"},    //对应数据表中的省份+城市+县区+街道
            {"data": 'address'},
            {"data": 'create_time'},
            {"data": 'source'},
            //{"data": null},
            {"data": "id", "bSortable": false}
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
                }
            });
        },
        "fnInitComplete": function () {	//表格初始化完成后调用 在这里和服务器分页没关系可以忽略
            $("input[type=search]").attr("placeholder", "搜索事件标题");
            // bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) { // 当创建了行，但还未绘制到屏幕上的时候调用，通常用于改变行的class风格
            //$(nRow).attr('id','item'+aData.id);
            //$(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("" +
                    "<a href='/job_pre_event_view?id="+aData.id+"&type=1' class='btn btn-info btn-xs'><i class='fa fa-list'> </i> 详 情 </a> &nbsp;" +
                    "<a onclick='event_get(this,event,"+'"'+aData.title+'"'+")' class='btn btn-primary btn-xs' v='" + aData.id + "'><i class='fa fa-thumb-tack'></i> 领 取 </a>&nbsp;"
                    //"<a onclick='event_back(this)' class='click_delete btn btn-danger btn-xs info_delete' v='" + aData.id + "'><i class='fa fa-reply'></i> 退回 </a>"
                );
            }
            return nRow;
        }
    });
});

//领取按钮事件
function event_get(obj,event,title){
    var id = $(obj).attr('v');
    var url = '/job_pre_event_get';
    bootConfirm('确认领取事件 “'+title+'”',function(){
        $.ajax({
            url     : url,
            dataType: 'json',
            data    : {id:id},
            method  : 'post',
            success : function(data){
                if(data.code){    //失败
                    Notify(data.msg, 'top-right', '5000', 'warning', 'fa-tag', true);
                }else{  //成功
                    Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag', true);
                    location.href='/job_get_event';
                }
            },
            error   : function(){
                alert('网络问题，请重试');
            }
        })
    })
}

//退回事件到研判中心
function event_back(obj){
    var id = $(obj).attr('v');
    var suggest_info = $('#suggest_info').val();
    bootbox.dialog({
        message : $('#event_down_box').html(),
        title   : '输入退回原因',
        className: '',
        buttons : {
            "取消"    : {
                className : 'btn-warning',
                callback : function(){}
            },
            success : {
                label   : '提交',
                className   : 'btn-blue',
                callback    : function(){
                    //alert(id);
                    var content = $.trim($('.bootbox .event_result').val());
                    if(content == ''){
                        Notify('请输入退回原因', 'top-right', '5000', 'warning', 'fa-tag', true);
                        return false;
                    }
                    $.ajax({
                        url     : eventBackUrl,
                        dataType: 'json',
                        data    :{id:id,content:suggest_info+'\n'+content},
                        success : function(data){
                            if(data.code == 0){   //成功
                                Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag', true);
                                location.reload();
                            }else{
                                Notify(data.msg, 'top-right', '5000', 'warning', 'fa-tag', true);
                            }
                        },
                        error   : function(){
                            alert('something wrong，please try again');
                        }
                    })

                }
            }
        }
    })
}