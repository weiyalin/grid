@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <link rel="stylesheet" href="libs/photoswipe/photoswipe.css">

    <!-- Skin CSS file (styling of UI - buttons, caption, etc.)
         In the folder of skin CSS file there are also:
         - .png and .svg icons sprite,
         - preloader.gif (for browsers that do not support CSS animations) -->
    <link rel="stylesheet" href="libs/photoswipe/default-skin/default-skin.css">

    <!-- Core JS file -->
    <script src="libs/photoswipe/photoswipe.min.js"></script>

    <!-- UI JS file -->
    <script src="libs/photoswipe/photoswipe-ui-default.min.js"></script>

    <style>
        .right-align{
            text-align: right;
        }
    </style>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">处理反馈</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
                {{--<a href="#" data-toggle="dispose">--}}
                {{--<i class="fa fa-times"></i>--}}
                {{--</a>--}}
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div id="horizontal-form">
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件标题</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="event_title" placeholder="" value="{{$event->title}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件地址</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="event_address" placeholder="" value="{{$event->address}}">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">事件上报人</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="event_reporter_name" placeholder="" value="{{$event->reporter_name}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">上报人联系方式</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="event_reporter_phone" placeholder="" value="{{$event->reporter_phone}}">

                            </div>
                        </div>
                    </div>
                </div>



                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">受理人姓名</label>
                            </div>
                            <div class="col-sm-9">
                                <label type="text" class="form-control" id="event_process_name" >{{$event->last_process_name}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">受理人联系方式</label>
                            </div>
                            <div class="col-sm-9">
                                <label type="text" class="form-control" id="event_process_phone">{{$event->last_process_phone}}</label>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">受理人单位</label>
                            </div>
                            <div class="col-sm-9">
                                <label type="text" class="form-control" id="event_process_org_name" >{{$event->last_process_org_name}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">受理时间</label>
                            </div>
                            <div class="col-sm-9">
                                <label class="form-control" id="event_process_time" >{{$event->last_process_time}}</label>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-1 right-align">
                                <label for="name" class="margin-top-10">问题描述</label>
                            </div>
                            <div class="col-sm-11">
                                <textarea class="form-control" rows="3" id="event_desc" placeholder="">{{$event->desc}}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-1 right-align">
                                <label for="name" class="margin-top-10">补充信息</label>
                            </div>
                            <div class="col-sm-11">
                                <textarea class="form-control" rows="3" id="event_additional_info" placeholder="">{{$event->additional_info}}</textarea>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row margin-top-10">
                    <div class="form-group">
                        <div class="col-sm-1 right-align">
                            <label for="name" class="margin-top-10">多媒体附件</label>
                        </div>
                        <div class="col-sm-11">
                            {{--<div class="well attached top">--}}
                                {{--<button id="btnAttachment" type="button" class="btn btn-info" onclick="attachment_add()">添加</button>--}}
                            {{--</div>--}}
                            <div class="well attached">
                                @foreach($event->attachment_list as $item)
                                    <img src="{{$item->path}}" class="img-thumbnail" style="width:140px;height:140px;" onclick="view_big('{{$item->path}}')"/>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row margin-top-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-1 right-align">
                                <label for="name" class="margin-top-10">选择类型</label>
                            </div>
                            <div class="col-sm-3">
                                <select id="category_1"  class="form-control" onchange="category_1_change()">
                                    <option value="0">选择一级分类</option>
                                    @foreach($category_1 as $category)
                                        <option value="{{$category->code}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select id="category_2"  class="form-control" onchange="category_2_change()">
                                    <option value="0">选择二级分类</option>

                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select id="category_3"  class="form-control" onchange="">
                                    <option value="0">选择三级分类</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-1 right-align">
                                <label for="name" class="margin-top-10">回复上报者</label>
                            </div>
                            <div class="col-sm-11">
                                <textarea class="form-control" rows="3" id="event_suggest_info" placeholder="">{{$event->suggest_info}}</textarea>
                            </div>
                        </div>
                    </div>

                </div>


                <br/>
                <div class="row margin-top-10 margin-top-10 margin-bottom-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-1">

                            </div>
                            <div class="col-sm-3">
                                <button id="btnSave" type="button" class="btn btn-success" onclick="">办结</button>
                            </div>
                            <div class="col-sm-2">
                                <button id="btnBack" type="button" class="btn btn-success" onclick="">退回</button>

                            </div>
                            <div class="col-sm-2">
                                <button id="btnReply" type="button" class="btn btn-success" onclick="">回复</button>

                            </div>
                            <div class="col-sm-2">
                                <button id="btnReturn" type="button" class="btn btn-success" onclick="javascript:location.href='/event_feedback_determine'">返回</button>

                            </div>

                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>

                <input type="hidden" id="pic_str" value="{{$event->pic_str}}"/>
                <input type="hidden" id="event_id" value="{{$event->id}}"/>
            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->
    <div id="msgModal" style="display:none;text-align: center;">
        {{--<img id="bigPic" src="#" class="img-thumbnail" style="width:900px;height:600px;"/>--}}

    </div>
    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" title="关闭 (Esc)"></button>

                    {{--<button class="pswp__button pswp__button--share" title="Share"></button>--}}

                    <button class="pswp__button pswp__button--fs" title="全屏"></button>

                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="上一个 (向左)">
                </button>

                <button class="pswp__button pswp__button--arrow--right" title="下一个 (向右)">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>

    <div style="display:none;" id="event_back_box">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name='memo' required rows="5" placeholder="请输入事件退回原因" class="form-control event_result"></textarea>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>


    <script type="text/javascript">
        $('#btnSave').click(function(){
            var id = $('#event_id').val();
            var title = $('#event_title').val();
            var address = $('#event_address').val();
            var reporter_name = $('#event_reporter_name').val();
            var reporter_phone = $('#event_reporter_phone').val();
            var desc = $('#event_desc').val();
            var additional_info = $('#event_additional_info').val();
            var suggest_info = $('#event_suggest_info').val();
            var pic_str = $('#pic_str').val();
            var org_id = 0;//已办结,不再下发部门
            var category_1 = $('#category_1').val();
            var category_2 = $('#category_2').val();
            var category_3 = $('#category_3').val();

            $.ajax({
                type: "post",
                url: '/event_process',
                data: {
                    id: id,
                    title:title,
                    address:address,
                    reporter_name:reporter_name,
                    reporter_phone:reporter_phone,
                    desc:desc,
                    additional_info:additional_info,
                    suggest_info:suggest_info,
                    attachment:pic_str,
                    next_process_org_id:org_id,
                    category_1:category_1,
                    category_2:category_2,
                    category_3:category_3,
                    status:1
                },
                success: function (data) {
                    if (data.code==0) {

                        Notify('办结成功', 'top-right', '3000', 'success', 'fa-check', true);
                        location.href='/event_feedback_determine';
                    } else {
                        Notify('办结失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        })

        $('#btnBack').click(function(){
            var id = $('#event_id').val();
            var suggest_info = $('#event_suggest_info').val();
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
                message: $('#event_back_box').html(),
                callback: function (result) {
                    if (result) {
                        var content = $.trim($('.bootbox .event_result').val());
                        if(content == ''){
                            Notify('请输入退回原因', 'top-right', '5000', 'warning', 'fa-tag', true);
                            return false;
                        }

                        $.ajax({
                            type: "post",
                            url: '/event_feedback_determine',
                            data: {
                                id: id,
                                suggest_info:suggest_info+'\n事件退回原因:'+content
                            },
                            success: function (data) {
                                if (data.code==0) {

                                    Notify('退回成功', 'top-right', '3000', 'success', 'fa-check', true);
                                    location.href='/event_already_determine';

                                } else {
                                    Notify('退回失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                                }
                            }
                        });
                    } else {

                    }
                },
                title: "确认信息"
            });
        })

        $('#btnReply').click(function(){
            var id = $('#event_id').val();
            var suggest_info = $('#event_suggest_info').val();
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
                message: '确定要进行回复吗？',
                callback: function (result) {
                    if (result) {
                        $.ajax({
                            type: "post",
                            url: '/event_feedback_determine',
                            data: {
                                id: id,
                                suggest_info:suggest_info
                            },
                            success: function (data) {
                                if (data.code==0) {

                                    Notify('回复成功', 'top-right', '3000', 'success', 'fa-check', true);
                                    location.href='/event_already_determine';

                                } else {
                                    Notify('回复失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                                }
                            }
                        });
                    } else {

                    }
                },
                title: "确认信息"
            });
        })

        function category_1_change(){
            var code = $('#category_1').val();
            $.ajax({
                type: "get",
                url: '/common_category',
                data: {
                    level:2,
                    code:code
                },
                success: function (data) {
                    if (data.code==0) {
                        var list = data.result;
                        $('#category_2 option').remove();
                        $('#category_2').append('<option value="0">选择二级分类</option>');
                        list.forEach(function(item){
                            $('#category_2').append("<option value='"+item.code+"'>"+item.name+"</option>");
                        })


                    } else {
                        Notify('获取事件类型失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });
        }

        function category_2_change(){
            var code = $('#category_2').val();
            $.ajax({
                type: "get",
                url: '/common_category',
                data: {
                    level:3,
                    code:code
                },
                success: function (data) {
                    if (data.code==0) {
                        var list = data.result;
                        $('#category_3 option').remove();
                        $('#category_3').append('<option value="0">选择三级分类</option>');
                        list.forEach(function(item){
                            $('#category_3').append("<option value='"+item.code+"'>"+item.name+"</option>");
                        })


                    } else {
                        Notify('获取事件类型失败', 'top-right', '3000', 'danger', 'fa-edit', true);
                    }
                }
            });

        }

        function view_big(src){
            var pic_list = $('#pic_str').val().split(',');
            var index=0;
            var items = [];
            for(var i=0;i<pic_list.length;i++){
                items.push({
                    src:pic_list[i],
                    w: 600,
                    h: 400
                });
                if(pic_list[i] == src){
                    index=i;
                }
            }

            var pswpElement = document.querySelectorAll('.pswp')[0];


            // define options (if needed)
            var options = {
                // optionName: 'option value'
                // for example:
                index: index // start at first slide
            };

            // Initializes and opens PhotoSwipe
            var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();



        }
    </script>
@endsection