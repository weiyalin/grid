@extends('layout')

@section('content')
    <script src="assets/js/datetime/moment.min.js"></script>


    <ul class="timeline">
        <li class="timeline-node">
            <a class="btn btn-palegreen">NOW</a>
        </li>
        @foreach($event_logs as $log)
            @if($log->done_time)

            @endif
            @if($log->get_time)

            @endif
            <li class="{{$log->li_class}}">
                <div class="timeline-datetime">
                                <span class="timeline-time">
                                    {{date('H:i',$log->create_time/1000)}}
                                </span><span class="timeline-date">{{date('Y-m-d',$log->create_time/1000)}}</span>
                </div>
                <div class="timeline-badge {{$log->color}}">
                    <i class="{{$log->fa}}"></i>
                </div>
                <div class="timeline-panel">
                    <div class="timeline-header bordered-bottom bordered-blue">
                                    <span class="timeline-title">
                                        {{$log->from_org_name}}-{{$log->from_user_name}}
                                    </span>
                        <p class="timeline-datetime">
                            <small class="text-muted">
                                <i class="glyphicon glyphicon-time">
                                </i>
                                <span class="timeline-date">{{date('Y-m-d',$log->create_time/1000)}}</span>
                                -
                                <span class="timeline-time">{{date('H:i',$log->create_time/1000)}}</span>
                            </small>
                        </p>
                    </div>
                    <div class="timeline-body">
                        <p>{{$log->action}}&nbsp;&nbsp;{{$log->status ==1 ? '给 '.$log->to_org_name : ''}}</p>
                    </div>
                </div>
            </li>
        @endforeach


        <li class="timeline-node">
            <a class="btn btn-info">事件开始</a>
        </li>
    </ul>
@endsection