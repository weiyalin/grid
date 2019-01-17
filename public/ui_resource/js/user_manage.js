/**
 * Created by Administrator on 2016/6/15 0015.
 */
var getUserListUrl  = '/sys_user_manage_list';      //获取人员列表
var userEditUrl     = '/sys_user_manage_view';      //编辑
var changeStatusUrl = '/sys_user_manage_status';    //更改状态
var userDelUrl      = '/sys_user_manage_delete';    //删除
var resetPasswordUrl= '/sys_user_manage_reset';     //重置密码

$(function () {
    getTableData(false);

    //高级搜索按钮
    $('#btn-search').click(function(){
        var searchResult = $("#simpledatatable");
        if (resultDataTable != null) {
            searchResult.dataTable().fnDestroy();
            resultDataTable.fnClearTable();
            $("#simpledatatable tbody").empty();
        }
        getTableData(true); //是高级搜索
    })
});
function getTableData(isAdvancedSearch){    //是否是高级搜索
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
        "sAjaxSource": getUserListUrl,
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

        "columns": [        //测试结果：会发送到后台，可以当做获取字段，同时也是前端将渲染的字段名称
            {"data": "name"},
            {"data": "login_name"},
            {"data": 'phone'},
            {"data": 'title'},
            {"data": 'roleName'},
            {"data": 'orgName'},
            {"data": 'status'},
            {"data": null},
            //{"data": "id", "bSortable": false}
            //{"data": ""}
        ],
        //"aaSorting": [[4, 'asc']],
        "fnServerData": function (sSource, aoData, fnCallback) {
            //aoData['status']=0;		//0:正常 1:停用 2：已删除 前台不再指定，后台直接显示0和1
            if(isAdvancedSearch){       //高级搜索
                //aoData['advanced_search'] = $('#advanced_search').serializeArray();
                aoData['advanced_search'] = true;
                aoData['name'] = $('#name').val();
                aoData['login_name'] = $('#login_name').val();
                aoData['title'] = $('#title').val();
                aoData['phone'] = $('#phone').val();
                aoData['role_id'] = $('#role_id').val();
                aoData['org_id'] = $('#org_id').val();
                aoData['status'] = $('#status').val();
                //alert(aoData)
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
        "fnInitComplete": function () {	//表格初始化完成后调用 在这里和服务器分页没关系可以忽略
            $("input[type=search]").attr("placeholder", "请输入用户姓名");
            // bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) { // 当创建了行，但还未绘制到屏幕上的时候调用，通常用于改变行的class风格
            //alert(nRow)
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("" +
                        //"<a onclick='_detail(this,event)'  class='btn btn-info btn-xs' v='" + aData.id + "'><i class='glyphicon glyphicon-ok'></i> 角色 </a>&nbsp;" +
                    "<a onclick='user_edit(this,event)' class='btn btn-info btn-xs' v='" + aData.id + "'><i class='fa fa-edit'></i>编辑</a>&nbsp;&nbsp;" +
                    "<a onclick='reset_password(this,event)'  class='btn btn-warning btn-xs' v='" + aData.id + "' title='点击后，用户密码恢复为默认密码'><i class='fa fa-lock'></i> 重置密码 </a>&nbsp;&nbsp;"+
                    "<a onclick='change_status(this,event)'  class='btn btn-darkorange btn-xs' v='" + aData.id + "' title='禁用/启用该用户'><i class='fa fa-user-md'></i> 禁用/启用 </a>&nbsp;&nbsp;"+
                    "<a onclick='user_del(this,event)' class='click_delete btn btn-danger btn-xs info_delete' id='info_delete' v='" + aData.id + "'><i class='fa fa-trash-o'></i> 删除 </a>&nbsp;"
                );
            }
            bind(nRow); //单独为每一行添加点击事件，整体一块添加有时会失效
            return nRow;
        }
    });
}
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

//编辑
function user_edit(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');
    location.href = userEditUrl+'?id='+id;
}
//重设密码
function reset_password(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
}

//更改状态
function change_status(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');

    bootConfirm('确认改变所选项目的状态？',function(){
        $.ajax({
            url     : changeStatusUrl,
            dataType: 'json',
            data    : {id:[id]},    //兼容批量更改，组成二维数组
            type    : 'get',
            success : function(data){
                if(data.code == 0){
                    Notify(data.msg,'top-right','4000','info','fa-tag',true);
                    location.reload();
                }else{
                    Notify(data.msg,'top-right','4000','warning','fa-warning',true);
                }
            },
            error   : function(){
                alert('something wrong')
            }
        })
    })
}
//批量更改状态
/* 没有成功，先隔过去
$('.btn-change-status').click(function(){
    var selectedTrs = $('.tr-selected');    //被选中的行
    var len = selectedTrs.length;           //被选中的行数
    if(len == 0) {
        Notify('您没有选中任何项目', 'top-left', '5000', 'warning', 'fa-warning', true);
        return;
    }else{
        //获取选中的行的ID
        var ids = {};       //存放被选中的id(json格式)
        for(var i=0; i<len; i++){
            id = $(selectedTrs[i]).children('td:first-child').attr('data-id');
            ids[i] = id;
        }
        //console.log(ids);
        bootConfirm('确定要更改选中项目的状态？',function() {
            $.ajax({
                url: changeStatusUrl,
                type: 'get',
                dataType: 'json',
                data:{id:ids},
                success: function (data) {
                    if (data.code == 0) {
                        Notify(data.msg, 'top-left', '5000', 'info', 'fa-tag', true);
                        location.reload();
                    } else {
                        Notify(data.msg, 'top-left', '5000', 'warning', 'fa-warning', true);
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
*/
//删除
function user_del(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');

    bootConfirm('确认删除此项？',function(){
        $.ajax({
            url     : userDelUrl,
            dataType: 'json',
            data    : {id:[id]},    //兼容批量删除，组成二维数组
            type    : 'get',
            success : function(data){
                if(data.code == 0){
                    Notify(data.msg,'top-right','4000','info','fa-tag',true);
                    location.reload();
                }else{
                    //Notify('删除失败','top-left','4000','success','fa',true);
                    Notify(data.msg,'top-right','4000','warning','fa-warning',true);
                }
            },
            error   : function(){
                alert('something wrong')
            }
        })
    })
}
//批量删除
$('.btn-del').click(function(){
    var selectedTrs = $('.tr-selected');    //被选中的行
    var len = selectedTrs.length;           //被选中的行数
    if(len == 0) {
        Notify('您没有选中任何项目', 'top-left', '8000', 'warning', 'fa-warning', true);
        return;
    }else{
        //获取选中的行的ID
        var ids = {};       //存放被选中的id(json格式)
        for(var i=0; i<len; i++){
            id = $(selectedTrs[i]).children('td:first-child').attr('data-id');
            ids[i] = id;
        }
        //console.log(ids);
        bootConfirm('确定要更改选中项目的状态？',function() {
            $.ajax({
                url: userDelUrl,
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

//重置密码
function reset_password(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }

    var id = $(obj).attr('v');
    bootConfirm('确认要重置密码吗？',function(){
        $.ajax({
            url     : resetPasswordUrl,
            dataType: 'json',
            data    : {id:id},
            type    : 'post',
            success : function(data){
                if (data.code == 0) {
                    Notify(data.msg, 'top-right', '10000', 'info', 'fa-tag', true);
                } else {
                    Notify(data.msg, 'top-right', '10000', 'warning', 'fa-warning', true);
                }
            },
            error   : function(){
                alert('something wrong');
            }
        })
    })
}