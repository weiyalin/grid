/**
 * Created by Administrator on 2016/6/16 0016.
 */
/**
 * Created by Administrator on 2016/6/14 0014.
 */
var saveUrl         = '/sys_user_manage_save';
var LoginNameCheckUrl = '/sys_user_login_name_unique';
//var getDefaultData  = '/get_default_data';
//var getCitysUrl     = '/get_city';
//var getDistrictsUrl = '/get_district';

$(function() {
// 表单验证
    formValidate();     //表单验证信息
    if($('input[name=id]').length == 0){    //新增的时候运行
        LoginNameCheck();   //检测登录用户名是否唯一
    }

})

function formValidate(){
    $("#user_info").validate({
        rules: {
            name: "required",
            //sex: "required",
            phone:{
                //required:true,
                digits:true,    //必须输入整数
            },
            email:{
                //required:true,
                email:true,
            },
            role_id:{
                required:true,
                //integer:true,
            },
            org_id: {
                required:true,
                //integer:true,
            },
            //title  : 'required',
            login_name:{
                required:true,
                rangelength:[6,20]
            },
            password:{              //不存在的时候不验证
                required:true,
                rangelength:[6,20]
            },
            /*password_confirmation:{
                required:true,
                equalTo:'#password',
            }*/
        },
        messages: {
            name: "请输入用户真实姓名",
            //sex: "请选择性别",
            phone:{
                //required:'请输入用户手机号',
                digits:'仅能包含数字',
            },
            email:{
                //required:'请输入用户邮箱',
                email:'邮箱的格式不正确'
            },

            role_id:{
                required:'请选择用户角色',
                //integer : '请选择用户角色'
            },
            org_id: {
                required:'请选择用户所属组织机构',
               // integer : '请选择用户所属组织机构',
            },
            //title:'请输入职位信息',
            login_name:{
                required:'请输入用户名',
                rangelength:'用户名长度需介于 6 ~ 20 位'
            },
            password:{
                required:'请输入密码',
                rangelength:'密码长度需介于 6 ~ 20 位'
            },
           /*不需要重复密码
           password_confirmation:{
                required:'请重复输入密码',
                equalTo:'两次输入密码不同',
            }*/
        },
        submitHandler:function(){
            $.ajax({
                url         : saveUrl,
                type        : 'post',
                dataType    : 'json',
                data        : $('#user_info').serialize(),
                success     : function(data){
                    if(data.code == 0){
                        Notify(data.msg, 'top-right', '8000', 'info', 'fa-tag', true);
                        location.href = '/sys_user_manage';
                    }else{
                        Notify(data.msg, 'top-right', '8000', 'warning', 'fa-warning', true);
                    }
                },
                error       : function(data){
                    if(data.status == 422){     //laravel自带验证器返回状态吗422的错误信息
                        msg = eval("("+data.responseText+")");  //把错误信息转化为json
                        for(var i in msg){                      //键不确定，所以用此方法，一般只返回一条信息
                            Notify(msg[i], 'top-right', '8000', 'warning', 'fa-warning', true);
                        }
                    }else{
                        alert('something wrong');
                    }
                }
            })
        }
    });
}

function LoginNameCheck(){
    $('#login_name').blur(function(){
        var login_name = $(this).val();
        $.ajax({
            url     : LoginNameCheckUrl,
            type    : 'get',
            dataType: 'json',
            data    : {login_name:login_name},
            success : function(data){
                if(data.code != 0){
                    $('#is_unique').html(data.msg);
                }else{
                    $('#is_unique').html('');
                }
            }
        })
    })
}