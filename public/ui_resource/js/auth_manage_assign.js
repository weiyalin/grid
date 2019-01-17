$(function () {
    var setting = {     //zTree配置信息
        check : {
            enable      : true,
            chkStyle    : 'checkbox',
            expandSpeed : 'fast',
            chkboxType  : {'Y':'ps','N':'ps'}
        },
        view : {
            showIcon    : false,
            dbClickExpand : false,
        },
        data : {    //必须使用data
            simpleData : {
                enable  : true,
                idKey   : 'id',
                pIdKey  : 'pId',
                rootId  : 0
            }
        },
        async : {
            enable : true,
            dataType : 'json',
            type : 'get',
            url : '/sys_auth_manage_authlist',
        },
        callback: {
            onAsyncSuccess: function () {
                /**
                 * 勾选已有的权限（id)
                 */
                var oldAuthNodeIds = $('input[name=oldAuthNodeIds]').val();   //已经获取的权限（测试数据）
                oldAuthNodeIds = eval('('+oldAuthNodeIds+')');              //字符串-》json
                $.each(oldAuthNodeIds,function(i,item){
                    var nodeObj = zTreeObj.getNodeByParam('id',item);   //根据ID获取节点对象
                    zTreeObj.checkNode(nodeObj,true)                    //勾选节点对象
                })
            }
        },
    };

    /**
     * 根据setting值渲染zTree
     */
    var zTreeObj = $.fn.zTree.init($('#authList'),setting);

    /**
     * 保存按钮 -- click事件
     */
    $('.btn-save').click(function(){
        var nodes = zTreeObj.getCheckedNodes(true);  //或 getSelectedNodes();获取被选择的节点
        var ids = [];
        $.each(nodes,function(i,item){
            ids.push(item.id);  //把id放入数组
        })

        if(ids.length == 0){    //如果ids长度为0
            bootConfirm("您没有选择任何权限，确认保存？",function(){
                addPages(ids);
            })
        }else{
            addPages(ids);  //发送ids和role_id
        }
    })
})
//保存权限
function addPages(ids) {
    $(".loading-container").show();
    $('.loading-progress').show();  //显示加载中动画
    $.post(
        $(".btn-save").attr("data"),
        {ids: ids.join(","), role_id: $("input[name=role_id]").val()},
        function (d) {
            $(".loading-container").hide(); //隐藏加载中动画
            if (d.code == 0) {
                Notify(d.msg,'top-right','4000','success','fa-check',true);
                location.href='/sys_auth_manage';
            } else {
                Notify(d.msg,'top-right','4000','warning','fa-warning',true);
                //bootMessage("danger", d.msg);
            }
        },
        "json");
}