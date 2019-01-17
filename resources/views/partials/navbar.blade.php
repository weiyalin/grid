<div class="navbar">
    <div class="navbar-inner" style="background: rgb(255, 22, 17);">
        <div class="navbar-container">
            <!-- Navbar Barnd -->
            <div class="navbar-header pull-left">
                <a title="综治网格化服务平台" href="/" class="navbar-brand" style="display:block;margin-left:30px;padding: 0px;">
                    <!--small>
                        <img style="width:25px;height:auto;" src="assets/img/air_logo.png" alt="" />
                    </small-->
                    <img src="ui_resource/img/logo4.png" style="margin-top: 8px;"/>
                </a>

                {{--<span class="navbar-brand " style="display:block;margin-left:10px;margin-top:25px;padding:8px 0 0 10px;font-size:15px;">
                    时间: {{date("Y-m-d H:i:s")}}
                </span>
                <div class="navbar-brand margin-left-30" >
                    <iframe width="420" scrolling="no" height="60" frameborder="0" allowtransparency="true" src="http://i.tianqi.com/index.php?c=code&id=12&color=%23FFFFFF&icon=1&num=1"></iframe>
                </div>--}}
            </div>
            <!-- /Navbar Barnd -->
            {{--<!-- Sidebar Collapse -->--}}
            {{--<div class="sidebar-collapse" id="sidebar-collapse">--}}
            {{--<i class="collapse-icon fa fa-bars"></i>--}}
            {{--</div>--}}
            <!-- /Sidebar Collapse -->
            <!-- Account Area and Settings --->
            <div class="navbar-header pull-right">
                <span class="navbar-brand " style="display:block;margin-left:10px;margin-top:25px;padding:8px 0 0 10px;font-size:15px;">
                    时间: {{date("Y-m-d H:i:s")}}
                </span>
                <div class="navbar-brand margin-left-30" >
                    <iframe width="420" scrolling="no" height="60" frameborder="0" allowtransparency="true" src="http://i.tianqi.com/index.php?c=code&id=12&color=%23FFFFFF&icon=1&num=1"></iframe>
                </div>

                <div class="navbar-account">
                    <ul class="account-area">
                        <li>
                            <a class="login-area dropdown-toggle" data-toggle="dropdown"
                               style="padding-top:11px !important;background: rgb(255, 22, 17);">
                                <div class="avatar" title="我的头像" style="border-left-width:0px;">
                                    <img id="avatar_2" src="{{profile('avatar')}}">
                                </div>
                                <section>
                                    <h2><span class="profile">{{profile('name')}}</span></h2>
                                </section>
                            </a>
                            <!--Login Area Dropdown-->
                            <ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                                <li class="username"><a>{{profile('name')}}</a></li>
                                <li class="email"><a>{{profile('mobile')}}</a></li>
                                <!--Avatar Area-->
                                <li>
                                    <div class="avatar-area">
                                        {{--<img id="avatar_1" src="{{profile('avatar')}}" class="avatar">--}}
                                        <img id="avatar_1" src="ui_resource/img/logo3.png"
                                             style="width: 100px;margin: 0 auto;display: block;margin-left: 5px;">
                                        {{--<div id="up_container">--}}
                                        {{--<span id="upload_avatar" class="caption">更改头像</span>--}}
                                        {{--</div>--}}
                                    </div>
                                </li>
                                <!--Avatar Area-->
                                {{--<li class="edit">--}}
                                {{--<a href="/profile" class="pull-left">设置</a>--}}
                                {{--<a href="/password" class="pull-right">修改密码</a>--}}
                                {{--</li>--}}
                                <!--Theme Selector Area-->
                                {{--<li class="theme-area">--}}
                                {{--<ul class="colorpicker" id="skin-changer">--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#5DB2FF;" rel="assets/css/skins/blue.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#2dc3e8;" rel="assets/css/skins/azure.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#03B3B2;" rel="assets/css/skins/teal.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#53a93f;" rel="assets/css/skins/green.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#FF8F32;" rel="assets/css/skins/orange.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#cc324b;" rel="assets/css/skins/pink.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#AC193D;" rel="assets/css/skins/darkred.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#8C0095;" rel="assets/css/skins/purple.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#0072C6;" rel="assets/css/skins/darkblue.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#585858;" rel="assets/css/skins/gray.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#474544;" rel="assets/css/skins/black.min.css"></a></li>--}}
                                {{--<li><a class="colorpick-btn" href="#" style="background-color:#001940;" rel="assets/css/skins/deepblue.min.css"></a></li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}
                                <!--/Theme Selector Area-->
                                <li class="dropdown-footer">
                                    <a href="/password" class="pull-left">修改密码</a>
                                    <a href="/logout" class="">
                                        退出
                                    </a>
                                </li>
                            </ul>
                            <!--/Login Area Dropdown-->
                        </li>
                        {{--<li>--}}
                        {{--<a class="login-area dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<div class="avatar"  title="我的头像" style="border-left-width:0px;">--}}
                        {{--<img id="avatar_2"  src="{{profile('teacher_avatar')}}">--}}
                        {{--</div>--}}
                        {{--<section>--}}
                        {{--<h2><span class="profile"><span style="width:100px;">{{session('current_school_name')}}</span></span></h2>--}}
                        {{--</section>--}}
                        {{--</a>--}}

                        {{--<ul class="dropdown-menu dropdown-menu-usermenu pull-right">--}}
                        {{--@foreach(session('rela_school') as $sss)--}}
                        {{--<li>--}}
                        {{--<a href="#" id="s_{{$sss->id}}" onclick="change_school({{$sss->id}})">{{$sss->name}}</a>--}}
                        {{--</li>--}}
                        {{--@endforeach--}}
                        {{--</ul>--}}

                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<select id="master_school"  class="form-control margin-5" onchange="school_select()" >--}}
                        {{--@foreach(session('rela_school') as $sss)--}}
                        {{--<option value="{{$sss->id}}" {{$sss->id == session('current_school') ? 'selected':''}}>{{$sss->name}}</option>--}}
                        {{--@endforeach--}}
                        {{--</select>--}}
                        {{--</li>--}}
                        <!-- /Account Area -->
                        <!--Note: notice that setting div must start right after account area list.
                        no space must be between these elements-->
                        <!-- Settings -->
                    </ul>
                    <div class="setting">
                        <a id="btn-setting2" title="Setting" href="#">
                            <i class="icon glyphicon glyphicon-cog"></i>
                        </a>
                    </div>
                    {{--<div class="setting">--}}
                    {{--<a title="修改密码" href="/password">--}}
                    {{--<i class="icon glyphicon glyphicon-cog"></i>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="setting-container">--}}
                    {{--<label>--}}
                    {{--<input type="checkbox" id="checkbox_fixednavbar">--}}
                    {{--<span class="text">Fixed Navbar</span>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<input type="checkbox" id="checkbox_fixedsidebar">--}}
                    {{--<span class="text">Fixed SideBar</span>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<input type="checkbox" id="checkbox_fixedbreadcrumbs">--}}
                    {{--<span class="text">Fixed BreadCrumbs</span>--}}
                    {{--</label>--}}
                    {{--<label>--}}
                    {{--<input type="checkbox" id="checkbox_fixedheader">--}}
                    {{--<span class="text">Fixed Header</span>--}}
                    {{--</label>--}}
                    {{--</div>--}}
                    <!-- Settings -->
                </div>
            </div>
            <!-- /Account Area and Settings -->
        </div>
    </div>
</div>