@extends('layout')
@section('content')
    <link rel="stylesheet" href="assets/css/daterangepicker.css"/>
    <div class="widget">
        <div class="widget-header bg-default">
            {{--<i class="widget-icon"></i>--}}
            <span class="widget-caption">事件统计</span>
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
            <div class="row">
                <div class="col-sm-5">
                    <label for="name" class="control-label col-sm-2 text-right padding-top-5">日期</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input class="form-control event_date" id="completion_date" type="text">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary" id="search">检索</button>
                    <button class="btn btn-warning" id="reset">重置</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="event_stat" style="width:100%;height:400px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="libs/echarts/echarts.min.js"></script>
    <script src="assets/js/datetime/jquery.daterangepicker.js"></script>
    <script>
        $(function() {
            var event_stat = echarts.init(document.getElementById('event_stat'));
            option = {
                title: {text: ''},
                tooltip: {},
                xAxis: {data: ['交办数', '办理中', '办结待审核', '已办结', '挂起数']},
                yAxis: {name: '事件数'},
                series: [{type: 'bar', data: []}],
                color: ['#c23513']
            }
            event_stat.setOption(option, true);
            get_data();
            function get_data(){
                var date = $('#completion_date').val();
                var data = {date:date};
                $.ajax({
                    url     : '/job_event_stat_data',
                    data    : data,
                    dataType: 'json',
                    success: function (data) {
                        event_stat.setOption({
                            title : {text:data.date},
                            series: {data: [data.total, data.recevie, data.review, data.done, data.guaqi]}
                        })
                    }
                })
            }

            //日期
            $('#completion_date').dateRangePicker({
                showShortcuts: true,
                shortcuts: {
                    'prev': ['month']
                },
                showWeekNumbers: true,
                startOfWeek: 'monday',
                separator: '--',
            })

            //检索
            $('#search').click(function(){
                get_data();
            })
            //重置
            $('#reset').click(function(){
                $('#completion_date').val('')
            })
        })
    </script>
@endsection