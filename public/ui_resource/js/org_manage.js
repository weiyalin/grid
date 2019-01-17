/**
 * Created by Administrator on 2016/6/13 0013.
 */
//定义各种操作的url
var getOrgListUrl = '/sys_org_manage_list';    //获取组织机构列表
var editOrgUrl = '/sys_org_manage_edit';        //编辑组织机构信息
var delOrgUrl = '/sys_org_manage_delete';         //删除

$(function () {
    resultDataTable = $("#simpledatatable").dataTable({
        "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
        "iDisplayLength": 10,
        "bAutoWidth": false,
        //"bSearchable": false,
        //"bFilter": true,
        //"seraching" : true,
        "ordering" : false,
        "bSort": true,
        "bProcessing": true,
        'bStateSave': true,
        "bServerSide": true,
        "sAjaxSource": getOrgListUrl,
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
            {"data": "name"},
            {"data": "detailAddress"},    //对应数据表中的省份+城市+县区+街道
            {"data": 'contact'},
            {"data": 'type'},
            //{"data": 'parent'},
            {"data": null},
            //{"data": "id", "bSortable": false}
            //{"data": ""}
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
            $("input[type=search]").attr("placeholder", "输入机构名称");
            // bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) { // 当创建了行，但还未绘制到屏幕上的时候调用，通常用于改变行的class风格
            //alert(nRow)
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("" +
                    //"<a onclick='org_detail(this,event)'  class='btn btn-info btn-xs' v='" + aData.id + "'><i class='glyphicon glyphicon-ok'></i> 详细信息 </a>&nbsp;" +
                    "<a onclick='org_edit(this,event)' class='btn btn-info btn-xs' v='" + aData.id + "'><i class='fa fa-edit'></i>详细/编辑</a>&nbsp;" +
                    "<a onclick='org_del(this,event)' class='click_delete btn btn-danger btn-xs info_delete' id='info_delete' v='" + aData.id + "'><i class='fa fa-trash-o'></i> 删除 </a>"
                );
            }
            bind(nRow); //单独为每一行添加点击事件，整体一块添加有时会失效
            return nRow;
        }
    });
});
//w为每一行添加点击事件（点击后变颜色）
function bind(obj) {
    //tr被点击后，添加tr-selected样式
    $(obj).click(function(){
        if($(this).hasClass('tr-selected')){
            $(this).removeClass('tr-selected');
        }else{
            $(this).addClass('tr-selected');
        }
    })
}

function org_edit(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');
    location.href = editOrgUrl+'?id='+id;
}

//单个删除
function org_del(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');
    bootConfirm('确定要删除此项目？',function() {
        $.ajax({
            url: delOrgUrl,
            type: 'get',
            dataType: 'json',
            data: {id: [id]},    //组成二维数组，兼容批量删除
            success: function (data) {
                if(data.code == 0){
                    Notify(data.msg, 'top-right', '5000', 'info', 'fa-tag', true);
                    location.reload();
                }else{
                    Notify(data.msg, 'top-right', '5000', 'warning', 'fa-warning', true);
                }
            },
            error: function () {
                alert('something wrong');
            }
        })
    })
}

//批量删除
$('.btn-del').click(function(){
    var selectedTrs = $('.tr-selected');    //被选中的行
    var len = selectedTrs.length;           //被选中的行数
    if(len == 0) {
        Notify('您没有选中任何项目', 'top-right', '5000', 'warning', 'fa-warning', true);
        return;
    }else{
        //获取选中的行的ID
        var ids = {};       //存放被选中的id(json格式)
        for(var i=0; i<len; i++){
            id = $(selectedTrs[i]).children('td:first-child').attr('data-id');
            ids[i] = id;
        }
        //console.log(ids);
        bootConfirm('确定要删除此项目？',function() {
            $.ajax({
                url: delOrgUrl,
                type: 'get',
                dataType: 'json',
                data:{id:ids},
                success: function (data) {
                    if (data.code == 0) {
                        Notify(data.msg, 'top-right', '8000', 'info', 'fa-tag', true);
                        location.reload();
                    } else {
                        Notify(data.msg, 'top-right', '8000', 'warning', 'fa-warning', true);
                    }
                }
                ,
                error: function () {
                    alert('something wrong');
                }
            })
        })
    }
})