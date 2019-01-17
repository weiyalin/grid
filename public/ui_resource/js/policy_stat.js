/**
 * Created by Administrator on 2016/7/15 0015.
 */

var statistics_url       = '/policy_stat_event_statistics';        //业务总体统计
var completion_rate_url   = '/policy_stat_event_completion_rate';   //业务办结率
var statistics_analysis_url     = 'policy_stat_statistics_analysis';
var map_data_url    = '/policy_stat_map_data';  //地图统计
$(function(){
    //daterangepicker设置
    dateRangePicker('#map_date');
    dateRangePicker('#completion_date');
    dateRangePicker('#source_date');
    dateRangePicker('#type_date');
    dateRangePicker('#office_date');
    dateRangePicker('#org_date');
    dateRangePicker('#redyellow_date');

    chart_summary();        //业务统计 图表一
    chart_completion();     //事件办结率图表二
    source_statistics();    //来源统计分析
    type_statistics();      //类型统计分析
    office_statistics();    //办事处分析
    org_statistics();       //职能部门统计

    //业务统计图表按钮
    $('.btn_tools button').click(function(){
        $('.btn_tools button').removeClass('btn-info');
        $(this).addClass('btn-info');
        chart_summary();
    })
    $('#summary_department').change(function(){
        chart_summary();
    })

    //办结率
    $('#completion_btn').click(function(){
        chart_completion();
    })

    //业务统计分析  检索按钮
    $('#source_search').click(function(){ source_statistics(); }) //来源统计分析
    $('#type_search').click(function(){ type_statistics(); }) //类型统计分析
    $('#office_search').click(function(){ office_statistics(); }) //办事处统计分析
    $('#org_search').click(function(){ org_statistics(); }) //职能部门统计分析
    $('#redyellow_search').click(function(){ redyellow_statistics(); }) //红黄牌统计分析

    //重置按钮点击事件
    $('#completion_reset').click(function(){    //业务办结率
        $('#completion_date').val('');
        $('#completion_depart').val(0);
        $('#completion_source').val(-1);
        chart_completion();
    })
    $('#chart_map_reset').click(function(){     //地图分布
        $('#map_date').val('');
        $('#map_depart').val(-1);
        $('#map_source').val(-1)
        refresh_chart_map();
    })
    $('#source_reset').click(function(){    //来源统计
        $('#source_date').val('');
        source_statistics();
    })
    $('#type_reset').click(function(){  //类型统计
        $('#type_date').val('');
        type_statistics();
    })
    $('#office_reset').click(function(){    //办事处统计
        $('#office_date').val('');
        office_statistics();
    })
    $('#org_reset').click(function(){   //职能部门统计
        $('#org_date').val('');
        org_statistics();
    })


})

//一些公用函数
//清空input框函数
function clear_input_box(id){ $(id).val(''); }
//daterangpicker设置
function dateRangePicker(id){
    $(id).dateRangePicker({
        showShortcuts   : true,
        shortcuts       : {
            'prev': ['month']
        },
        showWeekNumbers : true,
        startOfWeek     : 'monday',
        separator       : '--',
    })
}
/**  ************业务总体统计图表 开始******第一行*************  **/

var chart_summary_1 = echarts.init(document.getElementById('chart_summary_1')); //左图
var chart_summary_2 = echarts.init(document.getElementById('chart_summary_2')); //右图
chart_summary_1.showLoading();
chart_summary_2.showLoading();
// 指定图表的配置项和数据
option_summary_1 = {
    title   : {text: ''},
    tooltip : {},
    //egend  : {data:['受理数']},
    xAxis   : {data: ['受理数','办结数']},
    yAxis   : {name:'事件数'},
    series  : [{type: 'bar', data: []}],
    color   : ['#c23531','#2f4554']
};
option_summary_2 = {
    title: {text: '走势图'},
    tooltip: {trigger: 'axis'},
    legend: {data:['受理数','办结数']},
    xAxis: {data: [],name:'日期'},
    yAxis: {name:'事件数'},
    series: [
        {name: '受理数', type: 'line', data: []},
        {name: '办结数', type: 'line', data: []}
    ]
};
//同时渲染图一和图二
function chart_summary(){
    var time = $('.btn_tools .btn-info').data('value');     //时间范围按钮
    var depart = $('#summary_department').val();            //部门
    chart_summary_1.setOption(option_summary_1,true);       //图一设置
    chart_summary_2.setOption(option_summary_2,true);       //图二设置
    //获取后台数据
    $.getJSON(statistics_url,{time:time,depart:depart},function(data){
        //渲染图一
        chart_summary_1.setOption({
            title   : {text :'业务统计 -- '+data[1].title},
            series  : [{data : [data[1].total,data[1].done]}],
        });
        chart_summary_1.hideLoading();

        //渲染图二
        var x=[],total=[],done = [];
        $.each(data[2].x,function(i,n){x.push(n)});
        $.each(data[2].total,function(i,n){total.push(n)});
        $.each(data[2].done,function(i,n){done.push(n)});
        //var interval = 0; rotate = 0;   //数据比较多时，设置间隔和显示角度
        //data.time==1 ? rotate = 45 : null;
        //if(data.time == 2 && total.length > 17) rotate = -50;
        chart_summary_2.setOption({
            xAxis   : {data:x},
            series  : [{data:total},{data:done}]
        })
        chart_summary_2.hideLoading();
    })
}

/** ****************业务总体统计图表  结束****************** **/

/** ****************业务办结率 图表 开始**********第二行*********************** **/

var chart_completion_1 = echarts.init(document.getElementById('chart_complete_1'));
var chart_completion_2 = echarts.init(document.getElementById('chart_complete_2'));
chart_completion_1.showLoading();
chart_completion_2.showLoading();
option_complete_1 = {
    tooltip : {formatter: "{a} <br/>{b} : {c}%"},
    series: [
        {name: '业务指标', type: 'gauge', detail: {formatter:'{value}%'}, data: [{ value:0,title:{text:'办结率',subtext:''} }]}
    ]
};
option_complete_2 = {
    title: {text: '办结率走势图'},   //挡住其他东西了，暂不显示
    tooltip: {trigger: 'axis'},
    xAxis: {
        data: [] ,name : '日期'
    },
    yAxis: {name:'比率(%)'},
    series: [
        {name: '办结率', type: 'line', data: []}
    ]
};
function chart_completion(){
    //搜索框部分
    var time =  $('#completion_date').val();
    var depart = $('#completion_depart').val();
    var departName = $('#completion_depart').find('option:selected').data('name');
    var source = $('#completion_source').val();
    var sourceName = $('#completion_source').find('option:selected').data('name');
    var data = {time:time,depart:depart,departName:departName,source:source,sourceName:sourceName};

    chart_completion_1.setOption(option_complete_1,true);
    chart_completion_2.setOption(option_complete_2,true);

    //获取数据
    $.getJSON(completion_rate_url,data,function(data){
        //渲染图一
        chart_completion_1.setOption({
            title   : {text:'办结率',subtext:data[1].title},
            series  : [{ data : [{value:data[1].ratio,name:'办结率'}] }],
        });
        chart_completion_1.hideLoading();

        chart_completion_2.setOption({
            xAxis : { data :data[2]['x'] },
            series: { data : data[2]['y'] }
        })
        chart_completion_2.hideLoading();
    })
}

/** ****************业务办结率 图表 结束*********************** **/

/** ****************事件分布地图 图表 开始**********第三行*********************** **/



var chart_map = echarts.init(document.getElementById('chart_map'));
refresh_chart_map();
$('#chart_map_search').click(function(){
    refresh_chart_map();
})
function refresh_chart_map(id){
    chart_map.showLoading();
    var date = $('#map_date').val();
    var depart = $('#map_depart').val();
    var source = $('#map_source').val();
    data = {date:date,depart:depart,source:source};
    id = id ? id : 1;
    $.get(map_data_url+'?id='+id,data,function (usaJson) {
        chart_map.hideLoading();
        echarts.registerMap('xinxiang', usaJson.json);
        chart_map.setOption({
            tooltip: {
                trigger: 'item',
                formatter: '{a}{b}<br/>事件数： {c}'
            },
            visualMap: {
                min: 0,
                max: usaJson.max,
                text:['High','Low'],
                realtime: false,
                calculable: true,
                color: ['orangered','yellow','lightskyblue']
            },
            series: [{
                type: 'map',
                map: 'xinxiang',
                name: '',
                //mapType: 'USA', // 自定义扩展图表类型
                itemStyle:{
                    normal:{label:{show:true}},
                    emphasis:{label:{show:true}}
                },
                data:usaJson.data
            }]
        });
    });
}
//地图点击事件:网格下探
chart_map.on('click',function(params){
    console.log(params);
    var id = params.data.grid_id;
    refresh_chart_map(id);
})
/** ****************事件分布地图 图表 结束****************************** **/


/** ****************业务统计分析 开始**********第四行*********************** **/
            /*$('#myTab a').click(function(){
                var type = $(this).data('type');
                switch(type){
                    case 1 : source_statistics(); break;
                    case 2 : type_statistics(); break;
                }
            })*/
    //----------------------------------------------------
//业务统计 的 公共配置
common_statistics_option = {
    title: {text: '业务统计'},
    tooltip: {trigger: 'axis'},
    legend: {data:['排查数','办结数','办结率']},
    xAxis: {data: []},
    yAxis:  [
        {type: 'value', name: '事件数', axisLabel: {formatter: '{value}'}},
        {type: 'value', name: '办结率',axisLabel: {formatter: '{value}%'}}
    ],
    series: [
        {name: '排查数', type: 'bar', data: []},
        {name: '办结数', type: 'bar', data: []},
        {name: '办结率', type: 'line', yAxisIndex: 1, data: []}
    ]
};


// 1 . 来源统计
var chart_tab_source = echarts.init(document.getElementById('chart_tab_source'));
chart_tab_source.setOption(common_statistics_option,true);

function source_statistics(){
    chart_tab_source.showLoading();
    var type = 1;   //类型是来源统计分析
    var date = $('#source_date').val(); //时间段
    var title_date = date ? '--'+date : '';
    $.getJSON(statistics_analysis_url,{type:type,date:date},function(data){
        var total = [], done = [], ratio = [];
        $.each(data,function(i,n){
            total.push(n.total);
            done.push(n.done);
            ratio.push(n.ratio);
        })
        chart_tab_source.setOption({
            title: {text: '来源统计'+title_date},
            xAxis: {data: ['指挥中心','网格员','微信用户']},
            series :[
                {data :total},
                {data :done},
                {data :ratio}
            ]
        })
        chart_tab_source.hideLoading();
    });
}

 // -------------------------------------------------------
// 2 .类型统计
var chart_tab_type = echarts.init(document.getElementById('chart_tab_type'));
chart_tab_type.setOption(common_statistics_option,true);
function type_statistics(){
    chart_tab_type.showLoading();
    var type = 2;   //类型统计
    var date = $('#type_date').val(); //时间段
    var title_date = date ? '--'+date : '';

    var x = [],total=[],done=[],ratio=[];
    $.getJSON(statistics_analysis_url,{type:type,date:date},function(data){
        $.each(data,function(i,n){
            x.push(n.name);
            total.push(n.total);
            done.push(n.done);
            ratio.push(n.ratio);
        })
        chart_tab_type.setOption({
            title: {text: '类型统计'+title_date},
            xAxis : {data : x},
            series :[
                {data :total},
                {data :done},
                {data :ratio}
            ]
        })
        chart_tab_type.hideLoading();
    });
}

//办事处统计
var chart_tab_office = echarts.init(document.getElementById('chart_tab_office'));
chart_tab_office.setOption(common_statistics_option,true);
function office_statistics(){
    chart_tab_office.showLoading();
    var type = 3;
    var date = $('#office_date').val();  //时间段
    var title_date = date ? '--'+date : '';

    var x = [],total=[],done=[],ratio=[];
    $.getJSON(statistics_analysis_url,{type:type,date:date},function(data){
        $.each(data,function(i,n){
            x.push(n.name);
            total.push(n.total);
            done.push(n.done);
            ratio.push(n.ratio);
        })
        chart_tab_office.setOption({
            title: {text: '办事处统计'+title_date},
            xAxis : {data : x},
            series :[
                {data :total},
                {data :done},
                {data :ratio}
            ]
        })
        chart_tab_office.hideLoading();
    });
}

// 职能部门统计
var chart_tab_org = echarts.init(document.getElementById('chart_tab_org'));
chart_tab_org.setOption(common_statistics_option,true);
function org_statistics(){
    chart_tab_org.showLoading();
    var type = 4;
    var date = $('#org_date').val();  //时间段
    var title_date = date ? '--'+date : '';

    var x = [],total=[],done=[],ratio=[];
    $.getJSON(statistics_analysis_url,{type:type,date:date},function(data){
        $.each(data,function(i,n){
            x.push(n.name);
            total.push(n.total);
            done.push(n.done);
            ratio.push(n.ratio);
        })
        chart_tab_org.setOption({
            title: {text: '职能部门统计'+title_date},
            xAxis : {data : x},
            series :[
                {data :total},
                {data :done},
                {data :ratio}
            ]
        })
        chart_tab_org.hideLoading();
    });
}