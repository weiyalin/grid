@extends('layout')

@section('content')
    <link href="assets/css/daterangepicker.css" rel="stylesheet" />
    <style>
        .right-align{
            text-align: right;
        }
    </style>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">常驻人口管理</span>
            <div class="widget-buttons">
                <a href="#" data-toggle="maximize">
                    <i class="fa fa-expand"></i>
                </a>
                <a href="#" data-toggle="collapse">
                    <i class="fa fa-minus"></i>
                </a>
                {{--<a href="#" data-toggle="dispose">--}}
                {{--<i class="fa fa-times"></i>--}}
                {{--</a>--}}
            </div><!--Widget Buttons-->
        </div><!--Widget Header-->
        <div class="widget-body">
            <div id="horizontal-form">
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">一级网格</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="" value="{{$persion->grid_1}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">二级网格</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id_card" placeholder="" value="{{$persion->grid_2}}">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">三级网格</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="" value="{{$persion->grid_3}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">四级网格</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id_card" placeholder="" value="{{$persion->grid_4}}">

                            </div>
                        </div>
                    </div>
                </div>



                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">姓名</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="姓名" value="{{$persion->name}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">身份证号</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id_card" placeholder="身份证号" value="{{$persion->card_code}}">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">国籍</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="国籍" value="{{$persion->nationality}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">出生日期</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id_card" placeholder="身份证号" value="{{$persion->birthday}}">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">民族</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="" value="{{$persion->nation}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">户籍类型</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id_card" placeholder="" value="{{$persion->family_type}}">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">联系电话</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="contact_phone" placeholder="国籍" value="{{$persion->contact_phone}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3 right-align">
                                <label for="name" class="margin-top-10">联系地址</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="contact_address" placeholder="身份证号" value="{{$persion->contact_address}}">

                            </div>
                        </div>
                    </div>
                </div>


                <br/>
                <div class="row margin-top-10 margin-top-10 margin-bottom-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-3">

                            </div>
                            <div class="col-sm-3">
                                <button id="btnQuery" type="button" class="btn btn-success" onclick="select_change()">保存</button>
                            </div>
                            <div class="col-sm-6">
                                <button id="btnReturn" type="button" class="btn btn-success" onclick="javascript:location.href='/data_resident_population'">返回</button>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>

                <br/>
                <hr/>
                    <h3>家庭成员列表</h3>
                <hr/>

                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>姓名</th>
                        <th>户主关系</th>
                        <th>年龄</th>
                        <th>文化程度</th>
                        <th>婚姻状况</th>
                        <th>民族</th>
                        <th>特征</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($family as $p)
                            <tr>
                                <td>{{$p->id}}</td>
                                <td>{{$p->name}}</td>
                                <td>{{$p->relation}}</td>
                                <td>{{$p->age}}</td>
                                <td>{{$p->culture_degree}}</td>
                                <td>{{$p->marital_status}}</td>
                                <td>{{$p->nation}}</td>
                                <th>{{$p->content}}</th>

                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>

        </div><!--Widget Body-->
    </div><!--Widget-->

    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    {{--<script src="assets/js/datetime/bootstrap-datepicker.js"></script>--}}

    {{--<script src="admin/event_query.js"></script>--}}


    <script type="text/javascript">

    </script>
@endsection