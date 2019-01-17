@extends('layout')
@section('content')
    <div class="widget form-horizontal">
        <!--header部分-->
        <div class="widget-header">
            <i class="widget-icon"></i>
            <span class="widget-caption">{{ $title }}</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a data-toggle="collapse" href="#">
                    <i class="fa fa-minus"></i>
                </a>
            </div>
        </div>
        <!--body部分-->
        <div class="widget-body" style="padding:20px;">
            <div class="form-title">
                <a class="btn btn-default" id="btn-back" href="{{ $backurl }}">
                    <i class="fa fa-chevron-left"></i>
                    返回
                </a>
            </div>
                @if(!$event)    {{--后台验证了下权限，可能会为空--}}
                    <div class="row text-center">
                       <div class="h4"> 无法查看相关信息</div>
                    </div>
                @else
                @if($type == 3)
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">事件状态:</label>
                                <div class="col-sm-9">
                                    <label class="control-label">{{ $event->statusDesc }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title" class="col-sm-3 control-label">事件标题 <span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <input readonly disabled value="{{ $event->title }}" type="text" name="title" class="form-control" id="title" placeholder="请输入事件标题"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">事件地址 <span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <input readonly disabled type="text" name="address" class="form-control"
                                       value="{{ $event->province }} {{ $event->city }} {{ $event->district }} {{ $event->address }}"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="reporter_name" class="col-sm-3 control-label">事件上报人  <span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <input readonly disabled value="{{ $event->reporter_name }}" type="text" name="reporter_name" class="form-control" placeholder="请输入事件当事人"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="reporter_phone" class="col-sm-3 control-label">上报人联系方式 <span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <input readonly disabled value="{{ $event->reporter_phone }}" type="text" name="reporter_phone" class="form-control" placeholder="请输入当事人联系方式"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="source" class="col-sm-3 control-label">事件来源</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" readonly disabled
                                       value="{{ $event->source_format }}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">最后办结期限</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" readonly disabled
                                       value="{{ $event->limit_end_time }}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="last_process_time" class="col-sm-2 control-label">问题描述 <span style="color:#f00">*</span></label>
                            <div class="col-sm-9">
                                <textarea readonly disabled class="form-control" name="desc" rows="3">{{ $event->desc }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="additional_info" class="col-sm-2 control-label">补充信息</label>
                            <div class="col-sm-9">
                                <textarea readonly disabled class="form-control" name="additional_info" rows="3">{{ $event->additional_info }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="suggest_info" class="col-sm-2 control-label">整改措施或建议</label>
                            <div class="col-sm-9">
                                <textarea id="suggest_info" readonly disabled class="form-control" name="suggest_info" rows="3">{{ $event->suggest_info }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label for="suggest_info" class="col-sm-2 control-label">多媒体附件</label>
                            <div class="col-sm-9">
                                <div class="well attached">
                                    @if($pics)
                                        <?php $pic_str = ''; ?>
                                        @foreach($pics as $pic)
                                            <img class="img-thumbnail" onclick="view_big('{{ $pic->path }}')" style="width:140px; height:140px;cursor:pointer;" src="{{ $pic->path }}"/>
                                            <?php
                                                $pic_str .= $pic->path.',';
                                            ?>
                                        @endforeach
                                            <input id="pic_str" type="hidden" value="{{ trim($pic_str,',') }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-----上传处理结果图片--------}}
                @if($type==2||$type==3||($type==1&&!empty($res_pics)))   {{--被退回且有处理结果附件，已领，办结事件显示--}}
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label for="suggest_info" class="col-sm-2 control-label">
                                    @if($type==2)
                                        上传处理结果
                                    @endif
                                    @if($type==3||$type==1)
                                        我的处理结果
                                    @endif
                                </label>
                                <div class="col-sm-9">
                                    @if($type==2)
                                        <div class="well attached top">
                                            <div style="position: relative; height: 33px; width: 52px; overflow: hidden; cursor: pointer; margin: 0px; padding: 0px;">
                                                <button id="btnAttachment" type="button" class="btn btn-info">添加</button>
                                            </div>
                                        </div>
                                        <div class="well attached" id="div_attachments">
                                            {{--@foreach($res_pics as $pic)--}}
                                                {{--<img class="img-thumbnail" onclick="view_big('{{ $pic->path }}')" style="width:140px; height: 140px; cursor:pointer;" src="{{ $pic->path }}"/>--}}
                                            {{--@endforeach--}}
                                            @foreach($res_pics as $item)
                                                <img src="{{$item->path}}" class="img-thumbnail img_{{$item->id}}" style="width:140px;height:140px;" onclick="view_big('{{$item->path}}')">
                                                <a href="#" style="position:absolute;z-index:2;margin-left:-35px;" class="btn btn-default btn-sm shiny icon-only danger img_{{$item->id}}" tabindex="-1" onclick="img_remove('img_{{$item->id}}','{{$item->path}}')"><i class="fa fa-times "></i></a>
                                                </img>
                                            @endforeach
                                        </div>
                                        <input id="pic_result" type="hidden" value="{{ $res_pics_str }}">
                                    @endif
                                    @if($type==3||$type==1)
                                        <div class="well attached">
                                            @foreach($res_pics as $pic)
                                                <img class="img-thumbnail" onclick="view_big('{{ $pic->path }}')" style="width:140px; height: 140px; cursor:pointer;" src="{{ $pic->path }}"/>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif



                <div class="row">
                    @if($type !=3)
                        <div class="col-sm-1">
                            <a class="btn btn-md btn-default" href="{{ $backurl }}"><i class="fa fa-chevron-left"></i>返回</a>
                        </div>
                        @if($type==1) {{--待办事件才显示--}}
                            <div class="col-sm-1 col-sm-offset-2">
                                <button class="btn btn-md btn-primary" v="{{ $id }}" onclick="event_get(this)"><i class="fa fa-thumb-tack"></i>领取</button>
                            </div>
                        @endif
                        @if($type==2)   {{--已领事件显示--}}
                            <div class="col-sm-1 col-sm-offset-2">
                                <button class="btn btn-md btn-primary" v="{{ $id }}" onclick="event_down(this)"><i class="fa fa-thumb-tack"></i>办结</button>
                            </div>
                        @endif
                        <div class="col-sm-1">
                            <button class="btn btn-md btn-danger" v="{{ $id }}" onclick="event_back(this)"><i class="fa fa-reply"></i>退回</button>
                        </div>
                    @endif
                </div>
                @endif

            <div aria-hidden="true" role="dialog" tabindex="-1" class="pswp">

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

                            <button title="关闭 (Esc)" class="pswp__button pswp__button--close"></button>
                            <button title="全屏" class="pswp__button pswp__button--fs"></button>
                            <button title="Zoom in/out" class="pswp__button pswp__button--zoom"></button>

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
                        <button title="上一个 (向左)" class="pswp__button pswp__button--arrow--left">
                        </button>
                        <button title="下一个 (向右)" class="pswp__button pswp__button--arrow--right">
                        </button>
                        <div class="pswp__caption">
                            <div class="pswp__caption__center"></div>
                        </div>
                    </div>
                </div>
            </div><!--图片展示框 end-->

            <div id="event_back_box" style="display:none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control event_result" placeholder="请输入退回原因" rows="4" required="" name="memo"></textarea>
                        </div>
                        <div class="help-block">
                            @if(isset($type)&&$type==1)  {{--待办事件--}}
                                * 事件将退回至指挥中心
                                <input type="hidden" id="backUrl" value="/job_pre_event_back"/>
                            @else
                                * 事件将退回至待办事件
                                <input type="hidden" id="backUrl" value="/job_get_event_back_to_pre"/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div id="event_down_box" style="display:none;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control event_result" rows="4" placeholder="请输入办理结果" required="" name="memo"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--body end-->
    </div>
@endsection
@section('footer')
<link rel="stylesheet" href="libs/photoswipe/photoswipe.css">
<link rel="stylesheet" href="libs/photoswipe/default-skin/default-skin.css">
<script src="libs/photoswipe/photoswipe.min.js"></script>
<script src="libs/photoswipe/photoswipe-ui-default.min.js"></script>
<script src="ui_resource/js/bootbox.js"></script>
<script src="ui_resource/js/functions.js"></script>
<script src="libs/upload/jquery.ocupload-1.1.2.js"></script>

<script>
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
        var options = {
            index: index // start at first slide
        };

        // Initializes and opens PhotoSwipe
        var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    }

    //领取按钮事件
    function event_get(obj){
        var id = $(obj).attr('v');
        var url = '/job_pre_event_get';
        bootConfirm('确认领取事件?',function(){
            $.ajax({
                url     : url,
                dataType: 'json',
                data    : {id:id},
                method  : 'post',
                success : function(data){
                    if(data.code){    //失败
                        Notify(data.msg, 'top-right', '5000', 'warning', 'fa-tag', true);
                    }else{  //成功
                        Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag', true);
                        location.href='/job_get_event';
                    }
                },
                error   : function(){
                    alert('网络问题，请重试');
                }
            })
        })
    }
    //事件退回按钮:待办、已领事件均有此按钮
    function event_back(obj){
        var id = $(obj).attr('v');
        var suggest_info = $('#suggest_info').val();
        var backUrl = $('#backUrl').val();
        bootbox.dialog({
            message : $('#event_back_box').html(),
            title   : '输入退回原因',
            className: '',
            buttons : {
                "取消"    : {
                    className : 'btn-warning',
                    callback : function(){}
                },
                success : {
                    label   : '提交',
                    className   : 'btn-blue',
                    callback    : function(){
                        //alert(id);
                        var content = $.trim($('.bootbox .event_result').val());
                        if(content == ''){
                            Notify('请输入退回原因', 'top-right', '5000', 'warning', 'fa-tag', true);
                            return false;
                        }
                        $.ajax({
                            url     : backUrl,
                            dataType: 'json',
                            data    :{id:id,memo:suggest_info+'\n事件退回：'+content,content:content},
                            success : function(data){
                                if(data.code == 0){   //成功
                                    Notify(data.msg, 'top-right', '5000', 'success', 'fa-tag', true);
                                    var backUrl = $('#btn-back').attr('href');
                                    location.href= backUrl;
                                }else{
                                    Notify(data.msg, 'top-right', '5000', 'warning', 'fa-tag', true);
                                }
                            },
                            error   : function(){
                                alert('something wrong，please try again');
                            }
                        })
                    }
                }
            }
        })
    }

    //办结按钮click事件
    function event_down(obj,event){
        var id = $(obj).attr('v');
        var suggest_info = $('#suggest_info').val();
        bootbox.dialog({
            message : $('#event_down_box').html(),
            title   : '输入办理结果',
            className: '',
            buttons : {
                "取消"    : {
                    className : 'btn-warning',
                    callback : function(){}
                },
                success : {
                    label   : '提交',
                    className   : 'btn-blue',
                    callback    : function(){
                        //alert(id);
                        var content = $.trim($('.bootbox .event_result').val());
                        if(content == ''){
                            Notify('请输入事件办理结果后再提交', 'top-right', '5000', 'warning', 'fa-tag', true);
                            return false;
                        }
                        //处理的结果图
                        var result_photos = $('#pic_result').val();
                        $.ajax({
                            url     : '/job_get_event_down',
                            dataType: 'json',
                            data    :{id:id,content:suggest_info+'\n办理结果：'+content,result_photos:result_photos},
                            success : function(data){
                                if(data.code == 0){   //成功
                                    Notify(data.msg, 'top-right', '5000', 'info', 'fa-tag', true);
                                    location.href='job_get_event';
                                }else{
                                    Notify(data.msg, 'top-right', '5000', 'warning', 'fa-tag', true);
                                }
                            },
                            error   : function(){
                                alert('something wrong，please try again');
                            }
                        })
                    }
                }
            }
        })
    }


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
                var photos = $('#pic_result').val().split(',');
                photos.push(src);

                if(photos[0] == false){
                    photos.splice(0,1);
                }
                //console.log(photos);
                $('#pic_result').val(photos.join(','));

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
        var photos = $('#pic_result').val().split(',');
        var index= photos.indexOf(src);
        photos.splice(index,1);
        //console.log(photos);
        $('#pic_result').val(photos.join(','));
    }

</script>
@endsection