/**
 * 数据中心：编辑、新增、列表页面选择网格所用的js
 * html格式： select标签的id必须为grid_1、grid_2、grid_3、grid_4
 */
$(function(){
    //网格信息点击事件
    //获取一级网格
    $.ajax({
        url     : '/data_population_next_grid_list',
        data    : {pid:0},  //pid=0为一级网格
        dataType: 'json',
        success : function(data){
            var grid_1_id = $("#grid_1").attr("data");
            var str = '';
            for(var i in data){
                if(data[i].id==grid_1_id) {
                    str += '<option selected value="' + data[i].id + '" title="' + data[i].name + '">' + data[i].short_name + '</option>'
                }else{
                    str += '<option value="' + data[i].id + '" title="' + data[i].name + '">' + data[i].short_name + '</option>'
                }
            }
            $('#grid_1').append(str);
            if(grid_1_id){
                grid_check(grid_1_id,'#grid_2');
            }
        }
    })
    //一级点击
    $('#grid_1').change(function(){
        var pid = $(this).val();
        getAndRenderNextGridList(pid,'#grid_2');
        $('#grid_3').empty();
        $('#grid_4').empty();
    })
    //二级
    $('#grid_2').change(function(){
        var pid = $(this).val();
        getAndRenderNextGridList(pid,'#grid_3');
        $('#grid_4').empty();
    })
    //三级
    $('#grid_3').change(function(){
        var pid = $(this).val();
        getAndRenderNextGridList(pid,'#grid_4');
    })
    function getAndRenderNextGridList(pid,appendTo){
        if(pid<=0)return;
        $.ajax({
            url     : '/data_population_next_grid_list',
            dataType: 'json',
            data    : {pid:pid},
            success : function(data){
                var str = '<option>请选择...</option>';
                for(var i in data){
                    str += '<option value="'+data[i].id+'" title="'+data[i].name+'">'+data[i].short_name+'</option>';
                }
                $(appendTo).empty().html(str);
            }
        })
    }


    function grid_check(pid,appendTo){
        if(pid<=0||appendTo=='#grid_4')return;
        $.ajax({
            url     : '/data_population_next_grid_list',
            dataType: 'json',
            data    : {pid:pid},
            success : function(data){
                var grid_id = $(appendTo).attr('data');
                var str = '<option>请选择...</option>';
                for(var i in data){
                    if(data[i].id==grid_id){
                        str += '<option value="'+data[i].id+'" selected title="'+data[i].name+'">'+data[i].short_name+'</option>';
                    }else{
                        str += '<option value="'+data[i].id+'" title="'+data[i].name+'">'+data[i].short_name+'</option>';
                    }
                }
                $(appendTo).empty().html(str);
                if(appendTo=="#grid_2"){
                    grid_check(grid_id,"#grid_3");
                }
                if(appendTo=="#grid_3"){
                    grid_check(grid_id,"#grid_4");
                }
            }
        })
    }

})