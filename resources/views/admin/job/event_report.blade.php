@extends('layout')
@section('header')
    <style>
        .error{
            color:#f00;
        }
    </style>
@endsection
@section('content')
<div class="widget">
    <!--header部分-->
    <div class="widget-header">
        <i class="widget-icon"></i>
        <span class="widget-caption">{{ $activeNav }}</span>
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
        <form class="form-horizontal" id="event_report" novalidate="novalidate">
            <div class="form-title">填写事件信息</div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">事件标题 <span style="color:#f00">*</span></label>
                        <div class="col-sm-9">
                            <input value="" type="text" name="title" class="form-control" id="title" placeholder="请输入事件标题"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">事件地址 <span style="color:#f00">*</span></label>
                        <div class="col-sm-3">
                            <select name="province" id="province" class="form-control">
                                <option value="XX省" selected style="display:none">XX省</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="city" id="city" class="form-control">
                                <option value="XX市" selected style="display:none">XX市</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="district" id="district" class="form-control">
                                <option value="XX区" selected style="display:none">XX区</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <input type="text" id="address" name="address" class="form-control" placeholder="请输入街道信息"/>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10">
                    <label class="col-sm-2 text-center">坐标确认 <span style="color:#f00">*</span></label>
                    <div class="col-sm-10" id="baidu_map" style="height:300px;border:3px solid #bbb;">
                    </div>
                </div>
            </div>
            <div class="row" style="padding:5px 0;">
                <div class="col-sm-2"></div>
                <div class="col-sm-3" style="padding:5px 0;">
                    <label class="col-sm-3">纬度</label>
                    <div class='col-sm-8'>
                        <input type="text" id="latitude" name="latitude" tips="纬度" value=""/>
                    </div>
                </div>
                <div class="col-sm-3" style="padding:5px 0;">
                    <label class="col-sm-3">经度</label>
                    <div class='col-sm-8'>
                        <input type="text" id="longitude" name="longitude" tips="经度" value=""/>
                    </div>
                </div>
                <div class="col-sm-3">
                    <span class="btn btn-warning" onclick="init_map()">坐标/地图 重置</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="reporter_name" class="col-sm-3 control-label">事件上报人  <span style="color:#f00">*</span></label>
                        <div class="col-sm-9">
                            <input value="" type="text" name="reporter_name" class="form-control" placeholder="请输入事件当事人"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="reporter_phone" class="col-sm-3 control-label">上报人联系方式 <span style="color:#f00">*</span></label>
                        <div class="col-sm-9">
                            <input value="" type="text" name="reporter_phone" class="form-control" placeholder="请输入当事人联系方式"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="source" class="col-sm-3 control-label">事件来源</label>
                        <div class="col-sm-9">
                            <select id="source" name="source" class="form-control">
                                <option value="0" selected>指挥中心</option>
                                <option value="1">网格员</option>
                                <option value="2">微信用户</option>
                                <option value="3">退回部门</option>
                                <option value="4">群众</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">事件来源人/部门</label>
                        <div class="col-sm-9">
                            <input id="source_format" value="指挥中心" type="text" class="form-control" name="source_format" placeholder="请填写事件来源部门/人员(选填)"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="last_process_time" class="col-sm-2 control-label">问题描述 <span style="color:#f00">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="desc" placeholder="请输入问题描述" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="additional_info" class="col-sm-2 control-label">补充信息</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="additional_info" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="suggest_info" class="col-sm-2 control-label">整改措施或建议</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="suggest_info" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <input id="pic_str" name="pic_str" type="hidden" value=""> <!--图片上传字段-->
        </form>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-9">
                        <label for="name" class="col-sm-2 control-label">多媒体附件</label>
                        <div class="col-sm-9" >
                            <div class="well attached top">
                                <a id="btnAttachment" class="btn btn-info" style="cursor:pointer;">添加</a>
                            </div>
                            <div class="well attached" id="div_attachments">

                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="btn-group col-sm-offset-4">
                    <button class="btn btn-blue btn-save" form="event_report" type="submit">
                        <i class="fa fa-save"></i> 提交 <!--通过jquery.validate提交验证-->
                    </button>
                </div>
            </div>

    </div>
</div>
@endsection

@section('footer')
<script src="ui_resource/js/jquery.validate.min.js"></script>
<script src="libs/upload/jquery.ocupload-1.1.2.js"></script>
<script src="http://api.map.baidu.com/api?v=2.0&ak=ONMZAsjQwi66QAWVcwKe5ELFS35ZVFkp" type="text/javascript"></script>
<script>
    $(function(){
        //填充省市区地址栏
        getAndRenderDefaultData();

        formValidate();

        //事件来源 change 事件
        $('#source').change(function(){
            var str = $(this).find('option:selected').html();
            $('#source_format').val(str);
        })

        //获取经纬度事件(change,blur事件均获取坐标)
        $('#address').on('input',function(){
            getLocation();
        })
        $('#address').blur(function(){
            getLocation();
        })

    })

    //地址经纬度获取
    function getLocation(){
        var a = $('#address').val();
        if(a){
            var p = $('#province').val();
            var c = $('#city').val();
            var d = $('#district').val();
            var address = p+c+d+a;
            var myGeo = new BMap.Geocoder();
            // 将地址解析结果显示在地图上，并调整地图视野
            myGeo.getPoint(address, function(point){
                showLocation(point.lng,point.lat)
                showOverlay(map,point.lng,point.lat);
                map.centerAndZoom(new BMap.Point(point.lng,point.lat), 15);
            }, c);
        }else{
            showLocation('','')
        }
    }

    //百度地图 选取坐标点
    var map = new BMap.Map("baidu_map", {enableMapClick: false}); //够早底图时，关闭底图可点功能
    //map.setDefaultCursor("crosshair");  //手势
    map.centerAndZoom(new BMap.Point(113.894961, 35.343954), 13);     //牧野区中间点：113.894961,35.343954
    map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
    getBoundary();
    //点击获取坐标
    map.addEventListener("click",function(e){
        showOverlay(map,e.point.lng, e.point.lat);
        showLocation(e.point.lng, e.point.lat);
    });

    //加坐标点图层
    function showOverlay(map,lng,lat){
        map.clearOverlays();
        var point = new BMap.Point(lng, lat);
        var marker = new BMap.Marker(point);    //创建标注
        map.addOverlay(marker);  //将标注添加到地图中
    }
    function init_map() {    //把地图中心对准牧野区(初始化)
        map.clearOverlays();
        map.centerAndZoom(new BMap.Point(113.894961, 35.343954), 13);
        $('#latitude').val('');
        $('#longitude').val('');
        //getBoundary()
    }
    //把坐标点显示在input框内
    function showLocation(lng,lat){
        $('#latitude').val(lat);
        $('#longitude').val(lng);
    }
    //添加牧野区图层
    function getBoundary(){
        var bdary = new BMap.Boundary();
        bdary.get("河南省新乡市牧野区", function(rs){       //获取行政区域
            map.clearOverlays();        //清除地图覆盖物
            var count = rs.boundaries.length; //行政区域的点有多少个
            if (count === 0) {
                console.log('未能获取当前输入行政区域');
                return ;
            }
            var pointArray = [];
            for (var i = 0; i < count; i++) {
                var ply = new BMap.Polygon(rs.boundaries[i], {
                    strokeStyle:'dashed',strokeWeight: 2    //, strokeColor: "#7FAEF5",fillColor:'#DCE4F0',setFillOpacity:'1'
                }); //建立多边形覆盖物
                ply.setFillColor('#DCE4F0');
                ply.setFillOpacity(0.2);
                ply.disableMassClear() ;    //进制被清除
                map.addOverlay(ply);  //添加覆盖物
            }
            //pointArray = pointArray.concat(ply.getPath());
            //map.setViewport(pointArray);    //调整视野
        });
    }


/** 百度地图   结束 **/
function formValidate(){
    //表单验证
    $('#event_report').validate({
        rules   : {
            title       : 'required',
            province    : 'required',
            city        : 'required',
            district    : 'required',
            address     : 'required',
            desc        : 'required',
            reporter_phone : 'required',
            reporter_name : 'required',
            latitude    : 'required',
            longitude   : 'required',
        },
        messages : {
            title       : '请输入事件标题',
            province    : '请输入事件标题',
            city        : '请选择城市信息',
            district    : '请选择区域信息',
            address     : '请填写详细地址',
            desc        : '请填写事件描述',
            reporter_phone : '请填写上报人的联系电话',
            reporter_name : '请填写上报人的姓名',
            latitude    : '请在地图上确认坐标',
            longitude   : '请在地图上确认坐标'
        },
        submitHandler   : function(){
            $.ajax({
                url     : '/job_event_report_save',
                type    : 'post',
                dataType: 'json',
                data    : $('#event_report').serialize(),
                success     : function(data){
                    if(data.code == 0){
                        Notify(data.msg, 'top-right', '5000', 'success', 'fa-check', true);
                        location.href = '/job_event_report';   //跳到待办事件
                    }else{
                        Notify(data.msg, 'top-right', '5000', 'warning', 'fa-warning', true);
                    }
                },
                error       : function(data){
                    if(data.status == 422){     //laravel自带验证器返回状态吗422的错误信息
                        msg = eval("("+data.responseText+")");  //把错误信息转化为json
                        for(var i in msg){                      //键不确定，所以用此方法，一般只返回一条信息
                            Notify(msg[i], 'top-left', '5000', 'warning', 'fa-warning', true);
                        }
                    }else{
                        alert('something wrong');
                    }
                }
            })
        }
    })
}

    //市 change事件
    $('select[name=city]').change(function(){
        var city_id = $(this).find('option:selected').attr('data_id');
        $.ajax({
            url     : '/get_district',
            dataType: 'json',
            type    : 'get',
            data    : {city_id:city_id},
            success : function(data){
                $('select[name=district]').empty();
                renderDistricts(data);
            }
        })
    })


    //获取省市区信息并填充，供选择
    //获取默认的省市区信息
    function getAndRenderDefaultData(){
        $.ajax({
            url: '/get_default_data',
            dataType: 'json',
            success: function (data) {
                //反别渲染 省 市 区
                renderProvinces(data.provinces);
                renderCitys(data.citys);
                renderDistricts(data.districts);
            },
            error  : function(){
                alert('something wrong');
            }

        })
    }




    //渲染省份信息（传进去的是二维数组）
    function renderProvinces(provinces){     //追加到html中的::select name="province"
        var str = '';
        for(var i in provinces){
            //alert(i+':::'+provinces[i].name+provinces[i].id);
            str += "<option data_id="+provinces[i].id+" value="+provinces[i].name+">"+provinces[i].name+"</option>";
        }
        //alert(str)
        //alert($('select[name=province]').attr('name'))
        $('select[name=province]').append(str);  //追加到省份信息下
    }
    //渲染 "市" 信息（传进去的是二维数组）
    function renderCitys(citys){
        var str = '';
        for(var i in citys){
            str += "<option data_id="+citys[i].id+" value="+citys[i].name+">"+citys[i].name+"</option>";
        }
        $('select[name=city]').append(str);
    }
    //渲染 “区” 信息（传进去的是二维数组）
    function renderDistricts(districts){
        var str = '';
        for(var i in districts){
            str += "<option data_id="+districts[i].id+" value="+districts[i].name+">"+districts[i].name+"</option>";
        }
        $('select[name=district]').append(str);
    }

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
                var photos = $('#pic_str').val().split(',');
                photos.push(src);

                if(photos[0] == false){
                    photos.splice(0,1);
                }
                //console.log(photos);
                $('#pic_str').val(photos.join(','));

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
        var photos = $('#pic_str').val().split(',');
        var index= photos.indexOf(src);
        photos.splice(index,1);
        //console.log(photos);
        $('#pic_str').val(photos.join(','));
    }
</script>
@endsection