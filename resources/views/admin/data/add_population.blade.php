<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- Head -->
<head>
    <meta charset="utf-8" />
    <title>人口导入</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="description" content="blank page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    @include('partials.header')

    <style>
        /*.page-sidebar {*/
        /*width:150px !important;*/
        /*}*/
    </style>
</head>
<!-- /Head -->
<!-- Body -->
<body>
<!-- Loading Container -->
<div class="loading-container">
    <div class="loading-progress">
        {{--<img src="assets/img/loading_gray.gif"/>--}}
        <div class="rotator">
            <div class="rotator">
                <div class="rotator colored">
                    <div class="rotator">
                        <div class="rotator colored">
                            <div class="rotator colored"></div>
                            <div class="rotator"></div>
                        </div>
                        <div class="rotator colored"></div>
                    </div>
                    <div class="rotator"></div>
                </div>
                <div class="rotator"></div>
            </div>
            <div class="rotator"></div>
        </div>
        <div class="rotator"></div>
    </div>
</div>
<!--  /Loading Container -->

<!-- Main Container -->
<div class="main-container container-fluid">
    <!-- Page Container -->
    <div class="page-container">
        <!-- Page Content -->
        <div class="page-content">
            <!-- Page Breadcrumb -->
            <div class="page-body">

                <div class="row margin-top-10">
                    <div class="form-group">
                        <div class="col-sm-12" >
                            <div class="well attached top" style="margin:10%;">
                                <select id="type" data="{{ $type }}" style="float: left; margin-right: 20px;">
                                    <option value="lowest_ensure" selected>低保</option>
                                    <option value="aging" >老龄</option>
                                    <option value="rest" >养老院</option>
                                    <option value="preferential_treatment" >优抚</option>
                                </select>
                                <button id="btnAttachment" type="button" class="btn btn-info">导入excel</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- /Page Body -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Container -->
    <!-- Main Container -->
    @include('partials.footer')
</div>
</body>
    <script src="libs/upload/jquery.ocupload-1.1.2.js"></script>
    <script>
        $("#type option[value="+$("#type").attr('data')+"]").attr("selected", true);
        var type = $("#type").val();
        var url = '/excel_upload?excel_type='+type;
        $("#type").change(function(){
            type = $("#type").val();
            url = '/population_import?excel_type='+type;
            window.location.href=url;
        })
        $(function(){
            $("#btnAttachment").upload({
                action: url,
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

                    }else {

                    }
                }
            });
        })
    </script>
</html>
