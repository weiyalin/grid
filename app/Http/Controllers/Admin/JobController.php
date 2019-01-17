<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use App\Models\Event;
use App\Http\Requests;
use App\Models\Grid;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    /**
     * 事件上报页面
     */
    public function event_report(){
        //return view('admin.job.event_report');
        return view('admin.job.event_report')
            ->with('title','日常办公')
            ->with('homeNav','日常办公')
            ->with('homeLink','/job_event_index')
            ->with('subNav','')
            ->with('activeNav','事件上报')
            ->with('menus',getMenus());
    }

    /**
     * 事件上报保存
     */
    public function event_report_save(Request $request){
        $this->validate($request,[
            'title'         => 'required',
            'address'       => 'required',
            'reporter_phone'=> 'required',
            'reporter_name' => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required'
        ]);

        $data = $request->all();
        //获取网格
        list($data['grid_1'],$data['grid_2'],$data['grid_3'],$data['grid_4']) = Grid::event_grid_id($data['longitude'],$data['latitude']);

        $pic_str = $data['pic_str'];
        unset($data['pic_str']);
        $data['reporter_id'] = $data['creator'] = current_user_id();
        $data['create_time']= millisecond();
        $data['update_time']= millisecond();


        //DB::beginTransaction();
        //提交事件，
        $event_id = DB::table('event')->insertGetId($data);
        //$res = DB::table('event')->insert($data);
        $pics = explode(',',$pic_str);
        foreach($pics as $v){
            $event_attachment_info[] = ['event_id'=>$event_id,'path'=>$v,'create_time'=>millisecond(),'type'=>1];
        }
        DB::table('event_attachment')->insert($event_attachment_info);   //事件图片

        //插入event_log数据
        $status['status'] = 0;  //事件上报：待研判
        Event::event_process_log($event_id,$status,current_user_org_id(),current_user_org_name(),'事件上报');

        if($event_id){
            //DB::commit();
            return responseToJson(0,'提交成功');
        }else{
            //DB::rollBack();
            return responseToJson(0,'提交失败');
        }
    }

    /**
     * 待办事件页面
     */
    public function pre_event(){
        return view('admin.job.pre_event')
            ->with('title','待办事件')
            ->with('homeNav','日常办公')
            ->with('homeLink','/job_event_index')
            ->with('subNav','')
            ->with('activeNav','待办事件')
            ->with('menus',getMenus());
    }

    /**
     * 待办事件列表
     */
    public function pre_event_list(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart  = intval(trim($request->input("iDisplayStart")));   //起始索引
        $iDisplayLength = intval(trim($request->input("iDisplayLength")));  //显示行数
        $sSearch    = trim($request->input("sSearch"));                     //全局搜索字段

        $query = DB::table('event');
        //基本过滤条件
        $query->where('status',1);    //0：待研判 1：待办理 2:办理中。。。
        $query->where('next_process_org_id',current_user_org_id());

        if($sSearch != '') {
            $query->where('title','like','%'.$sSearch.'%');
        }
        $query->select('id','title','province','city','district','address','source','last_process_name','last_process_org_name','create_time'); //要获取的字段

        $count = $query->count();           //数据总数
        $query->skip($iDisplayStart)->take($iDisplayLength);    //分页
        $list = $query->get();  //获取数据

        $sourceName = [0=>'呼叫中心',1=>'网格员',2=>'微信用户'];   //与数据库中的对应

        //var_dump(DB::getQueryLog());
        if($list){
            //数据中的 “省份+城市+县区+街道” 组成 address为键的"详细地址"
            foreach($list as $v){
                $source = $sourceName[$v->source];
                $address = $v->province.' '.$v->city.' '.$v->district.' '.$v->address;    //把地址拼装成详细地址
                $tmpArr[] = ['id'=>$v->id,'title'=>$v->title,'address'=>$address,'create_time'=>date('Y-m-d H:i:s',$v->create_time/1000),'source'=>$source];
            }

            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["aaData"] = $tmpArr;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["aaData"] = array();
            echo json_encode($json);
        }
    }

    /**
     * 代办事件详细页面
     * 已领事件详细页面
     */
    public function pre_event_view(Request $request){
        $id = intval($request->input('id'));    //event.id
        $type = intval($request->input('type'));   //1 为待办事件 2:已领事件 3：已办事件

        $where['id'] = $id;
        // 1、查看的事件详情应该 ：属于自己部门的事件
        // 2、事件办结，下个处理部分已经不属于自己。这时查看详情，需要last_process_id是自己
        if($type == 3){
            $where['last_process_id'] = current_user_id();
        }else{
            $where['next_process_org_id'] = current_user_org_id();
        }

        $event = DB::table('event')->where($where)->first();
        $event->limit_end_time = $event->limit_end_time ==0 ? '' : date('Y-m-d H:i:s',$event->limit_end_time/1000);

        //事件的（图片）多媒体信息【非处理结果类】
        $pics = DB::table('event_attachment')->where('category',0)->where('event_id',$id)->get();
        //事件的（图片）多媒体信息【处理结果类】
        $res_pics = DB::table('event_attachment')->where('category',1)->where('event_id',$id)->get();

        if(!empty($res_pics)){
            $pic_str=[];
            foreach($res_pics as $key=>$value){
                //$path = '/event_attachment?path='.$value->path;
                $path = $value->path;
                $pic_str[] = $path;
                $res_pics[$key]->path = $path;
            }

            $res_pics_str = implode(",",$pic_str);
        }

        switch($type){ //待办事件
            case 1 :
                $title = '待办事件 -- 事件详情';
                $activeNav = '待办事件';
                $backurl = '/job_pre_event';
                break;
            case 2 :    //已领事件
                $title = '已领事件 -- 事件详情';
                $activeNav = '已领事件';
                $backurl = '/job_get_event';
                break;
            case 3 : //已办结事件
                $title = '办结事件 -- 事件详情';
                $activeNav = '办结事件';
                $backurl = '/job_already_event';
                switch($event->status){
                    case 3: $statusDesc = '办结--待审核';   break;
                    case 4: $statusDesc = '办结--已审核';   break;
                    case 5: $statusDesc = '已挂起';         break;
                }
                $event->statusDesc = $statusDesc."[".date('Y-m-d H:i:s',$event->update_time/1000)."]";
                break;
        }
        return view('admin.job.event_view')
            ->with('title',$title)
            ->with('homeNav','日常办公')
            ->with('homeLink','/job_event_index')
            ->with('subNav','')
            ->with('activeNav',$activeNav)
            ->with('menus',getMenus())
            ->with('event',$event)
            ->with('pics',$pics)
            ->with('res_pics',isset($res_pics)?$res_pics:null)
            ->with('res_pics_str',isset($res_pics_str)?$res_pics_str:null)
            ->with('type',$type)
            ->with('id',$id)
            ->with('backurl',$backurl);
    }

    /**
     * 领取事件
     *  1、更新event中last_*、status字段
     *  2、更新、并 新增 event_process_log //更换为event_log表 2016年7月14日
     */
    public function pre_event_get(Request $request){
        $id = intval($request->input('id'));    //event_id

        $event['last_process_id'] = current_user_id();
        $event['last_process_name'] = current_user_name();
        $event['last_process_phone'] = $request->user()->phone;
        $event['last_process_org_id'] = current_user_org_id();
        $event['last_process_org_name'] = current_user_org_name();
        $event['last_process_time'] = millisecond();
        $event['status'] = 2;   //领取后，状态改为办理中
        $event['updator'] = current_user_id();
        $event['update_time'] = millisecond();
        //var_dump($event);
        //更新event表
        //DB::beginTransaction();
        $res1 = DB::table('event')->where('id',$id)->update($event);

        //插入event_log数据
        $status['status'] = 2;  //领取后状态为：办理中
        Event::event_process_log($id,$status,current_user_org_id(),current_user_org_name(),'领取事件');

        if($res1){
            //DB::commit();
            return responseToJson(0,'领取成功，进入已领事件查看详情');
        }else{
            //DB::rollBack();
            return responseToJson(1,'领取失败，请重试');
        }
    }

    /**
     * 退回事件 -- 待办事件退回，退回至研判中心
     *  1、event表中status=0,next_process_org_id=0;
     *  2、插入一条新的event_log
     */
    public function pre_event_back(Request $request){
        $id = intval($request->input('id'));
        //dd($request->all());
        $memo = htmlspecialchars($request->input('memo'));
        $content = htmlspecialchars($request->input('content'));
        if($memo == ''){
            return responseToJson(1,'请输入退回原因');
        }

        //DB::beginTransaction();
        //更新event表
        $where['id'] = $id;

        $event['status'] = 0;
        $event['next_process_org_id'] = 0;  //处理机构：退回至研判中心
        //last_*信息清空
        $event['last_process_id'] = 0;
        //$event['last_process_name'] = '';
        $event['last_process_org_id'] = 0;
        $event['suggest_info'] = $memo;
        $event['updator'] = current_user_id();
        $event['update_time'] = millisecond();
        //$event['source'] = 3;       //退回时，来源改为3
        $event['source_format'] = current_user_org_name().'--'.current_user_name();
        $res1 = DB::table('event')->where($where)->update($event);


        //插入event_log数据
        $status['status'] = 0;  //退回后，状态改为0：待研判
        Event::event_process_log($id,$status,current_user_org_id(),current_user_org_name(),'退回事件至指挥中心',$content);

        if($res1){
            //DB::commit();
            return responseToJson(0,'退回成功，已退回至事件研判中心');
        }else{
            //DB::rollBack();
            return responseToJson(1,'退回失败，请重试');
        }

    }

    /**
     * 已领事件页面
     */
    public function get_event(){
        return view('admin.job.get_event')
            ->with('title','已领事件')
            ->with('homeNav','日常办公')
            ->with('homeLink','/job_event_index')
            ->with('subNav','')
            ->with('activeNav','已领事件')
            ->with('menus',getMenus());
    }

    /**
     * 已领事件列表
     *  1、event中last_process_id是自己的，且status = 2 (办理中的)
     */
    public function get_event_list(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart  = intval(trim($request->input("iDisplayStart")));   //起始索引
        $iDisplayLength = intval(trim($request->input("iDisplayLength")));  //显示行数
        $sSearch    = trim($request->input("sSearch"));                     //全局搜索字段

        $query = DB::table('event');

        //基本过滤条件
        $query->where('status',2);    //1：待办理 2:办理中。。。
        $query->where('last_process_id',current_user_id());  //处理人是自己
        $query->orderBy('update_time','desc');

        if($sSearch != '') {
            $query->where('title','like','%'.$sSearch.'%');
        }

        $count = $query->count();           //数据总数
        $query->skip($iDisplayStart)->take($iDisplayLength);    //分页
        $list = $query->get();  //获取数据

        $sourceName = [0=>'呼叫中心',1=>'网格员',2=>'微信用户',3=>'退回部门'];   //与数据库中的对应

        //var_dump(DB::getQueryLog());
        if($list){
            //数据中的 “省份+城市+县区+街道” 组成 address为键的"详细地址"
            foreach($list as $v){
                $source = $sourceName[$v->source];         //把int转化成对应的名称
                $address = $v->province.' '.$v->city.' '.$v->district.' '.$v->address;    //把地址拼装成详细地址

                $tmpArr[] = ['id'=>$v->id,'title'=>$v->title,'address'=>$address,'create_time'=>date('Y-m-d H:i:s',$v->create_time/1000),'source'=>$source,'last_process_time'=>date('Y-m-d H:i:s',$v->last_process_time/1000),];
            }
            unset($list);
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["aaData"] = $tmpArr;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["aaData"] = [];
            echo json_encode($json);
        }
    }

    /**
     * 退回 -- 已领事件退回---退回至待办事件
     *  1、更新envent表事件的 status = 1; last_process_org_id =0 ,last_process_id = 0;
     *  2、插入新的event_log数据
     */
    public function event_back_to_pre(Request $request){
        $id = intval($request->input('id'));
        $memo = trim(htmlspecialchars($request->input('memo')));
        $content = trim(htmlspecialchars($request->input('content')));
        if($memo == ''){
            return responseToJson(1,'请填写退回原因');
        }

        //DB::beginTransaction();
        //更新event表
        $where['id'] = $id;

        //待办事件还没有领取的时候，last字段全部都 应该 没有被填充,所以退回时，清空
        $event['last_process_org_id'] = 0;
        //$event['last_process_org_name'] = '';
        $event['last_process_id'] = 0;
        $event['status'] = 1;
        $event['suggest_info'] = $memo;
        $event['updator'] = current_user_id();
        $event['update_time'] = millisecond();
        //$event['source'] = 3;       //退回时，来源改为3
        $event['source_format'] = current_user_org_name().'--'.current_user_name();

        $res1 = DB::table('event')->where($where)->update($event);

        //插入event_log数据
        $status['status'] = 1;  //领取事件退回，退回至办理中
        Event::event_process_log($id,$status,current_user_org_id(),current_user_org_name(),'事件退回至待办事件',$content);

        if($res1){
            //DB::commit();
            return responseToJson(0,'已退回至待办事件');
        }else{
            //DB::rollBack();
            return responseToJson(1,'退回失败,请重试');
        }
    }

    /**
     * 办结  --- 即提交审核
     *  1、event表中更新status字段 = 3
     *  2、event_log中插入一条新得数据
     */
    public function event_down(Request $request){
        $id = intval($request->input('id'));
        $memo = trim(htmlspecialchars($request->input('content')));
        if($memo == ''){
            responseToJson(1,'请填写事件处理结果');
        }
        $result_photos = $request->input('result_photos');
        $result_photos_list = [];
        if($result_photos){
            $result_photos_list = explode(",",$result_photos);
        }

        //DB::beginTransaction();
        //更新event表
        //$where['id'] = $id;
        /* 修改suggest_info存储信息方式：2016年7月14日14:49:23注释
         * $event['status'] = 3;
        $event['next_process_org_id'] = 0;  //办结，交给指挥中心
        $event['suggest_info'] = ' \n事件办结：'.$memo;
        $event['updator'] = current_user_id();
        $event['update_time'] = millisecond();*/
        //$res1 = DB::table('event')->where($where)->update($event);
        //办结：交给指挥中心
        $res1 = DB::update('update `event` set `status`=3,`next_process_org_id`=0,`suggest_info`=CONCAT(`suggest_info`,?),`updator`=?,`update_time`=? where `id`=?',
                            [$memo,current_user_id(),millisecond(),$id]);
        unset($where);
        unset($event);

        if($result_photos_list){
            //删除原来的处理结果附件
            DB::table('event_attachment')->where('category',1)->where('event_id',$id)->delete();

            //添加事件处理结果附件
            $items = [];
            foreach($result_photos_list as $result_photo){
                $items[] = ['event_id'=>$id,'path'=>$result_photo,'create_time'=>millisecond(),'type'=>1,'category'=>1];
            }
            DB::table('event_attachment')->insert($items);
        }

        //插入event_log数据
        $status['status'] = 3;  //事件办结
        Event::event_process_log($id,$status,current_user_org_id(),current_user_org_name(),'事件办结',$memo);

        if($res1){
            //DB::commit();
            return responseToJson(0,'办结已提交');
        }else{
            //DB::rollBack();
            return responseToJson(1,'提交失败,请重试');
        }
    }
    /**
     * 已办事件页面
     */
    public function already_event(){
        return view('admin.job.already_event')
            ->with('title','已办事件')
            ->with('homeNav','日常办公')
            ->with('homeLink','/job_event_index')
            ->with('subNav','')
            ->with('activeNav','已办事件')
            ->with('menus',getMenus());
    }

    /**
     * 已办事件列表
     */
    public function already_event_list(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart  = intval(trim($request->input("iDisplayStart")));   //起始索引
        $iDisplayLength = intval(trim($request->input("iDisplayLength")));  //显示行数
        $sSearch    = trim($request->input("sSearch"));                     //全局搜索字段

        $query = DB::table('event');

        //基本过滤条件
        $query->whereIn('status',[3,4,5]);    //1：待办理 2:办理中 3:已办理待审核 4 已办结 5、已挂起
        $query->where('last_process_id',current_user_id());  //处理人是自己

        if($sSearch != '') {
            $query->where('title','like','%'.$sSearch.'%');
        }

        $count = $query->count();           //数据总数

        $query->skip($iDisplayStart)->take($iDisplayLength);    //分页
        $list = $query->get();  //获取数据

        $sourceName = [0=>'呼叫中心',1=>'网格员',2=>'微信用户',3=>'退回部门'];   //与数据库中的对应
        $statusName = [3=>'办结-待审核',4=>'办结-已审核',5=>'已挂起'];

        //var_dump(DB::getQueryLog());
        if($list){
            //数据中的 “省份+城市+县区+街道” 组成 address为键的"详细地址"
            foreach($list as $v){
                $source = $sourceName[$v->source];         //把int转化成对应的名称
                $address = $v->province.' '.$v->city.' '.$v->district.' '.$v->address;    //把地址拼装成详细地址
                $tmpArr[] = [
                    'id'=>$v->id,
                    'title'=>$v->title,
                    'address'=>$address,
                    'create_time'=>date('Y-m-d H:i:s',$v->create_time/1000),
                    'source'=>$source,
                    //'last_process_time'=>date('Y-m-d H:i:s',$v->last_process_time/1000),
                    //'get_time'      => ,
                    'event_status'  => $statusName[$v->status].'  ['.date('Y-m-d H:i:s',$v->update_time/1000).']'
                ];
            }

            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["aaData"] = $tmpArr;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["aaData"] = [];
            echo json_encode($json);
        }
    }

    /**
     * 事件统计页面
     */
    public function event_stat(){
        return view('admin.job.event_stat')
            ->with('title','事件统计')
            ->with('homeNav','日常办公')
            ->with('homeLink','/job_event_index')
            ->with('subNav','')
            ->with('activeNav','事件统计')
            ->with('menus',getMenus());
    }

    /**
     * 事件统计参数
     */
    public function event_stat_data(Request $request){
        $query = DB::table('event');

        $date = trim($request->input('date'));
        if($date){
            list($start,$end) = explode('--',$date);
            $start = strtotime($start)*1000;
            $end = strtotime($end)*1000;
            $query->whereBetween('create_time',[$start,$end]);
        }
        //统计个人的事件数量

        $event = $query->select(DB::raw('COUNT(*) as count,status'))
            ->whereNotIn('status',[0,1,6])    //待研判、待办理、删除的不统计
            //->where('last_process_id',current_user_id())
            ->groupBy('status')->get();

        $total = $recevie = $review = $done = $guaqi = 0;
        foreach($event as $v){
            $total += $v->count;                        //总数、交办数
            $v->status==2 ? $done  += $v->count : null;   //办理中
            $v->status==3 ? $review += $v->count : null;  //办理待审核
            $v->status==4 ? $done  += $v->count : null;   //办结数
            $v->status==5 ? $guaqi += $v->count : null;   //挂起数
        }
        $data = ['total'=>$total,'recevie'=>$recevie,'review'=>$review,'done'=>$done,'guaqi'=>$guaqi,'date'=>$date];
        echo json_encode($data);
    }
    /**
     * 事件统计列表
     */
    public function event_stat_list(){

    }


}
