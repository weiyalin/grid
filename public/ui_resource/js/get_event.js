$(function () {
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
        "sAjaxSource": '/job_get_event_list',
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
            {"data": 'last_process_time'},
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
                $(nRow).find("td:last").html("" +
                        //"<a onclick='org_detail(this,event)'  class='btn btn-info btn-xs' v='" + aData.id + "'><i class='glyphicon glyphicon-ok'></i> 详细信息 </a>&nbsp;" +
                    "<a href='/job_pre_event_view?type=2&id="+aData.id+"' class='btn btn-info btn-xs'><i class='fa fa-list'> </i> 详 情 </a> &nbsp;"
                    //" <a onclick='event_down(this,event)' class='click_delete btn btn-primary btn-xs info_delete' v='" + aData.id + "'><i class='fa fa-thumb-tack'> </i> 办结 </a> &nbsp;"+
                    //" <a onclick='event_back_to_pre(this)' class='click_delete btn btn-danger btn-xs info_delete' v='" + aData.id + "'> <i class='fa fa-share'></i> 退回 </a>"
                );
            }
            //bind(nRow); //单独为每一行添加点击事件，整体一块添加有时会失效
            return nRow;
        }
    });
});

//办结按钮click事件
function event_down(obj,event){
    var id = $(obj).attr('v');
    bootbox.dialog({
        message : $('#event_down_box').html(),
        title   : '输入办理结果',
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
                        Notify('请输入事件办理结果后再提交', 'top-right', '5000', 'warning', 'fa-tag', true);
                        return false;
                    }
                    $.ajax({
                        url     : '/job_get_event_down',
                        dataType: 'json',
                        data    :{id:id,content:' '+' \n事件办结：'+content},
                        success : function(data){
                            if(data.code == 0){   //成功
                                Notify(data.msg, 'top-right', '5000', 'info', 'fa-tag', true);
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

//退回事件到'待办事件'
/*function event_back_to_pre(obj){
    var id = $(obj).attr('v');
    bootbox.dialog({
        message : $('#event_down_box').html(),
        title   : '退回原因',
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
                        url     : '/job_get_event_back_to_pre',
                        dataType: 'json',
                        data    :{id:id,content:content},
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
}*/
