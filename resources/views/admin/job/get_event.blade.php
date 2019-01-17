
@extends('layout')
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
            <div id="horizontal-form">
                <table class="table table-striped table-bordered table-hover" id="simpledatatable">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>事件标题</th>
                        <th>事件地址</th>
                        <th>上报时间</th>
                        <th>事件来源</th>
                        <th>领取时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div><!--Widget body-->

    </div>
    <div style="display:none;" id="event_down_box">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name='memo' required rows="5" class="form-control event_result"></textarea>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script src="assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="assets/js/datatable/dataTables.bootstrap.min.js"></script>
    {{--<script src="assets/js/datetime/bootstrap-datepicker.js"></script>--}}
            <!--弹出框的两个js文件-->
    <script src="ui_resource/js/bootbox.js"></script>
    <script src="ui_resource/js/functions.js"></script>
    <script src="ui_resource/js/get_event.js"></script>
@endsection