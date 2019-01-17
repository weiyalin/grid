
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>行政区域工具</title>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ONMZAsjQwi66QAWVcwKe5ELFS35ZVFkp"></script>
</head>
<body>
<div style="width:520px;height:340px;border:1px solid gray" id="container"></div>
<p><input id="startBtn" type="button" onclick="startTool();" value="开启取点工具" /><input type="button" onclick="clear_coord();" value="清除" /></p>
<div id="info"></div>
</body>
</html>
<script type="text/javascript">
    var map_obj = window.parent.map_obj?window.parent.map_obj:'';//继承并记录拐点坐标

    var map = new BMap.Map("container");                        // 创建Map实例

    if(map_obj!=''){//编辑时网格第一个点设置为地图初始位置
        var coord_sole_old = map_obj.split(";")[0];
        var map_top_default = coord_sole_old.split(',');
        var point_default = new BMap.Point(map_top_default[0],map_top_default[1]);
        map.centerAndZoom(point_default,15);
    }else{//添加网格时默认设置
        map.centerAndZoom("新乡市牧野区", 15);     // 初始化地图,设置中心点坐标和地图级别
    }
//    map.centerAndZoom("新乡市牧野区", 15);     // 初始化地图,设置中心点坐标和地图级别

    var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
    var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
    var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮


     map.addControl(top_left_control);
     map.addControl(top_left_navigation);
     map.addControl(top_right_navigation);


    var key = 1;    //开关
    var newpoint;   //一个经纬度
    var points = [];    //数组，放经纬度信息
    var polyline = new BMap.Polyline(); //折线覆盖物
    var polygon='';

    function startTool(){   //开关函数
        if(key==1){
            document.getElementById("startBtn").style.background = "green";
            document.getElementById("startBtn").style.color = "white";
            document.getElementById("startBtn").value = "开启状态";
            key=0;
        }
        else{
            document.getElementById("startBtn").style.background = "red";
            document.getElementById("startBtn").value = "关闭状态";
            key=1;
        }
    }

    //清楚所有我画的地图
    function clear_coord(){
        map.removeOverlay(polyline);
        if(polygon!=''){
            map.removeOverlay(polygon);
            polygon='';
        }

//        document.getElementById('info').innerHTML = '';
        points=[];

        map_obj='';//已记录拐点清除
    }

    map.addEventListener("click",function(e){   //单击地图，形成折线覆盖物
        newpoint = new BMap.Point(e.point.lng,e.point.lat);
        if(key==0){
            //    if(points[points.length].lng==points[points.length-1].lng){alert(111);}
            points.push(newpoint);  //将新增的点放到数组中
            polyline.setPath(points);   //设置折线的点数组
            map.addOverlay(polyline);   //将折线添加到地图上
//            document.getElementById("info").innerHTML += "new BMap.Point(" + e.point.lng + "," + e.point.lat + "),</br>";    //输出数组里的经纬度
            map_obj += e.point.lng+","+e.point.lat+";"//记录拐点坐标
        }
    });
//    map.addEventListener("dblclick",function(e){   //双击地图，形成多边形覆盖物
//        if(key==0){
//            map.disableDoubleClickZoom();   //关闭双击放大
//            polygon = new BMap.Polygon(points);
//            map.addOverlay(polygon);   //将折线添加到地图上
//        }
//    });

    //Ctrl+z 后退
    document.onkeydown = function() {
        var oEvent = window.event;
        if ((oEvent.keyCode == 90||oEvent.keyCode==122) && oEvent.ctrlKey && points.length > 0) {
            points.pop();
            polyline.setPath(points);   //设置折线的点数组
            map.addOverlay(polyline);   //将折线添加到地图上
            map_obj = '';
            for(var i = 0;i < points.length;i++){
                map_obj += points[i]['lng']+","+points[i]['lat']+";"//记录拐点坐标
            }
        }
    }

    //预先画好上一次的画图结果
    if(map_obj!=''){
        var coord_sole = map_obj.split(";");
        for(var i = 0;i < coord_sole.length;i++){
            if(coord_sole[i].length>0){
                var x_y = coord_sole[i].split(',');
                newpoint = new BMap.Point(x_y[0], x_y[1]);
                points.push(newpoint);  //将新增的点放到数组中
            }
        }
        polyline.setPath(points);   //设置折线的点数组
        map.addOverlay(polyline);   //将折线添加到地图上
    }

    //放大地图
    function full_screen(width,height){
        document.getElementById('container').style.height=height+'px';
        document.getElementById('container').style.width=width+'%';
    }

    //还原地图
    function recover(){
        document.getElementById('container').style.height='340px';
        document.getElementById('container').style.width='520px';
    }

    var color = ['red','blue','green','greenyellow'];
    //批量画地图画范围
    function set_map(data){
        map.clearOverlays();
        for(var j = 0;j < data.length;j++){
            var coord_sole = data[j]['map'].split(";");
            var newpoint;   //一个经纬度
            var points=[];;    //数组，放经纬度信息
            for(var i = 0;i < coord_sole.length;i++){
                if(coord_sole[i].length>0){
                    var x_y = coord_sole[i].split(',');
                    newpoint = new BMap.Point(x_y[0], x_y[1]);
                    points.push(newpoint);  //将新增的点放到数组中
                }
            }
            var polyline = new BMap.Polyline(points,{strokeColor:color[j%color.length], strokeWeight:6, strokeOpacity:0.5}); //折线覆盖物
            map.addOverlay(polyline);   //将折线添加到地图上
        }
    }

</script>