//设置各种URL
var saveUrl = '/data_population_save';
var checkIfHouseholderUrl = '/data_population_isHouseholder';

$(function(){
    //如果证件号码是身份证，则自动生成出生日期和年龄
    $('#card_code').blur(function(){
        var card_category = $('#card_category').val();
        if(card_category != '01'){
            return ;
        }
        var card_code = $(this).val();              //获取身份证号
        var birthday_y = card_code.substring(6,10);   //获取生日 --年
        var birthday_m = card_code.substring(10,12);   //获取生日 --月
        var birthday_d = card_code.substring(12,14);   //获取生日 --日
        if(birthday_d){
            $('#birthday').val(birthday_y+'-'+birthday_m+'-'+birthday_d+' 00:00:00');
        }
    })

    //居住地填完后，自动完成户籍所在地 、联系地址
    $('#domicile_address').blur(function(){
        var p = $('#domicile_province').val();
        var c = $('#domicile_city').val();
        var d = $('#domicile_district').val();
        var a = $(this).val();

        $('#contact_address').val(p+' '+c+' '+d+' '+a);     //填充联系地址

        //如果是固定人口，则，户籍地址 == 居住地址
        var is_fixed = $('input[name=is_fixed]:checked').val();
        if(is_fixed == 1){
            $('#family_address').val(a);
        }
    })

    //判断是否需要绑定户主信息
    $('input[name=is_householder]').change(function(){
        if($(this).val() == 1){
            $('.bind_householder').fadeOut();
        }else{
            $('.bind_householder').fadeIn();
        }
    })

    //验证绑定的户主信息
    $('#householder_id').blur(function(){
        var householder_id = $.trim($(this).val());
        if(householder_id.length != 18 && householder_id.length != 15){
            $('#bind_householder_tips').html('<span style="color:#f00;">有效身份证号应为15或18位</span>');
            return;
        }

        $.ajax({
            url     : checkIfHouseholderUrl,
            dataType: 'json',
            data    : {householder_id:householder_id},
            success : function(data){
                if(data.code == 0){ //是户主，可以使用
                    $('#bind_householder_tips').html('户主基本信息：'+data.result.name +' '+data.result.nation)
                }else{
                    $('#bind_householder_tips').html('<span style="color:#f00;">'+data.msg+'</span>')
                }
            },
            error   : function(data){
                alert('something wrong,please try again!')
            }
        })

    })

    //表格数据验证
    $('#population_info').bootstrapValidator({
        fields: {
            householder_id: {
                validators: {
                    callback : function(value,validator){
                        var householder_id = $('#householder_id').val();
                        if(householder_id.length !=15 || householder_id.length != 18){
                            return false;
                        }
                    }
                }
            },
        },
        submitHandler   : function(validator,form,submitButton){
            if(false){
                //可以在这里判断户主信息
                return;
            }
            $.ajax({
                url     : saveUrl,
                dataType: 'json',
                data    : form.serialize(),
                method  : 'post',
                success : function(data){
                    if(data.code ==0){
                        Notify(data.msg,'top-right','5000','success','fa-check',true);
                        location.href='/data_population';
                    }else{
                        Notify(data.msg,'top-right','5000','danger','fa-check',true);
                    }

                },
                error   : function(data){
                    if(data.status == 422){     				//laravel自带验证器返回状态吗422的错误信息
                        msg = eval("("+data.responseText+")");  //把错误信息转化为json
                        for(var i in msg){                      //键不确定，所以用此方法，一般只返回一条信息
                            msg[i]	//即为返回的消息；i不固定，为出错字段的字段值
                            Notify('存在无效数据，请重新检查数据<br/>'+msg[i], 'top-right', '5000', 'warning', 'fa-warning', true);
                        }
                    }else{
                        alert('something wrong, please try again!')
                    }
                }
            })
        }
    });
})

//省市区联动
$('#domicile_province option').click(function (){
    address_getNext(this,'#domicile_city',1);
});
$('#domicile_city option').click(function(){
    address_getNext(this,'#domicile_district',2);
})
$('#family_province option').click(function (){
    address_getNext(this,'#family_city',1);
});
$('#family_city option').click(function(){
    address_getNext(this,'#family_district',2);
})

//获取下一级地区信息
function address_getNext(obj,sId,level){  //obj:点击对象 sId:子id,指定把信息放入哪个子ID level:1/市 2/区
    var pId = $(obj).data('id');
    if(level == 1){
        var data = {province_id:pId};
        var url = '/get_city';
    }else if(level == 2){
        var data = {city_id:pId}
        var url = '/get_district';
    }

    var str = '';
    $.get(
        url,
        data,
        function(data){
            for(var i in data){
                str += "<option data-id='"+data[i].id+"' value='"+data[i].name+"' >"+data[i].name+"</option>";
            }
            $(sId).empty().html(str);
            //重新赋予下一级的点击事件
            if(level == 1){
                $('#domicile_city option').click(function(){
                    address_getNext(this,'#domicile_district',2);
                })
                $('#family_city option').click(function(){
                    address_getNext(this,'#family_district',2);
                })
            }
        },
        'json'
    );
}