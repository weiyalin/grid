<!DOCTYPE html>
@extends('layout')


@section('content')
<link href="assets/css/daterangepicker.css" rel="stylesheet" />
<style>
    .right-align{  text-align: right;  }
    .panel-body table td{padding:2px 5px;}
    .date-picker-wrapper{z-index:999;}
    .modal-darkorange .import-btn{
        cursor: pointer;
        /*margin-bottom: 10px;*/
    }
    .modal-darkorange .file-bar{
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
    }
    .modal-darkorange p{
        color:#777;
    }
    /*视频轮播图片*/
    .video{
        background: white;
        width: 100%;
        height: 700px;
    }
    .video1{
        margin:0 auto;
        width: 80%;
        height: 600px;
        background: white;
        margin-left: 90px;
    }
    /*----------隐藏导航全屏-----------*/
    .fa{
        display: none;
    }
    .video1 table tr td div{
        width: 260px;
        height: 180px;
        overflow: hidden;
        background: #00adee;
        margin-top: 15px;
        margin-left: 10px;
    }
    .video-body{
        margin:0 auto;
        z-index:0;
    }
    .video1 table tr td div img{
        width: 100%;
        height: 100%;
    }
    /*插件样式*/

</style>

<div class="widget">
    <div class="video">
        <!-- Demo -->
        <div id="owl-demo" class="owl-carousel video-body">
            <a href="javascript:void (0);" class="item">
                <div class="video1" >
                    <table>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/01.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/02.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/03.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/04.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/05.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/06.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/07.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/09.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/10.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/11.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/12.jpg" alt=""></div></td>
                        </tr>
                    </table>
                </div>
            </a>
            <a href="javascript:void (0);" class="item">
                <div class="video1">
                    <table cellpadding="20px">
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/13.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/14.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/15.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/03.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/04.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/12.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/14.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/03.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/05.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/11.jpg" alt=""></div></td>
                        </tr>
                    </table>
                </div>
            </a>
            <a href="javascript:void (0);" class="item">
                <div class="video1">
                    <table cellpadding="20px">
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/13.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/15.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/14.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/05.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/06.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/12.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/10.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/06.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/12.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/15.jpg" alt=""></div></td>
                        </tr>
                    </table>
                </div>
            </a>
            <a href="javascript:void (0);" class="item">
                <div class="video1">
                    <table cellpadding="20px">
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/01.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/06.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/07.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/10.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/12.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/11.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/04.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/01.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/13.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/05.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/09.jpg" alt=""></div></td>
                        </tr>
                    </table>
                </div>
            </a>
            <a href="javascript:void (0);" class="item">
                <div class="video1">
                    <table cellpadding="20px">
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/06.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/07.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/13.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/01.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/13.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/15.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/09.jpg" alt=""></div></td>
                        </tr>
                        <tr>
                            <td><div><img src="/admin/video_center/bgimg/05.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/04.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/08.jpg" alt=""></div></td>
                            <td><div><img src="/admin/video_center/bgimg/15.jpg" alt=""></div></td>
                        </tr>
                    </table>
                </div>
            </a>
        </div>
        <!-- Demo end -->
    </div>
    <div id="myModal" style="display:none; width: 800px;">
        <div class="row">
            <div class="col-md-12">
                <div id="a1"></div>


            </div>
        </div>
    </div>
</div><!--Widget-->
<script type="text/javascript" src="libs/ckplayer6.8/ckplayer/ckplayer.js"></script>
<script>
    $(function(){
        $('#owl-demo').owlCarousel({
            items: 1,
            autoPlay: true
        });
        $('.video1 table tr td div').click(function () {
            dialog();
        });
    });
    function dialog(){
        //加载视频
        var flashvars={
            f:'assets/video/m050.mp4',
            c:0,
            b:1,
            // i:'http://www.ckplayer.com/static/images/cqdw.jpg'
        };
        var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
        CKobject.embedSWF('libs/ckplayer6.8/ckplayer/ckplayer.swf','a1','ckplayer_a1','570','400',flashvars,params);
        /*
         CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
         下面三行是调用html5播放器用到的
         */
        // var video=['http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4->video/mp4'];
        var video=['assets/video/m050.mp4->video/mp4'];
        var support=['iPad','iPhone','ios','android+false','msie10+false'];
        CKobject.embedHTML5('a1','ckplayer_a1',570,500,video,flashvars,support);

        bootbox.dialog({
            message: $("#myModal").html(),
            title: "发现上访人员",
            className: "modal-darkorange",
            buttons: {
                success: {
                    label: "关闭",
                    className: "btn-blue",
                    callback: function () {
                        console.log('关闭');
                    }
                },
            }
        });
    }
</script>
@endsection