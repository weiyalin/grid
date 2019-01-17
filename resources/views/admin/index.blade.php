@extends('layout')

@section('content')
    {{--<iframe height="760" width="840" src = "http://www.iermu.com/video/42f904d242fd6e358a490f97a2013f23/2050712015"  frameborder=0 allowfullscreen></iframe>--}}

    <iframe src="/index_test" id="iframepage" height="800px" width="1000px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" onLoad="iFrameHeight()">

    </iframe>

    <script type="text/javascript" language="javascript">
        function iFrameHeight() {
            return ;
            var ifm= document.getElementById("iframepage");
            var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
            if(ifm != null && subWeb != null) {
                ifm.height = subWeb.body.scrollHeight;
                ifm.width = subWeb.body.scrollWidth;
            }
        }
    </script>
@endsection