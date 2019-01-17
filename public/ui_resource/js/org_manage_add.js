/**
 * Created by Administrator on 2016/6/14 0014.
 */
var saveUrl         = '/sys_org_manage_save';
var getDefaultData  = '/get_default_data';
var getCitysUrl     = '/get_city';
var getDistrictsUrl = '/get_district';

$(function() {
// 表单验证
    formValidate();     //表单验证信息
    getAndRenderDefaultData();   //获取"省市区"信息并渲染

})

function formValidate(){
    $("#org_info").validate({
        rules: {
            name: {
                required:true,
                maxlength:45
            },
            type: "required",
            province:'required',
            city: 'required',
            district:'required',
            address: {
                required: true,
                maxlength: 100
            },
            contact:{
                required:true,
                maxlength:100
            }
        },
        messages: {
            name: {
                required:"请输入机构名称",
                maxlength:"长度不能超过100个字符"
            },
            type: "请选择机构类型",
            province:'请选择省份',
            city: '请选择城市',
            district:'请选择区/镇',
            address: {
                required: "请输入详细地址",
                maxlength: "长度不能超过100个字符"
            },
            contact:{
                required:'请输入联系方式',
                maxlength:'长度不能超过100个字符'
            }
        },
        submitHandler:function(){
            $.ajax({
                url         : saveUrl,
                type        : 'post',
                dataType    : 'json',
                data        : $('#org_info').serialize(),
                success     : function(data){
                    if(data.code == 0){
                        Notify(data.msg, 'top-right', '5000', 'info', 'fa-tag', true);
                        location.href = '/sys_org_manage';
                    }else{
                        Notify(data.msg, 'top-right', '5000', 'warning', 'fa-warning', true);
                    }
                },
                error       : function(data){
                    if(data.status == 422){     //laravel自带验证器返回状态吗422的错误信息
                        msg = eval("("+data.responseText+")");  //把错误信息转化为json
                        for(var i in msg){                      //键不确定，所以用此方法，一般只返回一条信息
                            Notify(msg[i], 'top-right', '5000', 'warning', 'fa-warning', true);
                        }
                    }else{
                        alert('something wrong');
                    }
                }
            })
        }
    });
}
//获取默认的省市区信息
function getAndRenderDefaultData(){
    $.ajax({
        url: getDefaultData,
        dataType: 'json',
        success: function (data) {
            //反别渲染 省 市 区
            renderProvinces(data.provinces);
            renderCitys(data.citys);
            renderDistricts(data.districts);
        },
        error  : function(){
            alert('something wrong');
        }

    })
}

//省 chang事件
$('select[name=province]').change(function(){
    var province_id = $(this).find("option:selected").attr('data_id');
    $.ajax({
        url     : getCitysUrl,
        dataType: 'json',
        type    : 'get',
        data    : {province_id:province_id},
        success : function(data){
            $('select[name=city]').empty();
            renderCitys(data);
        },
        error   : function(){
            alert('获取市区信息出错，请重试')
        }
    })
})
//市 change事件
$('select[name=city]').change(function(){
    var city_id = $(this).find('option:selected').attr('data_id');
    $.ajax({
        url     : getDistrictsUrl,
        dataType: 'json',
        type    : 'get',
        data    : {city_id:city_id},
        success : function(data){
            $('select[name=district]').empty();
            renderDistricts(data);
        }
    })
})

//渲染省份信息（传进去的是二维数组）
function renderProvinces(provinces){     //追加到html中的::select name="province"
    var str = '';
    for(var i in provinces){
        //alert(i+':::'+provinces[i].name+provinces[i].id);
        str += "<option data_id="+provinces[i].id+" value="+provinces[i].name+">"+provinces[i].name+"</option>";
    }
    //alert(str)
    //alert($('select[name=province]').attr('name'))
    $('select[name=province]').append(str);  //追加到省份信息下
}
//渲染 "市" 信息（传进去的是二维数组）
function renderCitys(citys){
    var str = '';
    for(var i in citys){
        str += "<option data_id="+citys[i].id+" value="+citys[i].name+">"+citys[i].name+"</option>";
    }
    $('select[name=city]').append(str);
}
//渲染 “区” 信息（传进去的是二维数组）
function renderDistricts(districts){
    var str = '';
    for(var i in districts){
        str += "<option data_id="+districts[i].id+" value="+districts[i].name+">"+districts[i].name+"</option>";
    }
    $('select[name=district]').append(str);
}