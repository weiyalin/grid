<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="_token" content="{{ csrf_token() }}"/>
<title>登录</title>
<link href="./ui_resource/css/login.css" type="text/css" rel="stylesheet" rev="stylesheet"/>
<script src="assets/js/jquery-2.0.3.min.js"></script>
	<script>
		$.ajaxSetup({
			headers:{
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			}
		})
	</script>
<style>
.warning {
	background-color: #d9534f;
	border-color: #d43f3a;
	color: #fff;
	padding:5px 0;
}
</style>
</head>
<body class="denglu02" onkeydown="keyLogin();">
	<div class="dl">
		<div class="biaoti"><img src="./ui_resource/img/login/ico03.png" /></div>
		<div class="log">
			<ul class="xuzhi02">
				 <li class="xz">系统特点</li>
				 <li>1.建立事件服务,为群众解决实事</li>
				 <li>2.建立民情日志,加强党群关系</li>
				 <li>3.建立考核系统,提高基层执政能力</li>
				 <li>4.整合社会资源,建立基础信息数据库</li>
			</ul>
			<form id="login_form">
			  <ul class="deng02">
				  <li id="tips" style="width:70%;margin:0 auto;height:20px;text-align:center;"></li>
				<li style=" width:100%; height:60px;">
					<p style="float:left;font-size:18px; color:#666;line-height:30px; ">用户名:</p> 
					<input name="login_name" id="login_name" class="i-text" type="text" maxlength="20" placeholder="请输入用户名"/>
				</li>
				<div style="clear:both;"></div>
		
				<li style=" width:100%; height:60px;"> 
					<p style="float:left;font-size:18px; color:#666;line-height:30px; ">密&nbsp;&nbsp;&nbsp;码:</p> 
					<input name="password" id="password" class="i-text" type="password" maxlength="20" placeholder="请输入密码"/>
				</li>
				<div style="clear:both;"></div>
				  {{--错误登录次数超过三次，则显示验证码--}}
				  @if(session('failLoginTimes') >= 3)
					<li style=" width:100%; height:60px;" id="captcha_box">
						<p style="float:left;font-size:18px; color:#666;line-height:30px; ">验证码:</p>
						<input name="captcha" id='captcha' class="i-text" type="text" placeholder="请输入验证码"/>
					</li>
				  @endif
			</form>
				<li style=" width:100%; height:60px;">
					<button id="loginbtn" class="btn-login02" type="button" >
					<span>登&nbsp;&nbsp;&nbsp;&nbsp;录</span>
				  </button>
				</li>
			</ul>
			
		</div>
	</div>
</body>
<script>
$(function(){

	function keyLogin(){
		if (event.keyCode==13){
			login();
		}
	}

	//登录按钮点击事件
	$('#loginbtn').click(function(){
		login();
	})
	//回车键登录
	$('#password').keydown(function(event){
		if(event.keyCode == 13){
			login();
		}
	})
	$('#login_name').keydown(function(event){
		if(event.keyCode == 13){
			login();
		}
	})
})
function login(){
	var login_name	= $.trim($('#login_name').val());	//获取用户名
	if(login_name.length == 0) {
		$("#tips").html("请输入登录帐号");
		$('#tips').addClass('warning');
		return;
	}
	var password = $.trim($('#password').val());		//获取密码
	if(password.length == 0){
		$('#tips').html('请输入密码');
		$('#tips').addClass('warning');
		return;
	}
	//验证码部分
			@if(session('failLoginTimes') >= 3)
	var captcha	= $.trim($('#captcha').val());			//获取验证码
	if(captcha.length == 0){
		$('#tips').html('请输入验证码');
		$('#tips').addClass('warning');
		return;
	}
	@endif


	//ajax发送数据
	$.ajax({
		type	: 'post',
		url		: '/login',
		dataType	: 'json',
		data	: $('#login_form').serialize(),
		success	: function(data){
			if(data.code == 0){
				location.href = "{{ url('/') }}";
			}else{
				//如果登录错误三次，并且验证码没有显示，则刷新一下页面,显示验证码
				if(data.result.failLoginTimes >= 3 && $('#captcha_box').length == 0){
					location.reload();
				}
				$('#tips').html(data.msg);
				$('#tips').addClass('warning');
			}
		},
		error	: function(){
			alert('something wrong');
		}
	})
}
</script>
</html>
