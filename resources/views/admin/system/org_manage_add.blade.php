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
                <a class="btn btn-default btn-add" href="/sys_org_manage">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
                <button type="submit" form="org_info" class="btn btn-default btn-save">
                    <i class="fa fa-save"></i> 保存 <!--通过jquery.validate提交验证-->
                </button>
            </div>

            <form id="org_info" class="form-horizontal" style="margin:15px auto;width:100%;">
                {{--如果是编辑则id有值，若新增，则id无值--}}
                @if(isset($org_info))
                        <input type="hidden" name="id" value="{{ $org_info->id or null }}"/>
                @endif
                <!--第1行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">机构名称<span style="color:#f00">*</span></label>
                            <div class="col-sm-10">
                                <input value="{{ $org_info->name or null }}" type="text" name="name" class="form-control" id="name" placeholder="输入机构名称"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contact" class="col-sm-2 control-label">联系方式<span style="color:#f00">*</span></label>
                            <div class="col-sm-10">
                                <input value="{{ $org_info->contact or null }}" type="text" name="contact" class="form-control" id="contact" placeholder="请输入联系方式"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!--第2行-->
                <div class="row" style="padding-bottom:15px;">
                    <div class="col-sm-1">
                        <lable style="vertical-align:middle">机构地址<span style="color:#f00">*</span></lable>
                    </div>
                    <div class="col-sm-2">
                        <select name="province" class="form-control">
                            <!--option value="河南省" selected style='display:none;'>河南省</option-->          <!--默认河南新乡-->
                            <option value="{{ $default_district['province'] or $org_info->province }}" selected style="display:none">{{ $default_district['province'] or $org_info->province }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="city" class="form-control">
                            <!--option value="新乡市" selected style='display:none;'>新乡市</option-->          <!--默认河南新乡-->
                            <option value="{{ $default_district['city'] or $org_info->city }}" selected style="display:none">{{ $default_district['city'] or $org_info->city }}</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="district" class="form-control">
                            <!--option value="牧野区" selected style='display:none;'>牧野区</option-->     <!--默认牧野区-->
                            <option value="{{ $default_district['district'] or $org_info->district }}" selected style="display:none">{{ $default_district['district'] or $org_info->district }}</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input value="{{ $org_info->address or null }}" class="form-control" type="text" name="address" placeholder="请输入详细地址"/>
                    </div>
                </div>
                <!--第3行-->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="type" class="col-sm-2 control-label">机构类型<span style="color:#f00">*</span></label>
                            <div class="col-sm-6">
                                <select name="type" id="type" class="form-control">
                                    @if(isset($org_info->type)) {{--显示后台传过来的值，或显示“请选择。。。”--}}
                                        <option selected="selected" style="display:none" value="{{ $org_info->type }}">{{ $org_info->typeName }}</option>
                                    @else
                                        <option disabled selected style='display:none;'>请选择...</option>
                                    @endif
                                    <option value="1">镇办</option>
                                    <option value="3">社区（村）</option>
                                    <option value="2">职能部门</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="parent_id" class="col-sm-2 control-label">上级部门<span style="color:#f00">*</span></label>
                            <div class="col-sm-6">
                                <select name="parent_id" id="parent_id" class="form-control">
                                    @if(isset($org_info))
                                        <option selected="selected" style="display:none" value="{{ $org_info->parent_id }}">{{ $org_info->parentName }}</option>
                                    @endif
                                        <option value="0">无上级部门</option>
                                    @foreach($org_list as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                    第4行
                    还有经纬度、区域等 暂略过
                -->
            </form>
        </div>
    </div>
@endsection
@section('footer')
    <script src="ui_resource/js/jquery.validate.min.js"></script>
    <script src="ui_resource/js/org_manage_add.js"></script>
@endsection