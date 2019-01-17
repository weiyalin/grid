/**
 * Created by Administrator on 2016/6/8 0008.
 */
/**
 * Created by wangzhiyuan on 15/12/10.
 */
$(function () {
    GetData();
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
        //"bSearchable": false,
        //"bFilter": true,
        //"seraching" : true,
        "ordering" : false,
        "bSort": true,
        "bProcessing": true,
        'bStateSave': true,
        "bServerSide": true,
        "sAjaxSource": '/sys_auth_manage_rolelist',
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
            {"data": "name"},
            {"data": "desc"},
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
            $("input[type=search]").attr("placeholder", "输入角色名称关键字");
           // bind();
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) { // 当创建了行，但还未绘制到屏幕上的时候调用，通常用于改变行的class风格
            //alert(nRow)
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                $(nRow).find("td:last").html("" +
                    "<a onclick='auth_edit(this,event)'  class='btn btn-info btn-xs' v='" + aData.id + "'><i class='glyphicon glyphicon-ok'></i> 权限管理 </a>&nbsp;" +
                    "<a onclick='role_eidt(this,event)' class='btn btn-info btn-xs' v='" + aData.id + "'><i class='fa fa-edit'></i> 编辑 </a>&nbsp;" +
                    "<a onclick='role_del(this,event)' class='click_delete btn btn-danger btn-xs info_delete' id='info_delete' v='" + aData.id + "'><i class='fa fa-trash-o'></i> 删除 </a>"
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

//权限编辑按钮
function auth_edit(obj,event){
	event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
	var id = $(obj).attr('v');
	var url = $('.btn-auth').attr('data');
	location.href=url+'?id='+id;
}
//编辑角色按钮
function role_eidt(obj, event) {
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');
    var url = $('.btn-edit').attr('data');
    window.location.href = url + "?id="+id;
}
//删除角色按钮
function role_del(obj,event){
    event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
    var id = $(obj).attr('v');
    var url = $('.btn-del').attr('data');

    bootConfirm('确认删除此角色吗？',function(){
        $.ajax({
            url     : url,
            dataType: 'json',
            data    : {id:[id]},    //写成二维，是为了兼容（接下来的）批量删除
            success : function(data){
                if(data.code == 0){
                    location.reload();  //成功直接刷新页面
                }else{
                    Notify('删除失败','top-right','4000','warning','fa-warning',true);
                    //bootMessage('danger','删除失败');
                }
            },
            error   : function(){
                alert('something wrong')
            }

        })
    })
}
//批量删除按钮
$('.btn-del').click(function(){
    var selectedTrs = $('.tr-selected');    //被选中的行
    var len = selectedTrs.length;           //被选中的行数
    if(len == 0){
        Notify('您没有选中任何项目', 'top-right', '5000', 'warning', 'fa-warning', true);
        return;
    }else{
        bootConfirm('确认删除选中的角色吗？',function(){

            var url = $('.btn-del').attr('data');   //删除操作的链接

            //获取选中的行的ID
            var ids = {};       //存放被选中的id(json格式)
            for(var i=0; i<len; i++){
                id = $(selectedTrs[i]).children('td:first-child').attr('data-id');
                ids[i] = id;
            }
            $.ajax({
                url     : url,
                dataType: 'json',
                data    : {id:ids},
                success : function(data){
                    if(data.code == 0){
                        location.reload();  //成功直接刷新页面
                    }else{
                        Notify('删除失败','top-right','4000','warning','fa-warning',true);
                        //bootMessage('danger','删除失败');
                    }
                },
                error   : function(){
                    alert('something wrong')
                }

            })
        })
    }
})
