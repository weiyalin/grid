<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ECharts</title>

    {{--<script type="text/javascript" src="libs/player/vxgplayer-1.7.45.min.js"></script>--}}
    {{--<link href="libs/player/vxgplayer-1.7.45.min.css" rel="stylesheet"/>--}}
    <script src="assets/js/jquery-2.0.3.min.js"></script>

    <script src="libs/jwplayer/jwplayer.js"></script>
    <script>jwplayer.key="1GKQ175WWoLO/fDsQKmy+Y80zGpKdpJ4Tb6aZg==";</script>
</head>
<body>
<div id="container"></div>
<input type="button" class="player-play" value="播放" />
<input type="button" class="player-stop" value="停止" />
<input type="button" class="player-status" value="取得状态" />
<input type="button" class="player-current" value="当前播放秒数" />
<input type="button" class="player-goto" value="转到第30秒播放" />
<input type="button" class="player-length" value="视频时长(秒)" />


<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
{{--<div id="main" style="width: 600px;height:400px;"></div>--}}
{{--<div class="vxgplayer"--}}
     {{--id="vxg_media_player1"--}}
     {{--width="640"--}}
     {{--height="480"--}}
     {{--url="rtsp://218.204.223.237:554/live/1/67A7572844E51A64/f68g2mj7wjua3la7.sdp"--}}
     {{--nmf-src="libs/player/pnacl/Release/media_player.nmf"--}}
     {{--nmf-path="media_player.nmf"--}}
     {{--useragent-prefix="MMP/3.0"--}}
     {{--latency="10000"--}}
     {{--autohide="2"--}}
     {{--volume="0.7"--}}
     {{--avsync--}}
     {{--controls--}}
     {{--mute--}}
     {{--aspect-ratio--}}
     {{--aspect-ratio-mode="1"--}}
     {{--auto-reconnect>--}}

{{--</div>--}}

{{--<div id="dynamicallyPlayers"></div>--}}

{{--<script type="text/javascript">--}}
    {{--function createPlayer(){--}}
        {{--indexPlayer++;--}}
        {{--var playerId = 'vxg_media_player' + indexPlayer;--}}
        {{--var div = document.createElement('div');--}}
        {{--div.setAttribute("id", playerId);--}}
        {{--div.setAttribute("class", "vxgplayer");--}}
        {{--var runtimePlayers = document.getElementById('dynamicallyPlayers');--}}
        {{--runtimePlayers.appendChild(div);--}}
        {{--vxgplayer(playerId, {--}}
            {{--url: '',--}}
            {{--nmf_path: 'media_player.nmf',--}}
            {{--nmf_src: 'libs/player/pnacl/Release/media_player.nmf',--}}
            {{--latency: 300000,--}}
            {{--aspect_ratio_mode: 1,--}}
            {{--autohide: 3,--}}
            {{--controls: true--}}
        {{--}).ready(function(){--}}
            {{--console.log(' =>ready player '+playerId);--}}
            {{--vxgplayer(playerId).src('rtsp://184.72.239.149/vod/mp4:BigBuckBunny_115k.mov');--}}
            {{--vxgplayer(playerId).play();--}}
            {{--console.log(' <=ready player '+playerId);--}}
        {{--});--}}

    {{--}--}}

{{--//    var player = vxgplayer('vxg_media_player1');--}}
{{--//    player.play()--}}
{{--//    player.isPlaying() // get play true of false--}}
    {{--// where 'vxg_media_player1' - unique id element in document--}}

{{--</script>--}}


<script type="text/javascript">
    var thePlayer;  //保存当前播放器以便操作
    $(function() {
        thePlayer = jwplayer('container').setup({
            flashplayer: 'libs/jwplayer/jwplayer.flash.swf',
            file: 'rtsp://218.204.223.237:554/live/1/67A7572844E51A64/f68g2mj7wjua3la7.sdp',
            width: 500,
            height: 350,
            dock: false
        });

        //播放 暂停
        $('.player-play').click(function() {
            if (thePlayer.getState() != 'PLAYING') {
                thePlayer.play(true);
                this.value = '暂停';
            } else {
                thePlayer.play(false);
                this.value = '播放';
            }
        });

        //停止
        $('.player-stop').click(function() { thePlayer.stop(); });

        //获取状态
        $('.player-status').click(function() {
            var state = thePlayer.getState();
            var msg;
            switch (state) {
                case 'BUFFERING':
                    msg = '加载中';
                    break;
                case 'PLAYING':
                    msg = '正在播放';
                    break;
                case 'PAUSED':
                    msg = '暂停';
                    break;
                case 'IDLE':
                    msg = '停止';
                    break;
            }
            alert(msg);
        });

        //获取播放进度
        $('.player-current').click(function() { alert(thePlayer.getPosition()); });

        //跳转到指定位置播放
        $('.player-goto').click(function() {
            if (thePlayer.getState() != 'PLAYING') {    //若当前未播放，先启动播放器
                thePlayer.play();
            }
            thePlayer.seek(30); //从指定位置开始播放(单位：秒)
        });

        //获取视频长度
        $('.player-length').click(function() { alert(thePlayer.getDuration()); });
    });
</script>
</body>
</html>