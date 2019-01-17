@extends('layout')
@section('header')
    <style>
        label{padding:0 -15px;}
        .error{color:#f45551;}
    </style>
@endsection
@section('content')
    <div class="widget">
        <div class="widget-header">
            <i class="widget-icon"></i>
            <span class="widget-caption">{{ $title }}</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
            </div>
        </div>

        <div class="widget-body">
            <div class="btn-group">
                <a class="btn btn-default btn-add" href="/sys_user_manage">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
                <button type="submit" form="user_info" class="btn btn-default btn-save">
                    <i class="fa fa-save"></i> 保存 <!--通过jquery.validate提交验证-->
                </button>
            </div>

            <form id="user_info" class="form-horizontal" style="margin:15px auto;width:90%;">
                @if(isset($user))
                      <input type="hidden" name="id" value="{{ $user->id }}"/>
                @endif
                <!--第1行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">真实姓名<span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <input value="{{ $user->name or null }}" type="text" name="name" class="form-control" id="name" placeholder="输入真实姓名"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sex" class="col-sm-3 control-label">性别</label>
                            <div class="col-sm-3">
                                <div class="radio">
                                    <label>
                                        <input type="radio" class="colored-success" name="sex" value="1" @if(isset($user) && $user->sex==1) checked @endif/>
                                        <span class="text">男</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="radio">
                                    <label>
                                        <input type="radio" class="colored-success" name="sex" value="0" @if(!isset($user) || $user->sex==0) checked @endif/>
                                        <span class="text">女</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--第2行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label">手机</label>
                            <div class="col-sm-9">
                                <input value="{{ $user->phone or null }}" type="text" name="phone" id="phone" class="form-control" placeholder="请输入手机号"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">邮箱</label>
                            <div class="col-sm-9">
                                <input value="{{ $user->email or null }}" type="text" name="email" id="email" class="form-control" placeholder="请输入邮箱"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!--第3行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="role_id" class="col-sm-3 control-label">角色<span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <select name="role_id" id="role_id" class="form-control">
                                    <option style="display:none;" value="">请选择角色...</option>
                                    @foreach($data['role_list'] as $v)
                                        @if(isset($user) && $user->role_id == $v->id)
                                            <option value="{{ $v->id }}" selected>{{ $v->name }}</option>
                                        @else
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">职位</label>
                            <div class="col-sm-9">
                                <input value="{{ $user->title or null }}" type="text" name="title" id="title" class="form-control" placeholder="请输入职位"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!--第4行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="org_id" class="col-sm-3 control-label">组织机构<span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <select name="org_id" id="org_id" class="form-control">
                                    <option style="display:none;" value="">请选择组织机构...</option>
                                    @foreach($data['org_list'] as $v)
                                        @if(isset($user) && $user->org_id==$v->id)
                                            <option value="{{ $v->id }}" selected>{{ $v->name }}</option>
                                        @else
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="org_id" class="col-sm-3 control-label">辖区简介</label>
                            <div class="col-sm-9">
                                <textarea name="textarea" id="textarea" class="form-control">{{$user->textarea or null}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>{{--分割线--}}

                <!--第1行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="login_name" class="col-sm-3 control-label">登录用户名<span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <input value="{{ $user->login_name or null }}" type="text" name="login_name" id="login_name" class="form-control" placeholder="请输入登录用户名"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <span id="is_unique" style="color:#f00"></span><!--显示唯一性的检测结果-->
                        <span class="help-block">(*大于6位小于20位)</span>
                    </div>
                </div>
                @if(!isset($user)) <!--如果存在user,则为编辑查看-->
                    <!--第2行-->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label title="默认为“123456”" for="login_name" class="col-sm-3 control-label">登录密码<span style="color:#f00">*</span></label>
                                <div class="col-sm-9">
                                    <input value="123456" type="text" name="password" id="password" class="form-control" placeholder="请输入密码"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="help-block">默认为“123456” (*，自定义需大于6位小于20位)</span>
                        </div>
                    </div>
                    <!--第3行-->
                    <!--div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="col-sm-3 control-label">再次输入密码<span style="color:#f00">*</span></label>
                                <div class="col-sm-9">
                                    <input type="password" name="password_confirmation" id="confirmation_password" class="form-control" placeholder="请再次输入密码"/>
                                </div>
                            </div>
                        </div>
                    </div-->
                @endif
                <input id="photo" name="photo" type="hidden" value="{{$user->photo or null}}"> <!--图片上传字段-->
            </form>
            <div style="margin: 15px auto;width: 90%;" class="form-horizontal ">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label style="width: 12.5%;" class="col-sm-3 control-label text-align-right">用户头像</label>
                            <div style="width: 87.5%;" class="col-sm-9" >
                                <div class="well attached top">
                                    <a id="btnAttachment" class="btn btn-info" style="cursor:pointer;">添加</a>
                                </div>
                                <div class="well attached" id="div_attachments">
                                    @if(isset($user->pic)&&!empty($user->pic))
                                        @foreach($user->pic as $key=>$item)
                                            <img src="{{$item}}" class="img-thumbnail img_{{$key}}" style="width:140px;height:140px;">
                                            <a href="#" style="position:absolute;z-index:2;margin-left:-35px;" class="btn btn-default btn-sm shiny icon-only danger img_{{$key}}" tabindex="-1" onclick="img_remove('img_{{$key}}','{{$item}}')"><i class="fa fa-times "></i></a>
                                            </img>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="ui_resource/js/jquery.validate.min.js"></script>
    <script src="libs/upload/jquery.ocupload-1.1.2.js"></script>
    <script src="ui_resource/js/user_manage_view.js"></script>
    <script>
        //图片上传
        $("#btnAttachment").upload({
            action: '/attachment_upload',
            name: 'file',
            params: {
                'type': 1,
                'rand': Math.random()
            },
            onSelect: function (self, element) {
                //this.autoSubmit = false;
                //var strRegex = "(.xls|.XLS)$";
                //var re=new RegExp(strRegex,'i');
                //if (!re.test(this.filename())) {
                //    Notify('只允许上传excel文件', 'top-right', '3000', 'warning', 'fa-edit', true);
                //}
                //else {
                //    this.submit();
                //}
//                bootbox.dialog({
//                    message: $("#msgModal").html(),
//                    title: "温馨提示",
//                    className: ""
//                });
                this.submit();
            },
            onSubmit: function (self, element) {
                //$('.uploadfile').hide();
                //$('#ajax_update').parent().show();
                //alert('Uploading file...');
            },
            onComplete: function (data, self, element) {
                data = JSON.parse(data);
                if(data.code ==0){
                    var src = data.result;
                    var photos = $('#photo').val().split(',');
                    photos.push(src);

                    if(photos[0] == false){
                        photos.splice(0,1);
                    }
                    //console.log(photos);
                    $('#photo').val(photos.join(','));

                    var id = new Date().getTime();
                    var param = id + "," +"'"+src+"'";
                    $('#div_attachments').append('<img id="'+id+'" src="'+src+'" class="img-thumbnail '+id+'" style="width:140px;height:140px;"><a href="#" style="position:absolute;z-index:2;margin-left:-35px;" class="btn btn-default btn-sm shiny icon-only danger '+id+'" tabindex="-1" onclick="img_remove('+param+')"><i class="fa fa-times "></i></a></img>')

                }
                else {
                    Notify(data.msg, 'top-right', '3000', 'danger', 'fa-edit', true);

                }
            }
        });

        function img_remove(id,src){
            $('.'+id).remove();
            var photos = $('#photo').val().split(',');
            var index= photos.indexOf(src);
            photos.splice(index,1);
            //console.log(photos);
            $('#photo').val(photos.join(','));
        }
    </script>
@endsection