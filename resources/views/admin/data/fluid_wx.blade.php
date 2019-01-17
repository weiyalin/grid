@extends('layout')
@section('header')
    <style>
        .row{margin:5px 0;}
    </style>
@endsection
@section('content')
<div class="widget">
    <div class="widget-header bg-default">
        <span class="widget-caption">流动人口微信平台</span>
        <div class="widget-buttons">
            <a href="#" data-toggle="maximize">
                <i class="fa fa-expand"></i>
            </a>
            <a href="#" data-toggle="collapse">
                <i class="fa fa-minus"></i>
            </a>
        </div>
    </div>

    <div class="widget-body">
        <form class="form-horizontal" id="fluid_wx_info">
            <div class="form-title">申报类型</div>
            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">申报类型</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="type"/>
                    </div>
                </div>
            </div>

            <div class="form-title">个人信息</div>
            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">姓名</label>
                    <div class="col-sm-8">
                        <input type="text" name="name" class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">证件号码</label>
                    <div class="col-sm-8">
                        <input type="text" name="id" class="form-control"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">联系电话</label>
                    <div class="col-sm-8">
                        <input type="text" name="phone" class="form-control"/>
                    </div>
                </div>
            </div>

            <div class="form-title">住宿信息</div>
            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">房东姓名</label>
                    <div class="col-sm-8">
                        <input type="text" name="fd_name" class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">房东电话</label>
                    <div class="col-sm-8">
                        <input type="text" name="fd_phone" class="form-control"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">房东身份证</label>
                    <div class="col-sm-8">
                        <input type="text" name="fd_id" class="form-control"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">入住日期</label>
                    <div class="col-sm-8">
                        <input type="text" name="date" class="form-control"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">合同期限</label>
                    <div class="col-sm-8">
                        <input type="text" name="deadline" class="form-control"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3">所在派出所</label>
                    <div class="col-sm-8">
                        <input type="text" name="paichusuo" class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label class="control-label col-sm-3" style="max-width:140px;">住址</label>
                    <div class="col-sm-4">
                        <select name="city" class="form-control">
                            <option value="新乡市">新乡市</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="city" class="form-control">
                            <option value="牧野区">牧野区</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2" style="max-width:140px;"></div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="输入街道信息"/>
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="输入小区/村庄"/>
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="输入具体的楼层、门牌号"/>
                </div>
            </div>
            <div class="row text-center" style="margin-top:30px;">
                <div class="col-sm-2 col-sm-offset-5">
                    <button class="btn btn-blue btn-lg">提交</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection