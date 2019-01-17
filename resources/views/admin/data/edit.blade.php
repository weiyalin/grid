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
            <span class="widget-caption">编辑页面</span>
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
                <a href="{{ $_GET['from'] }}" class="btn btn-default btn-back">
                    <i class="fa fa-chevron-left"></i> 返回
                </a>
            </div>

            <form id="population_info" class="form-horizontal" style="padding:20px;">
                <input type="hidden" name="edit" value="1"/>
                <input type="hidden" name="id" value="{{ $person->id }}"/>
                <!--  基本信息  -->
                <div class="form-title">基本信息</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3">姓名：<span style="color:#f00">*</span></label>
                        <div class="col-md-9">
                            <input value="{{ $person->name }}" type="text" name="name" id="name" class="form-control"
                                   data-bv-notempty data-bv-notempty-message="请输入姓名"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label col-md-3">性别：</label>
                        <div class="col-md-9">
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->sex==1 ? 'checked':null }} type="radio" name="sex" value="1"/>
                                    <span class="text">男</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->sex==2? 'checked':null }} type="radio" name="sex" value="2"/>
                                    <span class="text">女</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->sex==0?'checked':null }} type="radio" name="sex" value="0"/>
                                    <span class="text">未知</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->sex==9?'checked':null }} type="radio" name="sex" value="9"/>
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
                            <input type="text" name="nationality" value="{{ $person->nationality }}" id="nationality" class="form-control"/>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <label form="nation" class="col-md-3 control-label">民族：</label>
                        <div class="col-md-9">
                            <input type="text" name="nation" value="{{ $person->nation }}" id="nation" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="card_category" class="col-md-3 control-label">证件类型：</label>
                        <div class="col-md-9">
                            <select class="form-control" name="card_category" id="card_category">
                                @foreach($cardCategoryList as $k=>$v)
                                    <option {{ $person->card_category==$k?'selected':null }} value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <label for="card_code" class="col-md-3 control-label">证件号码：<span style="color:#f00">*</span></label>
                        <div class="col-md-9">
                            <input value="{{ $person->card_code }}" type="text" name="card_code" id="card_code" class="form-control"
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
                                    <option {{ $person->marital_status==$k?'selected':null }} value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="birthday" class="col-md-3 control-label">出生日期：</label>
                        <div class="col-md-9">
                            <input value="{{ $person->birthday }}" type="text" name="birthday" id="birthday" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="culture_degree" class="col-md-3 control-label">学历</label>
                        <div class="col-md-9">
                            <select name="culture_degree" id="culture_degree" class="form-control">
                                @foreach($cultureDegreeList as $k=>$v)
                                    <option {{ $person->culture_degree==$k?'selected':null }} value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-3 control-label">联系方式</label>
                        <div class="col-md-9">
                            <input value="{{ $person->contact_phone }}" type="text" id="contact_phone" name="contact_phone" class="form-control"/>
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
                                        <input {{ $person->is_fixed==1?'checked':null }} type="radio" checked name="is_fixed" value="1"/>
                                        <span class="text">固定人口</span>
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input {{ $person->is_fixed==2?'checked':null }} type="radio" name="is_fixed" value="2"/>
                                        <span class="text">流动人口</span>
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input {{ $person->is_fixed==0?'checked':null }} type="radio" name="is_fixed" value="0"/>
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
                                    <input {{ $person->family_type=="城镇"?'checked':null }} checked type="radio" name="family_type" value="城镇"/>
                                    <span class="text">城镇&nbsp;&nbsp;</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->family_type=="农村"?'checked':null }} type="radio" name="family_type" value='农村'/>
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
                            @if($person->is_householder)
                                <div class="radio-inline">
                                    <label>
                                        <input checked type="radio" name="is_householder" value="1"/>
                                        <span class="text">是</span>
                                    </label>
                                </div>
                            @else
                                <div class="radio-inline">
                                    <label>
                                        <input checked type="radio" name="is_householder" value="0"/>
                                        <span class="text">否</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div id="edit_house_holder" class=" col-md-2 btn btn-primary" title="编辑户主状态或更改绑定户主,点击此按钮进行编辑">
                            编辑
                        </div>
                    </div>
                </div>

                @if(!$person->is_householder)
                    {{--如果是户主，则不需要显示此部分--}}
                <div class='row bind_householder'>
                    <div class="col-md-6">
                        <label class="control-label col-md-3">绑定户主：<span style="color:#f00">*</span></label>
                        <div class="col-md-9">
                            <input readonly disabled value="{{ $person->householder_id }}" type="text" class="form-control" id="householder_id" name="householder_id" placeholder="输入户主身份证号进行绑定"/>
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
                            <input value="{{ $person->relation }}" type="text" class="form-control" name="relation" placeholder="请输入与户主关系"/>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label col-md-1" style="margin-left:35px;">居住地:<span style="color:#f00">*</span></label>
                        <div class="col-md-2">
                            <select class="form-control" name="domicile_province" id="domicile_province">
                                <option value="{{ $person->domicile_province }}"  selected style="display:none">{{ $person->domicile_province }}</option>
                                @foreach($provinces as $p)
                                    <option data-id="{{ $p->id }}" value="{{ $p->name }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="domicile_city" id="domicile_city">
                                <option value="{{ $person->domicile_city }}" selected style="display:none">{{ $person->domicile_city }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="domicile_district" id="domicile_district">
                                <option value="{{ $person->domicile_district }}" selected style="display:none">{{ $person->domicile_district }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input value="{{ $person->domicile_address }}" class="form-control" name="domicile_address" id="domicile_address" placeholder="请输入街道信息"
                                   data-bv-notempty data-bv-notempty-message="请填写街道信息" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label" for="family_province" style="float:left;width:120px;text-align:center;">户籍所在地：</label>
                        <div class="col-md-2">
                            <select class="form-control" id="family_province" name="family_province">
                                <option value="{{ $person->family_province }}" selected style="display:none">{{ $person->family_province }}</option>
                                @foreach($provinces as $p)
                                    <option data-id="{{ $p->id }}" value="{{ $p->name }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="family_city" name="family_city">
                                <option value="{{ $person->family_city }}" selected style="display:none">{{ $person->family_city }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="family_district" name="family_district">
                                <option value="{{ $person->family_district }}" selected style="display:none">{{ $person->family_district }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input value="{{ $person->family_address }}" class="form-control" id="family_address" name="family_address" placeholder="街道信息"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <label class="control-label col-md-2" for="contact_address">联系地址：</label>
                        <div class="col-md-10">
                            <input value="{{ $person->contact_address }}" class="form-control" name="contact_address" id="contact_address"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label col-md-3" for="contact_postcode">邮政编码：</label>
                        <div class="col-md-9">
                            <input value="{{ $person->contact_postcode }}" class="form-control" name="contact_postcode" id="contact_postcode"/>
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
                                    <input {{ $person->is_special==1?'checked':null }} type="radio" name="is_special" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_special==2?'checked':null }} type="radio" name="is_special" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_special==0?'checked':null }} type="radio" name="is_special" value="0"/>
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
                                    <input {{ $person->is_emphases==1?'checked':null }} type="radio" name="is_emphases" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_emphases==2?'checked':null }} type="radio" name="is_emphases" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_emphases==0?'checked':null }} type="radio" name="is_emphases" value="0"/>
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
                                    <input {{ $person->is_allowance==1?'checked':null }} type="radio" name="is_allowance" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_allowance==2?'checked':null }} type="radio" name="is_allowance" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_allowance==0?'checked':null }} type="radio" name="is_allowance" value="0"/>
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
                                    <input {{ $person->is_invalidism==1?'checked':null }} type="radio" name="is_invalidism" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_invalidism==2?'checked':null }} checked type="radio" name="is_invalidism" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_invalidism==0?'checked':null }} type="radio" name="is_invalidism" value="0"/>
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
                                    <input {{ $person->is_older==1?'checked':null }} type="radio" name="is_older" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_older==2?'checked':null }} type="radio" name="is_older" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_older==0?'checked':null }} type="radio" name="is_older" value="0"/>
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
                                    <input {{ $person->is_veteran==1?'checked':null }} type="radio" name="is_veteran" value="1"/>
                                    <span class="text">是</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_veteran==2?'checked':null }} type="radio" name="is_veteran" value="2"/>
                                    <span class="text">否</span>
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input {{ $person->is_veteran==0?'checked':null }} type="radio" name="is_veteran" value="0"/>
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
                            <input value='{{ $person->memo }}' class="form-control" id="memo" name="memo" placeholder="请输入备注信息"/>
                        </div>
                    </div>
                </div>

                <div class="form-title">网格信息</div>
                <div class="row">
                    <div class="col-md-1">
                        <label class="control-label">所属网格:</label>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_1" data="{{ $person->grid_1 }}" class="form-control" id="grid_1">
                            <option style="display:none;">请选择...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_2" data="{{ $person->grid_2 }}" class="form-control" id="grid_2">
                            <option style="display:none;">请选择...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_3" data="{{ $person->grid_3 }}" class="form-control" id="grid_3">
                            <option style="display:none;">请选择...</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="grid_4" data="{{ $person->grid_4 }}" class="form-control" id="grid_4">
                            <option style="display:none;">请选择...</option>
                        </select>
                    </div>
                </div>

                <div class="form-title">特征标签</div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label col-md-1" style="width: 12%;" for="memo">性格特征：</label>
                        <div class="col-md-10">
                            {{--@if(empty($label_data))--}}
                                {{--<input type="text" name="label" data-role="tagsinput" style="display: none;">--}}
                            {{--@else--}}
                                <input type="text" value="{{ $label_data }}" name="label" data-role="tagsinput" style="display: none;">
                            {{--@endif--}}
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


    <!--不是户主，显示不是户主的编辑页面（弹出页）-->
    <div class="myModal" style="display:none">
        <div class="row">
            <label class="control-label col-sm-3">是否为户主</label>
            <div class='col-sm-8'>
                <div class="radio-inline is_householder">
                    <label>
                        <input type="radio" value="1" onclick="hide()" name="is_householder"/>
                        <span class="text">是</span>
                    </label>
                </div>
                <div class="radio-inline">
                    <label>
                        <input type="radio" onclick="show()" checked value="0" name="is_householder"/>
                        <span class="text">否</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="row householder_id">
            <label class="control-label col-sm-3">绑定户主</label>
            <div class="col-sm-8">
                <input type="text" name="householder_id" class='form-control' placeholder="不是户主时,需填写 被绑定户主 的身份证号"/>
            </div>
        </div>
        <div class="row householder_id">
            <label class="control-label col-sm-3">与户主关系</label>
            <div class="col-sm-8">
                <input type="text" name="relation" class="form-control"/>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="assets/js/tagsinput/bootstrap-tagsinput.js"></script>
    <script src="assets/js/validation/bootstrapValidator.js"></script>
    <script src="ui_resource/js/data_select_grid.js"></script>
    <script>
        var saveUrl = '/data_population_save';

        //如果证件号码是身份证，则自动生成出生日期和年龄
        $('#card_code').blur(function(){
            var card_category = $('#card_category').val();
            if(card_category != 01){
                return ;
            }
            var card_code = $(this).val();              //获取身份证号
            var birthday_y = card_code.substring(6,10);   //获取生日 --年
            var birthday_m = card_code.substring(10,12);   //获取生日 --月
            var birthday_d = card_code.substring(12,14);   //获取生日 --日
            if(birthday_d){
                $('#birthday').val(birthday_y+'-'+birthday_m+'-'+birthday_d+' 00:00:00');
            }
        })

        //省市区联动
        $('#domicile_province option').click(function (){
            address_getNext(this,'#domicile_city',1);
        });
        $('#domicile_city option').click(function(){
            address_getNext(this,'#domicile_district',2);
        })
        $('#family_province option').click(function (){
            address_getNext(this,'#family_city',1);
        });
        $('#family_city option').click(function(){
            address_getNext(this,'#family_district',2);
        })

        //获取下一级地区信息
        function address_getNext(obj,sId,level){  //obj:点击对象 sId:子id,指定把信息放入哪个子ID level:1/市 2/区
            var pId = $(obj).data('id');
            if(level == 1){
                var data = {province_id:pId};
                var url = '/get_city';
            }else if(level == 2){
                var data = {city_id:pId}
                var url = '/get_district';
            }

            var str = '';
            $.get(
                    url,
                    data,
                    function(data){
                        for(var i in data){
                            str += "<option data-id='"+data[i].id+"' value='"+data[i].name+"' >"+data[i].name+"</option>";
                        }
                        $(sId).empty().html(str);
                        //重新赋予下一级的点击事件
                        if(level == 1){
                            $('#domicile_city option').click(function(){
                                address_getNext(this,'#domicile_district',2);
                            })
                            $('#family_city option').click(function(){
                                address_getNext(this,'#family_district',2);
                            })
                        }
                    },
                    'json'
            );
        }

        //表格数据验证
        $('#population_info').bootstrapValidator({
            fields: {
                householder_id: {
                    validators: {
                        callback : function(value,validator){
                            var householder_id = $('#householder_id').val();
                            if(householder_id.length !=15 || householder_id.length != 18){
                                return false;
                            }
                        }
                    }
                },
            },
            submitHandler   : function(validator,form,submitButton){
                if(false){
                    //可以在这里判断户主信息
                    return;
                }
                $.ajax({
                    url     : saveUrl,
                    dataType: 'json',
                    data    : form.serialize(),
                    method  : 'post',
                    success : function(data){
                        if(data.code ==0){
                            Notify(data.msg,'top-right','5000','success','fa-check',true);
                            location.href='/data_population';
                        }else{
                            Notify(data.msg,'top-right','5000','danger','fa-check',true);
                        }
                    },
                    error   : function(data){
                        if(data.status == 422){     				//laravel自带验证器返回状态吗422的错误信息
                            msg = eval("("+data.responseText+")");  //把错误信息转化为json
                            for(var i in msg){                      //键不确定，所以用此方法，一般只返回一条信息
                                msg[i]	//即为返回的消息；i不固定，为出错字段的字段值
                                Notify('存在无效数据，请重新检查数据<br/>'+msg[i], 'top-right', '5000', 'warning', 'fa-warning', true);
                            }
                        }else{
                            alert('something wrong, please try again!')
                        }
                    }
                })
            }
        });

        //编辑户主信息
        $('#edit_house_holder').click(function(){
            bootbox.dialog({
                message     : $('.myModal').html(),
                title       : '编辑户主状态/信息',
                buttons     : {
                    success : {
                        label : '确定',
                        className : 'btn-blue',
                        callback : function(){
                            changeHouseHolderInfo();
                        },
                        '取消' : {
                            className : 'btn-blue',
                            callback :function(){}
                        }
                    }
                }
            })
        })
        function hide(){
            $('.householder_id').fadeOut();
        }
        function show(){
            $('.householder_id').fadeIn();
        }
        //更改户主信息
        function changeHouseHolderInfo(){
            var bootboxObj = $('.bootbox-body');
            var id = $('input[name=id]').val();
            var is_householder = bootboxObj.find('input[name=is_householder]:checked').val();
            var householder_id = bootboxObj.find('input[name=householder_id]').val();
            var relation = bootboxObj.find('input[name=relation]').val();
            $.ajax({
                url     : '/data_population_householder_edit',
                method  : 'post',
                dataType: 'json',
                data    : {id:id,is_householder:is_householder,householder_id:householder_id,relation:relation},
                success : function(data){
                    if(data.code == 0){
                        Notify(data.msg, 'top-right', '5000', 'success', 'fa-check', true);
                        $('.btn-back').trigger('click');
                    }else{
                        Notify(data.msg, 'top-right', '5000', 'danger', 'fa-check', true);
                    }
                },
                error   : function(){
                    alert('something wrong,please try again');
                }
            })
        }
    </script>
@endsection