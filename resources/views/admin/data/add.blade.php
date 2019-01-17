@extends('layout')
@section('header')
    <style>
        .row{margin:5px 0;}
        .help-block{color:#f00;}
        .bootstrap-tagsinput{
            width: 66%;
            float: left;
        }
        .hint{
            line-height: 34px;
            margin-left: 15px;
            color: gray;
        }
    </style>
@endsection
@section('content')
    <div class="widget">
        <div class="widget-header bg-default">
            <span class="widget-caption">新增人口</span>
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
            <div class="btn-group">
                <a href="/data_population" class="btn btn-default btn-add">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <!--  基本信息  -->
                <div class="form-title">基本信息</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3">姓名：<span style="color:#f00">*</span></label>
                        <div class="col-md-9">
                            <input value="" type="text" name="name" id="name" class="form-control"
                                data-bv-notempty data-bv-notempty-message="请输入姓名"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label col-md-3">性别：</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input checked  type="radio" name="sex" value="1"/>
                                    <span class="text">男</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input  type="radio" name="sex" value="2"/>
                                    <span class="text">女</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input  type="radio" name="sex" value="0"/>
                                    <span class="text">未知</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="sex" value="9"/>
                                    <span class="text">未说明</span>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="nationality" class="col-md-3 control-label">国籍：</label>
                        <div class="col-md-9">
                            <input type="text" name="nationality" value="中国" id="nationality" class="form-control"/>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <label form="nation" class="col-md-3 control-label">民族：</label>
                        <div class="col-md-9">
                            <input type="text" name="nation" value="汉族" id="nation" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="card_category" class="col-md-3 control-label">证件类型：</label>
                        <div class="col-md-9">
                            <select class="form-control" name="card_category" id="card_category">
                                @foreach($cardCategoryList as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <label for="card_code" class="col-md-3 control-label">证件号码：<span style="color:#f00">*</span></label>
                        <div class="col-md-9">
                            <input value="" type="text" name="card_code" id="card_code" class="form-control"
                             data-bv-notempty data-bv-notempty-message="请输入证件号码" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="marital_status" class="col-md-3 control-label">婚姻状况：</label>
                        <div class="col-md-9">
                            <select name="marital_status" id="marital_status" class="form-control">
                                @foreach($maritalStatusList as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="birthday" class="col-md-3 control-label">出生日期：</label>
                        <div class="col-md-9">
                            <input value="" type="text" name="birthday" id="birthday" class="form-control" placeholder="格式：1990-01-01或1990-01-01 00:00:00"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="culture_degree" class="col-md-3 control-label">学历</label>
                        <div class="col-md-9">
                            <select name="culture_degree" id="culture_degree" class="form-control">
                                @foreach($cultureDegreeList as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-3 control-label">联系方式：</label>
                        <div class="col-md-9">
                            <input type="text" id="contact_phone" name="contact_phone" class="form-control"/>
                        </div>
                    </div>
                </div>

                <!--  户籍信息  -->
                <div class="form-title clear-fix">户籍信息</div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_fixed">人口状态：</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" checked name="is_fixed" value="1"/>
                                    <span class="text">固定人口</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_fixed" value="2"/>
                                    <span class="text">流动人口</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_fixed" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="family_type" class="col-md-3 control-label">户籍类型：</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="family_type" value="城镇"/>
                                    <span class="text">城镇&nbsp;&nbsp;</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="family_type" value='农村'/>
                                    <span class="text">农村</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_householder">是否为户主:<span style="color:#f00">*</span></label>
                        <div class="col-md-4">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_householder" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_householder" value="0"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                        </div>
                        <span class="control-label" style="color:#ccc;position:relative;top:7px;">若非户主，则需绑定户主</span>
                    </div>
                </div>

                <div class='row bind_householder'>
                    <div class="col-md-6">
                        <label class="control-label col-md-3">绑定户主：<span style="color:#f00">*</span></label>
                        <div class="col-md-9">
                            <input value="" type="text" class="form-control" id="householder_id" name="householder_id" placeholder="输入户主身份证号进行绑定"/>
                        </div>
                    </div>
                    <div class="col-md-6"><!--绑定户主的ajax提示信息-->
                        <label class="control-label" id="bind_householder_tips"></label>
                    </div>
                </div>

                <div class='row bind_householder'>
                    <div class="col-md-6">
                        <label class="control-label col-md-3">与户主关系：</label>
                        <div class="col-md-9">
                            <input value="" type="text" class="form-control" name="relation" placeholder="请输入与户主关系"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label col-md-1" style="margin-left:35px;">居住地:<span style="color:#f00">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control" name="domicile_province" id="domicile_province">
                                <option value="河南省" selected selected style="display:none">河南省</option>
                                @foreach($location['provinces'] as $p)
                                    <option data-id="{{ $p->id }}" value="{{ $p->name }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="domicile_city" id="domicile_city">
                                <option value="新乡市" selected style="display:none">新乡市</option>
                                @foreach($location['citys'] as $c)
                                    <option data-id="{{ $c->id }}" value="{{ $c->name }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="domicile_district" id="domicile_district">
                                <option value="牧野区" selected style="display:none">牧野区</option>
                                @foreach($location['districts'] as $d)
                                    <option value="{{ $d->name }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" name="domicile_address" id="domicile_address" placeholder="请输入街道信息"
                                data-bv-notempty data-bv-notempty-message="请填写街道信息" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label" for="family_province" style="float:left;width:120px;text-align:center;">户籍所在地：</label>
                        <div class="col-md-2">
                            <select class="form-control" id="family_province" name="family_province">
                                <option value="河南省" selected style="display:none">河南省</option>
                                @foreach($location['provinces'] as $p)
                                    <option data-id="{{ $p->id }}" value="{{ $p->name }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="family_city" name="family_city">
                                <option value="新乡市" selected style="display:none">新乡市</option>
                                @foreach($location['citys'] as $c)
                                    <option data-id="{{ $c->id }}" value="{{ $c->name }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="family_district" name="family_district">
                                <option value="牧野区" selected style="display:none">牧野区</option>
                                @foreach($location['districts'] as $d)
                                    <option value="{{ $d->name }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" id="family_address" name="family_address" placeholder="街道信息"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <label class="control-label col-md-2" for="contact_address">联系地址：</label>
                        <div class="col-md-10">
                            <input class="form-control" name="contact_address" id="contact_address"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="contact_postcode">邮政编码：</label>
                        <div class="col-md-9">
                            <input class="form-control" name="contact_postcode" id="contact_postcode"/>
                        </div>
                    </div>

                </div>

                <!--补充信息-->
                <div class="form-title">补充信息</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3">是否特殊人群:</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_special" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="is_special" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_special" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_emphases">是否重点人群:</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_emphases" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="is_emphases" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_emphases" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_allowance">是否低保人群:</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_allowance" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="is_allowance" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_allowance" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_invalidism">是否伤残人群:</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_invalidism" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="is_invalidism" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_invalidism" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_older">是否老龄人:</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_older" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="is_older" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_older" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="is_veteran">是否退伍军人:</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_veteran" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input checked type="radio" name="is_veteran" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="is_veteran" value="0"/>
                                    <span class="text">不确定</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <label class="control-label col-md-2" for="memo">备注：</label>
                        <div class="col-md-9">
                            <input class="form-control" id="memo" name="memo" placeholder="请输入备注信息"/>
                        </div>
                    </div>
                </div>

                <div class="form-title">网格信息</div>
                <div class="row">
                    <div class="col-md-1">
                        <label class="control-label">所属网格:</label>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_1" class="form-control" id="grid_1">
                            <option value="">==一级网格==</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_2" class="form-control" id="grid_2">
                            <option value="">==二级网格==</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_3" class="form-control" id="grid_3">
                            <option value="">==三级网格==</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_4" class="form-control" id="grid_4">
                            <option value="">==四级网格==</option>
                        </select>
                    </div>
                </div>

                <div class="form-title">特征标签</div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label col-md-1" style="width: 12%;" for="memo">性格特征：</label>
                        <div class="col-md-10">
                            <input type="text" name="label" data-role="tagsinput" style="display: none;">
                            <span class="hint">提示：个性标签间以空格分割</span>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:50px;">
                    <div class="col-md-1 col-md-offset-5">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fa fa-save"></i>保存
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="assets/js/tagsinput/bootstrap-tagsinput.js"></script>
    <script src="assets/js/validation/bootstrapValidator.js"></script>
    <script src="ui_resource/js/data_population_add.js"></script>
    <script src="ui_resource/js/data_select_grid.js"></script>

@endsection