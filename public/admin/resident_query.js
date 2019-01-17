/**
 * Created by wangzhiyuan on 15/12/10.
 */
$(function () {

    $('#birthday').dateRangePicker({
            //batchMode:'week',
            showShortcuts:true,
            shortcuts:{
                'prev':['week'],
                'next':['week']
            },
            showWeekNumbers: true,
            startOfWeek: 'monday',
            separator: ' -- '
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
    GetData(true);
}

var resultDataTable = null;
var isAdvanceSearch = false;
function GetData(isAdvanceSearch) {

    var pageKey = $('#where').data('name'); //表示这个页面的基本搜索条件，也可以表示现在位于哪个页面

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
        "sAjaxSource": '/data_population_resident_list',
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
            {"data": "card_code"},
            {"data": "birthday"},
            {'data': "contact_phone"},
            {'data': "contact_address"},

            {"data": "id", "bSortable": false}
            //{"data": ""}
        ],
        //"aaSorting": [[4, 'asc']],
        "fnServerData": function (sSource, aoData, fnCallback) {

            // !!! 以此标识符判断来源页面，作为基本的搜索条件!!!
            aoData['basic_condition'] = pageKey;    //pageKey的定义放在函数顶部

            if(isAdvanceSearch){    //是否是高级搜索
                aoData['aSearch'] = true;
                aoData['name'] = $('#name').val();
                aoData['card_code'] = $('#card_code').val();
                aoData['contact_phone'] = $('#contact_phone').val();
                aoData['birthday'] = $('#birthday').val();
                aoData['grid_1'] = $('#grid_1').val();
                aoData['grid_2'] = $('#grid_2').val();
                aoData['grid_3'] = $('#grid_3').val();
                aoData['grid_4'] = $('#grid_4').val();
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
            $("input[type=search]").attr("placeholder", "输入姓名");
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).find("td:eq(0)").attr("data-id", aData.id);
            if ($(nRow).find(".btn-info").length == 0) {
                //回收站的按钮和其他页面有不同
                if(pageKey == 'status'){
                    var buttonsHtml =        //按钮的html拼接
                        "" +
                        "<a class='btn btn-info btn-xs info_detail' v='" + aData.id + "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                        "<a class='btn btn-warning btn-xs info_restore' n='"+aData.name+"' v='" + aData.id + "'><i class='fa fa-edit'></i>还原</a>&nbsp;" +
                        "<a class='btn btn-danger btn-xs info_force_delete' v='" + aData.id + "' n='"+aData.name+"'><i class='fa fa-trash-o'></i> 彻底删除 </a>"
                }else{
                    var buttonsHtml =        //按钮的html拼接
                        "" +
                        "<a class='btn btn-info btn-xs info_detail' v='" + aData.id + "'><i class='fa fa-list'></i>详情</a>&nbsp;" +
                        "<a class='btn btn-warning btn-xs info_edit' href='/data_population_edit?id="+aData.id+"&from="+location.pathname+"' v='" + aData.id + "'><i class='fa fa-edit'></i>编辑</a>&nbsp;" +
                        "<a class='click_delete btn btn-danger btn-xs info_delete' v='" + aData.id + "' n='"+aData.name+"'><i class='fa fa-trash-o'></i> 删除 </a>"
                }
                $(nRow).find("td:last").html(buttonsHtml);
            }
            bind(nRow);
            return nRow;
        }
    });
}

function bind(rowObj) {
        //删除
    $(rowObj).find(".info_delete").on("click",function () {
        var del_btn = $(this);
        var id = del_btn.attr("v");
        var name = del_btn.attr('n');
        var data = {id:id};
        bootboxConfirm('/data_population_delete',data,'确定删除',name);
    });

    //人口详细信息弹出页
    $(rowObj).find('.info_detail').click(function(){
        template.config('openTag','<{');
        template.config('closeTag','}>');

        var id = $(this).attr('v');
        $.ajax({
            url     : '/data_population_detail',
            data    : {id:id},
            dataType: 'json',
            success : function(data){
                var html = template('myModal',data);
                bootbox.dialog({
                    message : html,
                })
                $('.modal-dialog').css('width','1000');
            }
        })
    })

    //回收站部分的 1还原/2彻底删除  按钮
        // 1 还原
    $(rowObj).find('.info_restore').click(function(){
        var btn = $(this);
        var id = btn.attr('v');
        var name = btn.attr('n');
        var data = {id:id,type:1};
        bootboxConfirm('/data_population_recycle_bin_action',data,'确定还原',name);
    })
        //彻底删除
    $(rowObj).find('.info_force_delete').click(function(){
        var btn = $(this);
        var id = btn.attr('v');
        var name = btn.attr('n');
        var data = {id:id,type:2};
        bootboxConfirm('/data_population_recycle_bin_action',data,'确定彻底删除',name);
    })
}

function bootboxConfirm(url,data,message,extra){
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
        message: message+'  '+extra+'  ?',
        callback: function (result) {
            if (result) {
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        if (data.code==0) {
                            Notify('删除成功', 'top-right', '3000', 'success', 'fa-check', true);
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
}

//到处excel
function exportExcel(){
    var _token = $('meta[name=_token]').attr('content');
    // 1. 搜集数据过滤条件
    var pageKey = $('#where').data('name');
    // 1.1高级搜索部分
    var name = $('#name').val();
    var card_code = $('#card_code').val();
    var birthday = $('#birthday').val();
    var grid_1 = $('#grid_1').val();
    var grid_2 = $('#grid_2').val();
    var grid_3 = $('#grid_3').val();
    var grid_4 = $('#grid_4').val();
    var formHtml = ""+
        "<form style='display:none' id='exportExcel_form' method='post' action='/data_population_export_excel'>" +
        "<input type='hidden' name='_token' value='"+_token+"'/>"+
        "<input type='hidden' name='pageKey' value='"+pageKey+"'/>"+
        "<input type='hidden' name='pageName' value='"+$('.widget-caption').html()+"'/>"+
        "<input type='hidden' name='name' value='"+name+"'/>"+
        "<input type='hidden' name='card_code' value='"+card_code+"'/>"+
        "<input type='hidden' name='birthday' value='"+birthday+"'/>"+
        "<input type='hidden' name='grid_1' value='"+grid_1+"'/>"+
        "<input type='hidden' name='grid_2' value='"+grid_2+"'/>"+
        "<input type='hidden' name='grid_3' value='"+grid_3+"'/>"+
        "<input type='hidden' name='grid_4' value='"+grid_4+"'/>"+
        "</form>";
    $('body').append(formHtml);
    var form = $('#exportExcel_form');
    form.submit();
}