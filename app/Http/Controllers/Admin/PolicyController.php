<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class PolicyController extends Controller
{
    /**
     * 决策首页
     */
    public function index(){
        return view('admin.policy.index')
            ->with('title','决策中心')
            ->with('homeNav','决策中心')
            ->with('homeLink','/policy_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }


    /**
     * 总体统计 -- 展示页
     */
    public function stat(){
        $depart = DB::table('event_department')->get();
        return view('admin.policy.stat')
            ->with('title','决策中心')
            ->with('homeNav','决策中心')
            ->with('homeLink','/policy_stat')
            ->with('subNav','')
            ->with('activeNav','总体统计')
            ->with('menus',getMenus())
            ->with('depart',$depart);
    }

    /**
     * 业务统计 ajax获取数据
     * $data[1] 为图一数据
     * $data[2] 为图二数据   因为图二的存在，导致每一次sql查询不一样，所以每种情况单独写开
     */
    public function stat_event_statistics(Request $request){
        $time = intval($request->input('time'));        //时间范围：0总体 1近一年 2本月 3本周 4当天
        $depart = intval($request->input('depart'));    //0：所有部门；1城管 2民政 3维稳 4环保 5安全生产 6食药监 7卫计委
        $departName = ['所有部门','城管','民政','维稳','环保','安全生产','食药监','卫计委'];
        //DB::connection()->enableQueryLog();
        $query = DB::table('event')->where('status','!=',6);    //非删除的所有数据
        if($depart){
            $code_list = DB::table("event_category")->where('department_id',$depart)->lists('code');
            //获取所有下级code
            $category_code_list = [];
            foreach($code_list as $code){
                $list_code = DB::table('event_category')->where('code','like',"$code%")->lists('code');
                $category_code_list = array_merge($category_code_list,$list_code);
            }
            $event_category_code_list = array_unique($category_code_list);
            $query->whereIn('event_category_code',$event_category_code_list);
        }

        switch($time){
            case 0 :
                $data[1]['title'] = '总体情况('.$departName[$depart].')';
                $res = $query->select(DB::raw('COUNT(*) as count,`status`,FROM_UNIXTIME(`create_time`/1000,"%Y年%m月") as date,FROM_UNIXTIME(`create_time`/1000,"%Y") as year'))->groupBy('status')->groupBy('year')->orderBy('create_time','asc')->get();
                if(!$res){
                    $data[1]['total'] = 0;
                    $data[2]['x'] = '';
                    $data[2]['total'][] = 0;
                    $data[2]['done'][] = 0;
                    break;
                }
                //dd($res);
                // 图一数据
                $data[1]['total'] = 0;
                $data[1]['done'] = 0;
                foreach($res as $v){
                    $v->status==4 ? $data[1]['done'] += $v->count : '';
                    $data[1]['total'] += $v->count;
                }
                //图二数据
                    //组装x轴数据
                /*
                 * $year_min = intval($res[0]->year);      //因为已经按照时间排列，所以第一个最小，最后一个最大
                $year_max = intval($res[count($res)-1]->year);
                for($i=$year_min;$i<=$year_max;$i++){
                    $data[2]['x'][$i] = $i.'年';
                    $data[2]['total'][$i] = 0;
                    $data[2]['done'][$i] = 0;
                }*/

                foreach($res as $v){
                    $data[2]['x'][$v->year] = $v->date;
                    //初始化y数据
                    $data[2]['total'][$v->year] = 0;
                    $data[2]['done'][$v->year] = 0;
                }
                    //组装y轴数据
                foreach($res as $v){
                    $year = intval($v->year);
                    $data[2]['total'][$year] += $v->count;
                    $v->status==4 ? $data[2]['done'][$year]+=$v->count : null;
                }
                break;
            case 1 :
                // 1. 组装查询条件
                $data[1]['title'] = '近一年('.$departName[$depart].')';
                $month_start = date('m',strtotime('-1 year +1 month')); //例如：从去年8月到今年7月
                $year_start = date('y',strtotime('-1 year +1 month'));  //
                $query->whereBetween('create_time',[strtotime($year_start.'-'.$month_start.'-01')*1000,time()*1000]);
                //dd(date('ymd',strtotime($year_start.'-'.$month_start.'-01')));  //从去年的8月1号开始（比如今年7月开始查看）
                // 2. 查询
                $res = $query->select(DB::raw('COUNT(*) as count,`status`,FROM_UNIXTIME(`create_time`/1000,"%y%m") as month'))->groupBy('status')->groupBy('month')->orderBy('create_time','asc')->get();
                if(!$res){
                    $data[1]['total'] = 0;
                    $data[2]['x'] = '';
                    $data[2]['total'][] = 0;
                    $data[2]['done'][] = 0;
                    break;
                }
                //dd(date('ymd',strtotime('-1 year +1 month')));
                //dd($res);
                // 3.1 图一数据
                $data[1]['total'] = 0;
                $data[1]['done'] = 0;
                foreach($res as $v){
                    $v->status==4 ? $data[1]['done'] += $v->count : '';
                    $data[1]['total'] += $v->count;
                }
                // 3.2 图二数据
                //$lastYearTimeStamp = strtotime('-1 year');
                //$month_start = intval(date('m',$lastYearTimeStamp));    //上一年的本月
                //dd($month_start);
                //$year_start = date('y',$lastYearTimeStamp);     //上一年的年份
                    //x轴数据
                $months = [1=>'01','02','03','04','05','06','07','08','09','10','11','12'];
                $index = intval($month_start)-1;    //循环取值时会先加1，所以这里先减一
                for($i=0;$i<12;$i++){
                   //$data[2]['x'][] = date('ym',strtotime('+'.$i.' month',$lastYearTimeStamp));
                    if($index < 12) {
                        $index += 1;
                    }else{
                        $index = $index - 11;
                        $year_start += 1;
                    }
                    $data[2]['x'][] = $year_start.'年'.$months[$index].'月';
                    //初始化y轴数据
                    $data[2]['total'][$year_start.$months[$index]] = 0;
                    $data[2]['done'][$year_start.$months[$index]] = 0;
                }
                    //组装y轴数据
                foreach($res as $v){
                    $v->status==4 ? $data[2]['done'][$v->month] += $v->count:'';
                    $data[2]['total'][$v->month] += $v->count;
                }
                //数据来源标识符
                $data['time'] = 1;
                //dd($data[2]);
                break;
            case 2 :
                // 1.组装查询条件
                $data[1]['title'] = '本月('.$departName[$depart].')';
                $month = date('Y-m-');
                $month_firstDay = strtotime($month.'1');    //本月第一天
                $query->whereBetween('create_time',[$month_firstDay*1000,time()*1000]);
                // 2. 查询
                $res = $query->select(DB::raw('COUNT(*) as count,`status`,FROM_UNIXTIME(`create_time`/1000,"%d") as day'))->groupBy('status')->groupBy('day')->orderBy('create_time','asc')->get();
                if(!$res){
                    $data[1]['total'] = 0;
                    $data[2]['x'] = '';
                    $data[2]['total'][] = 0;
                    $data[2]['done'][] = 0;
                    break;
                }
                // 3.1 图一数据
                $data[1]['total'] = 0;
                $data[1]['done'] = 0;
                foreach($res as $v){
                    $v->status==4 ? $data[1]['done'] += $v->count : '';
                    $data[1]['total'] += $v->count;
                }
                // 3.2 图二数据
                    //组装x轴数据
                $today_day = date('d',time());
                for($i=1;$i<=$today_day;$i++){
                    $data[2]['x'][$i] = $i.'日';
                    //初始化y轴键值
                    $data[2]['done'][$i] = 0;
                    $data[2]['total'][$i] = 0;
                }
                    //组装y轴数据
                foreach($res as $v){
                    $day = intval($v->day);
                    $v->status==4 ? $data[2]['done'][$day] += $v->count:'';
                    $data[2]['total'][$day] += $v->count;
                }
                //4. 数据来源标识符
                $data['time'] = 2;
                break;
            case 3 :
                // 1. 组装查询条件
                $data[1]['title'] = '本周('.$departName[$depart].')';
                $w = date('w');                     //今天周w
                $days = $w>0 ? $w-1 : 6;            //和周一差几天？
                $week_firstDay = strtotime(date('Y-m-d').' '.-$days.' days');           //本周第一天时间戳
                $query->whereBetween('create_time',[$week_firstDay*1000,time()*1000]);  //限定查询为本周
                // 2. 查询
                $res = $query->select(DB::raw('COUNT(*) as count,`status`,FROM_UNIXTIME(`create_time`/1000,"%d") as day'))->groupBy('status')->groupBy('day')->orderBy('create_time','asc')->get();
                if(!$res){
                    $data[1]['total'] = 0;
                    $data[2]['x'] = '';
                    $data[2]['total'][] = 0;
                    $data[2]['done'][] = 0;
                    break;
                }
                // 3.1 图一数据
                $data[1]['total'] = 0;
                $data[1]['done'] = 0;
                foreach($res as $v){
                    $v->status==4 ? $data[1]['done'] += $v->count : '';
                    $data[1]['total'] += $v->count;
                }
                // 3.2 图二数据
                //组装x轴日期
                for($i=0;$i<=$days;$i++){
                    //本周每天的时间戳
                    $eachDay = $week_firstDay + $i*86400;
                    $data[2]['x'][$i] =date('m月d日',$eachDay);
                    //y轴数据初始化
                    $yDay = intval(date('d',$eachDay));
                    $data[2]['done'][$yDay] = 0;
                    $data[2]['total'][$yDay] = 0;
                }
                //组装Y轴数据
                foreach($res as $v){
                    $day = intval($v->day);
                    //在x轴组装数据是，已经把所有的日期为键的空数组组建好，根据sql查到的数据，直接（用sql中的日期）调用填充
                    if($v->status==4){
                        $data[2]['done'][$day] += $v->count;
                    }
                    $data[2]['total'][$day] += $v->count;
                }
                //4. 数据来源标识符
                $data['time'] = 3;
                break;
            case 4 :
                // 1. 组装条件
                $data[1]['title'] = '当天('.$departName[$depart].')';
                $today_start = strtotime(date('Y-m-d'));
                $query->where('create_time','>',$today_start*1000);
                // 2. 查询
                $res = $query->select(DB::raw('COUNT(*) as count,status'))->groupBy('status')->get();
                // 3.1 图一数据
                $data[1]['total'] = 0;  //初始化
                $data[1]['done'] = 0;
                foreach($res as $v){
                    $v->status == 4 ? $data[1]['done'] = $v->count:'';  //状态4为办结
                    $data[1]['total'] += $v->count;
                }
                // 3.2 图二数据
                $data[2]['x'][] = date('m月d号');
                $data[2]['done'][] = $data[1]['done'];
                $data[2]['total'][] = $data[1]['total'];
                //4. 数据来源标识符
                $data['time'] = 4;
                break;
        }

        return json_encode($data);
    }

    /**
     * 事件办结率 第二行图表  ：和业务统计不同，办结率仅统计有数据的年/月/日即可
     * ajax获取数据
     */
    public function stat_event_completion_rate(Request $request){
        $time = $request->input('time');
        $depart = intval($request->input('depart'));        // 0：为全部，1-7:七大部门
        $departName = $request->input('departName');        // 0：为全部，1-7:七大部门
        $source = intval($request->input('source'));        //-1：为全部，0：指挥中心，1：网格员，2：微信用户
        $sourceName = $request->input('sourceName');
        $group = 'month';       //默认分组为‘月’
        $data[1]['title'] = '';

        $query = DB::table('event')->where('status','!=',6);

        //搜索
        if($depart ==0 && $source == -1){
            $data[1]['title']  .= '总体';
        }
        if($depart){            //部门来源，查询 事件分配部门是不是它的下级
            $data[1]['title'] .= ' 部门:'.$departName.' ';
            $code_list = DB::table("event_category")->where('department_id',$depart)->lists('code');
            //获取所有下级code
            $category_code_list = [];
            foreach($code_list as $code){
                $list_code = DB::table('event_category')->where('code','like',"$code%")->lists('code');
                $category_code_list = array_merge($category_code_list,$list_code);
            }
            $event_category_code_list = array_unique($category_code_list);
            $query->whereIn('event_category_code',$event_category_code_list);
        }
        if($source != -1){
            $query->where('source',$source);
            $data[1]['title'] .= ' 来源：'.$sourceName.' ';
        }
        if($time){
            list($start,$end) = explode('--',$time);
            $start = strtotime($start)*1000;
            $end_tmp = strtotime($end);
            $end = $end_tmp<=time() ? $end_tmp*1000 : time()*1000;       //判断一下截止时间不要在 ‘现在’之后

            $query->whereBetween('create_time',[$start,$end]);

            if(date('m',$start/1000) == date('m',$end/1000)){     //如果事件范围在同一个月，则按天分组，否则默认按月分组
                $group = 'day';
                $query->select(DB::raw('COUNT(*) as count,`status`,`create_time`,FROM_UNIXTIME(`create_time`/1000,"%d") as day'))
                ->groupBy('day');
            }else{
                $group = 'month';
                $query->select(DB::raw('COUNT(*) as count,`status`,`create_time`,FROM_UNIXTIME(`create_time`/1000,"%y%m") as month'))
                    ->groupBy('month');
            }


            $data[1]['title'] .= ' 日期:'.$time.' ';
        }else{
            $group = 'month';
            $query->select(DB::raw('COUNT(*) as count,`status`,`create_time`,FROM_UNIXTIME(`create_time`/1000,"%y%m") as month'))
                ->groupBy('month');
        }
        //默认统计全部事件的办结率

        //$query->select(DB::raw('COUNT(*) as count,`status`,`create_time`,FROM_UNIXTIME(`create_time`/1000,"%y%m") as month'));
        $res = $query->orderBy('create_time','asc')->groupBy('status')->get();
        //dd($res);
        if($res){
            $total = 0;
            $done  = 0;
            switch($group){
                case 'day' :
                    // 3.1 图一数据
                    foreach($res as $v){
                        $v->status==4 ? $done += $v->count : '';
                        $total += $v->count;
                        $data[1]['ratio'] = round($done/$total * 100,2);
                    }
                    // 3.2 图二数据
                    foreach($res as $v){
                        //图二数据
                        if(!isset($tmp[$v->day])){   //如果不存在，则第一次赋初始值，否则累加
                            $tmp[$v->day]['total'] = $v->count;   //记录总数
                            $tmp[$v->day]['done']  = ($v->status==4 ? $v->count: 0); //仅记录办结
                            //x轴下标
                            $data[2]['x'][] = date('m月d日',$v->create_time/1000);
                        }else{
                            $tmp[$v->day]['total'] += $v->count;
                            $v->status==4 ? $tmp[$v->day]['done']+=$v->count : null;
                        }
                    }
                    //计算比率
                    foreach($tmp as $v){
                        $data[2]['y'][] = round($v['done']/$v['total'] * 100 ,2) ;   //保证顺序即可，没写下标
                    }
                    break;
                case 'month' :
                    foreach($res as $v){
                        //图一数据
                        $total += $v->count;
                        $v->status==4 ? $done+=$v->count : null;
                    }
                    $data[1]['ratio'] = round($done/$total * 100,2);

                    foreach($res as $v){
                        //图二数据
                        if(!isset($tmp[$v->month])){   //如果不存在，则第一次赋初始值，否则累加
                            $tmp[$v->month]['total'] = $v->count;   //记录总数
                            $tmp[$v->month]['done']  = ($v->status==4 ? $v->count: 0); //仅记录办结
                            //x轴下标
                            $data[2]['x'][] = date('y年m月',$v->create_time/1000);
                        }else{
                            $tmp[$v->month]['total'] += $v->count;
                            $v->status==4 ? $tmp[$v->month]['done']+=$v->count : null;
                        }
                    }

                    //计算比率
                    foreach($tmp as $v){
                        $data[2]['y'][] = round($v['done']/$v['total'] * 100 ,2) ;   //保证顺序即可，没写下标
                    }
            }
        }else{
            $data[1]['ratio'] = 0;  //当结果集不存在的时候，赋一个默认值
            $data[2]['x'][] = '';
            $data[2]['y'][] = 0;
        }

        return json_encode($data);
    }

    /**
     * 事件地图分布
     *  直接默认展示第二级区域
     */
    public function map_data(Request $request){
        //搜索关键字
        $date = $request->input('date');
        $depart = intval($request->input('depart'));
        $source = intval($request->input('source'));

        //待删除
           /* if($request->input('id') == 'all'){
                $data = DB::table('grid')->where('level',4)->orWhere('parent_id',9)->orWhere('id',9)->lists('map');
                foreach($data as $k=>$v){
                    $point = explode(';',$v);
                    foreach($point as $vv){
                        $p[$k][] = explode(',',$vv);
                    }
                }
                return $p;
            }*/
        //待删除 结束
        $query = DB::table('event');
        if($date){
            list($start,$end) = $this->deal_dateRangePicker($date);
            $query->whereBetween('create_time',[$start,$end]);
        }
        if($depart != -1){
            $code_list = DB::table("event_category")->where('department_id',$depart)->lists('code');

            //获取所有下级code
            //event_category 中department_id，所有一二三级分类都填充时，此段代码可以屏蔽，如果只是一级分类填写，则需要打开
           /* $category_code_list = [];
            foreach($code_list as $code){
                $list_code = DB::table('event_category')->where('code','like',"$code%")->lists('code');
                $category_code_list = array_merge($category_code_list,$list_code);
            }
            $event_category_code_list = array_unique($category_code_list);
            dd($event_category_code_list);*/

            $query->whereIn('event_category_code',$code_list);
        }
        if($source != -1){
            $query->where('source',$source);
        }

        $parent_id = intval($request->input('id')) ? intval($request->input('id')) : 1; //父ID是1，为第二级
        $grid_list = DB::table('grid')->where('parent_id',$parent_id)->get();
        if(!$grid_list) return ;
        //获取所取的grid的level
        $level = $grid_list[0]->level;  //level 为1 按grid_1分组，level为2，按grid_2分组。。。

        //获取次级别下各个区块的事件数
        $events = $query->select(DB::raw('COUNT(*) as count,grid_'.$level.' as grid_id'))->groupBy('grid_id')->get();

        if($events){
            foreach($events as $v){
                $events_count[$v->grid_id] = $v->count;     // event['id'] => count;
            }
            unset($events);
        }
        //上面如果没有给event_count赋值，如果没有复制，说明都是0,默认给个20；
        $visualMap_max = isset($events_count)&&$events_count ? ceil(max($events_count)/10)*10 : 20;//前台用

        //组装geo坐标点
        $features = [];
        $data = [];
        foreach($grid_list as $grid){
            if(!$grid->map) continue;
            $property = new \stdClass();
            $property->name=$grid->short_name;

            //构造数组
            $map = trim($grid->map,';');
            $points = explode(";",$map);
            $coordinates = [];
            //var_dump($points);
            foreach($points as $point){
                $p = explode(",",$point);
                $coordinates[] = [floatval($p[0]),floatval($p[1])];
            }

            //构造对象
            $geometry = new \stdClass();
            $geometry->type="Polygon";
            $geometry->coordinates = [$coordinates];

            $feature = new \stdClass();
            $feature->type = "Feature";
            $feature->id=$grid->id;
            $feature->properties = $property;
            $feature->geometry = $geometry;
            $features[] = $feature;

            //此grid是否有事件数的信息，没有则value=0;
            $data_value = isset($events_count[$grid->id]) ? $events_count[$grid->id] : 0;
            $data[] = ['name'=>$grid->short_name,'value'=>$data_value,'grid_id'=>$grid->id];
        }

        $json = new \stdClass();
        $json->type="FeatureCollection";
        $json->features = $features;

        $result = ['json'=>$json,'data'=>$data,'max'=>$visualMap_max];
        //dd($result);
        return response()->json($result);
        //return response()->download(public_path().'/ui_resource/js/json/henan.json');
    }

    /**
     * 业务统计分析
     *  1 : 来源统计
     *  2 ：类型统计
     *  3 ：办事处统计
     *  4 ：职能部门统计
     *  5 ：红黄牌统计
     */
    public function statistics_analysis(Request $request){
        $type = intval($request->input('type'));
        switch($type){
            case 1 :    //来源统计
                return $this->source_statistics($request);
                break;
            case 2 :    //类型统计
                return $this->type_statistics($request);
                break;
            case 3 :    //办事处统计
                return $this->office_statistics($request);
                break;
            case 4 :    //职能部门统计
                return $this->org_statistics($request);
                break;
            case 5 :    //红黄牌统计
                break;

        }
    }
    /**
     * 业务统计分析 之 事件来源统计
     * returen
     *      指挥中心 {data :[排查数,办结数,办结率]},
     *      网格员   {data :[排查数,办结数,办结率]},
     *      微信用户 {data :[排查数,办结数,办结率]}
     */
    protected function source_statistics($request){
        //DB::connection()->enableQueryLog();
        $query = DB::table('event');

        $date = $request->input('date');
        if($date){
            list($start,$end) = $this->deal_dateRangePicker($date);
            $query->whereBetween('create_time',[$start,$end]);
        }

        $query->select(DB::raw('COUNT(*) as count,`source`,`status`'))->where('status','!=',6)->where('source','!=',3);
        $events = $query->groupBy('status')->groupBy('source')->get();
        //dd(DB::getQueryLog());
        //组装x轴数据
            //目前只有三种来源(0~2)，来源名字在前台，如果数据集为空，返回一个默认数据
        for($i=0;$i<3;$i++){
            $data[$i]['total'] =0;
            $data[$i]['done'] =0;
            $data[$i]['ratio'] =0;
        }
        if(!$events){   //如果数据集为空，全部返回为0；
            return json_encode($data);
        }
        foreach($events as $v){
            if(!isset($data[$v->source])){
                $data[$v->source]['total'] = $v->count;
                $data[$v->source]['done'] = 0;
                $v->status==4 ? $data[$v->source]['done'] = $v->count : '';
            }else{
                $data[$v->source]['total'] += $v->count;
                $v->status==4 ? $data[$v->source]['done'] += $v->count : '';
            }
        }
        foreach($data as $k=>$v){
            $data[$k]['ratio'] = $v['total']==0 ? 0 : round($v['done']/$v['total']*100,2);
        }
        //dd(DB::getQueryLog());
        return json_encode($data);
    }

    /**
     * 业务统计分析 之 类型统计
     *  event_department(目前): 1:城管 2:民政 3：维稳 4：环保 5：安全生产 6：食药监 7：卫计委
     */
    protected function type_statistics($request){
        $date = $request->input('date');
        //取部门列表
        $depart_list = DB::table('event_department')->get();
        //x轴数据
        foreach($depart_list as $v){
            $depart[$v->id]['name'] = $v->name;             //组成['id'=>'name']的数组：如 1=>城管，2=>民政

            //初始化y轴数据
            $depart[$v->id]['total'] = 0;
            $depart[$v->id]['done'] = 0;
            $depart[$v->id]['ratio'] = 0;
        }
        unset($depart_list);
        //dd($depart);
        $query = DB::table('event')->where('event.status','!=',6);
        if($date){
            list($start,$end) = $this->deal_dateRangePicker($date);
            $query->whereBetween('event.create_time',[$start,$end]);
        }
        $query->select(DB::raw('COUNT(*) as count,`event`.`status`,`event_category`.`department_id` as depart'));
        $query->join('event_category','event.event_category_code','=','event_category.code');
        $events = $query->groupBy('event.status')->groupBy('event.event_category_code')->orderBy('event.event_category_code')->get();
        //dd($res);
        if(!$events){
            return json_encode($depart);
        }

        //组装y轴数据
        foreach($events as $v){
            $depart[$v->depart]['total'] += $v->count;
            $v->status == 4 ? $depart[$v->depart]['done'] += $v->count : '';
        }
            //计算比率
        foreach($depart as $k=>$v){
            $depart[$k]['ratio'] = ($v['total'] != 0 ? round($v['done']/$v['total']*100,2) : 0);
        }
        return json_encode($depart);
    }

    /**
     * 业务统计分析 之 办事处统计
     *  前端为：排查数，只选取已经被领取的部分last_process_org_id（分配给办事处的不再统计 next_process_org_id）
     */
    protected function office_statistics($request){
        $date = $request->input('date');
        //办事处列表 type:1:办事处 2：职能部门
        $office_list = DB::table('organization')->where('type',1)->select('id','name')->get();
        foreach($office_list as $v){
            // x轴数据
            $office[$v->id]['name'] = $v->name; //id=>办事处名字
            $office_ids[] = $v->id;   //过滤事件时用

            //初始化ye轴数据
            $office[$v->id]['total']=0;
            $office[$v->id]['done']=0;
            $office[$v->id]['ratio']=0;
        }
        //var_dump($office);
        //dd($office_list);
        $query = DB::table('event')->where('status','!=','6');
        if($date){
            list($start,$end) = $this->deal_dateRangePicker($date);
            $query->whereBetween('create_time',[$start,$end]);
        }
        $query->select(DB::raw('COUNT(*) as count,status,last_process_org_id as org_id'))->whereIn('last_process_org_id',$office_ids);
        $events = $query->groupBy('last_process_org_id')->groupBy('status')->get();
        //dd($events);
        if($events){
            //组装y轴数据
            foreach($events as $v){ //office.id = events.org_id
                $office[$v->org_id]['total'] += $v->count;
                $v->status==4 ? $office[$v->org_id]['done']  += $v->count : null;
            }

            foreach($office as $k=>$v){
                $office[$k]['ratio'] = $v['total']==0 ? 0 : round($v['done']/$v['total']*100,2);
            }
        }

        return json_encode($office);
    }

    /**
     * 业务统计分析 之 职能部门统计
     */
    protected function org_statistics($request){
        $date = $request->input('date');
        //职能部门列表 type:1:办事处 2：职能部门
        $org_list = DB::table('organization')->where('type',2)->select('id','name')->get();
        foreach($org_list as $v){
            // x轴数据
            $org[$v->id]['name'] = $v->name; //id=>办事处名字
            $org_ids[] = $v->id;   //过滤事件时用
            // 初始化y轴数据
            $org[$v->id]['total'] = 0;
            $org[$v->id]['done'] = 0;
            $org[$v->id]['ratio'] = 0;
        }
        //var_dump($org);
        $query = DB::table('event')->where('status','!=','6');
        if($date){
            list($start,$end) = $this->deal_dateRangePicker($date);
            $query->whereBetween('create_time',[$start,$end]);
        }
        $query->select(DB::raw('COUNT(*) as count,status,last_process_org_id as org_id'))->whereIn('last_process_org_id',$org_ids);
        $events = $query->groupBy('last_process_org_id')->groupBy('status')->get();
        if($events){
            //var_dump($events);
            // y轴数据
            foreach($events as $v){ //org['id'] = events.org_id
                $org[$v->org_id]['total'] += $v->count;
                $v->status==4 ? $org[$v->org_id]['done']  += $v->count : null;
            }
            // 比率
            foreach($org as $k=>$v){
                $org[$k]['ratio'] = $v['total']==0 ? 0 : round($v['done']/$v['total']*100,2);
            }
        }

        return json_encode($org);
    }

    /**
     * 拆解daterangepicker的时间
     */
    private function deal_dateRangePicker($dateString){
        list($start_tmp,$end_tmp) = explode('--',$dateString);
        $start = strtotime($start_tmp)*1000;
        $end = strtotime($end_tmp);
        $end = strtotime('+1 days',$end)<time() ? strtotime('+1 days',$end)*1000 : time()*1000;
        return [$start,$end];
    }
}
