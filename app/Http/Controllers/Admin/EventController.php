<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use Excel;

class EventController extends Controller
{
    /**
     * 事件首页
     */
    public function index(){
        return view('admin.event.index')
            ->with('title','事务处理中心')
            ->with('homeNav','事务处理中心')
            ->with('homeLink','/event_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }

    public function attachment(){
        $path = Input::get('path');
        $full_path = storage_path().'/app/public/'.$path;

        $h = intval(Input::get('h'));
        $w = intval(Input::get('w'));
        if($h > 0 && $w > 0){
            $thumb_path = storage_path().'/app/public/'.'thumb_'.$w.'_'.$h.'_'.$path;
            if(file_exists($thumb_path)){
                $full_path = $thumb_path;
            }
            else {
                $extension = pathinfo($full_path, PATHINFO_EXTENSION);
                $thumb = \PhpThumbFactory::create($full_path);
                $thumb->adaptiveResize($w, $h);
                $thumb->save($thumb_path, $extension);

                $full_path = $thumb_path;
            }

        }

        return response()->download($full_path);
    }

    /**
     * 事件详细页面
     */
    public function detail(){
        $type = intval(Input::get('type'));
        $id = intval(Input::get('id'));
        $event = Event::detail($id);

        if($event == false){
            $event = new \stdClass();
        }

        if($event->last_process_time == false){
            $event->last_process_time = date('Y-m-d H:i:s',time());
        }
        else {
            $event->last_process_time = date('Y-m-d H:i:s',$event->last_process_time/1000);
        }

        if($event->limit_end_time == false){
            $event->limit_end_time = '';
        }
        else {
            $event->limit_end_time = date('Y-m-d',$event->limit_end_time/1000);

        }


        $list = DB::table('event_attachment')->where('category',0)->where('event_id',$id)->get();
        //dd($event->attachment_list);
        $pic_str=[];
        foreach($list as $key=>$value){
            //$path = '/event_attachment?path='.$value->path;
            $path = $value->path;
            $pic_str[] = $path;
            $list[$key]->path = $path;
        }

        $event->attachment_list = $list;
        $event->pic_str = implode(",",$pic_str);

        if($type == 1){
            $text = 'admin.event.already_detail';
            $active = '已研判事件';
            $res_list = DB::table('event_attachment')->where('category',1)->where('event_id',$id)->get();
        }
        else if($type == 2){
            $text = 'admin.event.already_detail';
            $active='事件反馈';
            $res_list = DB::table('event_attachment')->where('category',1)->where('event_id',$id)->get();
        }
        else {
            $text = 'admin.event.detail';
            $active='待研判事件';
        }

        return view($text)
            ->with('title','事件研判')
            ->with('homeNav','事件研判')
            ->with('homeLink',"/event_detail?type=$type&id=$id")
            ->with('subNav','')
            ->with('activeNav',$active)
            ->with('type',$type)
            ->with('org_list',Event::get_org_status_list())
            ->with('category_1',Event::get_event_category(1))
            ->with('category_2',Event::get_event_category(2))
            ->with('category_3',Event::get_event_category(3))
            ->with('event_logs',Event::timeline($id))
            ->with('menus',getMenus())
            ->with('event',$event)
            ->with('res_list',isset($res_list)?$res_list:null);
    }

    /**
     * 事件处理
     */
    public function process(){
        //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        $id = intval(Input::get('id'));

        $title = Input::get('title');
        $address = Input::get('address');
        $reporter_name = Input::get('reporter_name');
        $reporter_phone = Input::get('reporter_phone');

        $desc = Input::get('desc');
        $additional_info = Input::get('additional_info');
        $suggest_info = Input::get('suggest_info');

        $attachment = Input::get('attachment');
        $attachment_list = [];
        if($attachment){
            $attachment_list = explode(",",$attachment);
        }

        //$event_category_code = Input::get("event_category_code");
        $category_1 = Input::get('category_1');
        $category_2 = Input::get('category_2');
        $category_3 = Input::get('category_3');
        if($category_3){
            $event_category_code = $category_3;
        }
        else if($category_2){
            $event_category_code = $category_2;
        }
        else if($category_1){
            $event_category_code = $category_1;
        }
        else {
            $event_category_code = '';
        }



        $status = intval(Input::get('status'));
        $limit_end_time = intval(Input::get('limit_end_time'));


        //当前处理人信息
        if($status == 0){
            $last_process_id = current_user_id();
            $last_process_name = current_user_name();
            $last_process_phone = current_user_phone();
            $last_process_time = millisecond();
            $last_process_org_id = current_user_org_id();
            $last_process_org_name = current_user_org_name();

            $data['last_process_id']=$last_process_id;
            $data['last_process_name']=$last_process_name;
            $data['last_process_phone']=$last_process_phone;
            $data['last_process_time']=$last_process_time;
            $data['last_process_org_id']=$last_process_org_id;
            $data['last_process_org_name']=$last_process_org_name;
        }


        //下一个移交单位 处理机构ID（由last_process_id指定的机构）
        $next_process_org_id = intval(Input::get('next_process_org_id'));

        if($title){
            $data['title']=$title;
        }
        if($address){
            $data['address']=$address;
        }
        if($reporter_name){
            $data['reporter_name']=$reporter_name;
        }
        if($reporter_phone){
            $data['reporter_phone']=$reporter_phone;
        }
        $data['desc']=$desc;
        if($additional_info){
            $data['additional_info']=$additional_info;
        }
//        if($suggest_info){
//            $data['suggest_info']=$suggest_info;
//        }
        $data['suggest_info']=$suggest_info;
//        if($next_process_org_id){
//            $data['next_process_org_id']=$next_process_org_id;
//        }
        $data['next_process_org_id']=$next_process_org_id;
        $data['limit_end_time']=$limit_end_time;


        $data['event_category_code']=$event_category_code;




        //$status 当前事件状态  //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        if($status == 0){
            //待研判
            $new_status = 1;
        }
        else {
            //已研判
//            if($status == 2){
//                $new_status = 4;
//            }
//            else{
//                //办结,审核通过和挂起皆可到办结状态
//                $new_status = 4;
//            }
            $new_status = 4;
        }
        $data['status']=$new_status;



        //研判
        $r = Event::process($id,$data);

        if($attachment_list){
            //添加事件附件
            DB::table('event_attachment')->where('event_id',$id)->where('category',0)->delete();
            $items = [];
            foreach($attachment_list as $attachment){
                $items[] = ['event_id'=>$id,'path'=>$attachment,'create_time'=>millisecond(),'type'=>1];
            }
            DB::table('event_attachment')->insert($items);
        }



        return responseToJson(0,'success',$r);
    }

    /**
     * 事件删除
     */
    public function delete(){
        $id = intval(Input::get('id'));
        $r = Event::event_delete($id);
        return responseToJson(0,'success',$r);
    }

    /**
     * 待研判事件页面
     */
    public function pre_determine(){
        return view('admin.event.pre_determine')
            ->with('title','研判事件')
            ->with('homeNav','研判事件')
            ->with('homeLink','/event_pre_determine')
            ->with('subNav','')
            ->with('activeNav','待研判')
            ->with('menus',getMenus());
    }

    /**
     * 待研判事件列表
     */
    public function event_list(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $status = intval(Input::get('status'));//事件状态

        $query = DB::table('event');
        if($status == 3){
            $query->where('status',$status);
        }
        else if($status && $status < 6){
            $query->whereBetween('status', [1,5]);
            //$query->orderBy('last_process_time','desc');
        }
        else {
            $query->where('status',$status);
        }

        if($sSearch != '') {
            $query->where('title','like','%'.mb_strtolower($sSearch).'%');
        }

        $count = $query->count();

        $list = $query
            ->skip($iDisplayStart)->take($iDisplayLength)->orderBy('update_time','desc')->orderBy('id','desc')->get();

        foreach($list as $key=>$value){
            if($value->source == 0){//事件来源（0：呼叫中心，1：网格员，2：微信用户）
                $list[$key]->source = '呼叫中心';
            }
            else if($value->source == 1){
                $list[$key]->source = '网格员';
            }
            else {
                $list[$key]->source = '微信用户';
            }

            //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
            switch($value->status){
                case 0:
                    $list[$key]->status='待研判';
                    break;
                case 1:
                    $list[$key]->status='待办理';
                    break;
                case 2:
                    $list[$key]->status='办理中';
                    break;
                case 3:
                    $list[$key]->status='办结待审核';
                    break;
                case 4:
                    $list[$key]->status='已办结';
                    break;
                case 5:
                    $list[$key]->status='已挂起';
                    break;
                case 6:
                    $list[$key]->status='已删除';
                    break;
                default:
                    $list[$key]->status='未知';
                    break;
            }

            $list[$key]->last_process_format = $value->last_process_org_name.'-'.$value->last_process_name;

            $list[$key]->last_process_time = date('Y-m-d H:i:s',$list[$key]->last_process_time/1000);
            $list[$key]->create_time = date('Y-m-d H:i:s',$list[$key]->create_time/1000);
            $list[$key]->update_time = date('Y-m-d H:i:s',$list[$key]->update_time/1000);
            $list[$key]->updator = DB::table('user')->where('id',$value->updator)->value('name');

        }

        if($list){
            $json["sEcho"] = $sEcho + 1;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["data"] = $list;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho + 1;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["data"] = "";
            echo json_encode($json);
        }

    }

    /**
     * 已研判事件页面
     */
    public function already_determine(){
        return view('admin.event.already_determine')
            ->with('title','研判事件')
            ->with('homeNav','研判事件')
            ->with('homeLink','/event_already_determine')
            ->with('subNav','')
            ->with('activeNav','已研判')
            ->with('menus',getMenus());
    }

//    /**
//     * 已研判事件列表
//     */
//    public function already_determine_list(){
//
//    }

    /**
     * 已研判事件退回
     */
    public function already_determine_back(){
        $id = intval(Input::get('id'));
        $suggest_info = Input::get('suggest_info');
        $r = Event::event_back($id,$suggest_info);
        return responseToJson(0,'success',$r);
    }

    /**
     * 已研判事件挂起
     */
    public function already_determine_close(){
        $id = intval(Input::get('id'));
        $suggest_info = Input::get('suggest_info');

        $r = Event::event_close($id,$suggest_info);
        return responseToJson(0,'success',$r);
    }

    /**
     * 自动研判事件页面
     */
    public function auto_determine(){
        return view('admin.event.auto_determine')
            ->with('title','研判事件')
            ->with('homeNav','研判事件')
            ->with('homeLink','/event_auto_determine')
            ->with('subNav','')
            ->with('activeNav','自动流转管理')
            ->with('menus',getMenus());
    }

    /**
     * 自动研判事件列表
     */
    public function auto_determine_list(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $query = DB::table('event_category');
        $query->leftJoin('organization','event_category.org_id','=','organization.id');
        if(!empty($sSearch)){
            $query -> where('event_category.name','like',"%$sSearch%");
        }
        $count = $query->count();
        $list = $query->select('organization.name as org_name','event_category.*')
            ->skip($iDisplayStart)->take($iDisplayLength)->orderBy('event_category.code','asc')->get();

        foreach($list as $key=>$value) {
            switch($value->level){
                case 1:
                    $list[$key]->level = '一级分类';
                    break;
                case 2:
                    $list[$key]->level = '二级分类';
                    break;
                case 3:
                    $list[$key]->level = '三级分类';
                    break;
                default:
                    $list[$key]->level = '未知';
                    break;
            }
        }

        if($list){
            $json["sEcho"] = $sEcho + 1;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["data"] = $list;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho + 1;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["data"] = "";
            echo json_encode($json);
        }
    }

    /**
     * 自动研判事件编辑添加页面
     */
    public function auto_determine_view(){
        $id = intval(Input::get('id'));
        $next_code = [];//次级分类
        if($id){
            $category = DB::table('event_category')->where('id',$id)->first();
            switch($category->level){
                case 1:
                    break;
                case 2:
                    $category->top_type = substr($category->code,0,3);
                    break;
                case 3:
                    $category->top_type = substr($category->code,0,3);
                    $category->next_type = substr($category->code,0,6);
                    $next_code = DB::table('event_category')->where('level',2)->where('code','like',$category->top_type.'%')->select('id','code','name')->get();;
                    break;
                default:
                    break;
            }
        }
        else {
            $category = null;
        }

        $department_list = DB::table('event_department')->get();
        $parent_code = DB::table('event_category')->where('level',1)->select('id','code','name')->get();//顶级分类

        return view('admin.event.auto_determine_view')
            ->with('category',$category)
            ->with('org_list',Organization::get_all())
            ->with('department_list',$department_list)
            ->with('parent_code',$parent_code)
            ->with('next_code',$next_code)
            ->with('title','事件研判')
            ->with('homeNav','事件研判')
            ->with('homeLink','/event_auto_determine')
            ->with('subNav','')
            ->with('activeNav','自动研判管理')
            ->with('menus',getMenus());
    }

    /**
     * 自动研判事件保存添加
     */
    public function auto_determine_save(){
        $id = intval(Input::get('category_id'));
        $parent_id = intval(Input::get('parent_id'));
        $level = intval(Input::get('level'));
        $name = Input::get('name');
        $org_id = intval(Input::get('org_id'));
        $department_id = intval(Input::get('department_id'));

        $data['level']=$level;
        $data['name']=$name;
        $data['org_id']=$org_id;
        $data['department_id']=$department_id;
        //获取code
        $code = Event::general_code($parent_id);
        $data['code']=$code;
        if($id){
            //更新
//            if(!empty($parent_id)){//不是一级分类
//                $parent_code = DB::table('event_category')->where('id',$parent_id)->value('code');
//                $code = DB::table('event_category')->where('id',$id)->value('code');
//                if(strstr($code,$parent_code) == false){
                    //父级分类更改,需重新生成code
//                    $code = Event::general_code($parent_id);
//                    $data['code']=$code;
//                }
//            }
            $r = DB::table('event_category')->where('id',$id)->update($data);
        }
        else {
            //添加
//            $code = Event::general_code($parent_id);
//            $data['code']=$code;

            $r = DB::table('event_category')->insert($data);
        }

        return responseToJson(0,'success');
    }


    /**
     * 获取次级分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auto_determine_get_child(Request $request){
        $level = $request->input('code');
        $parent_code = DB::table('event_category')->where('code','like',$level.'%')->where('level',2)->select('id','code','name')->get();
        return responseToJson(0,$parent_code);
    }

    public function auto_determine_org(){
        $category_id = intval(Input::get('category_id'));
        $org_id = intval(Input::get('org_id'));

        //$data['org_id']=$org_id;
        $r = DB::table('event_category')->where('id',$category_id)->update(['org_id'=>$org_id]);

        return responseToJson(0,'success',$r);
    }

    /**
     * 自动研判事件规则删除
     */
    public function auto_determine_delete(){
        $id = intval(Input::get('id'));

        $r = DB::table('event_category')->where('id',$id)->delete();
        return responseToJson(0,'success',$r);
    }

    /**
     * 回收站页面
     */
    public function recycle_bin(){
        return view('admin.event.recycle_bin')
            ->with('title','研判事件')
            ->with('homeNav','研判事件')
            ->with('homeLink','/event_recycle_bin')
            ->with('subNav','')
            ->with('activeNav','回收站')
            ->with('menus',getMenus());
    }

//    /**
//     * 回收站列表
//     */
//    public function recycle_bin_list(){
//
//    }

    /**
     * 回收站事件还原
     */
    public function recycle_bin_recorvery(){
        $id = intval(Input::get('id'));
        $r = Event::event_recorvery($id);
        if(empty($r)){
            return responseToJson(1,'success','还原失败');
        }else{
            return responseToJson(0,'success','已成功还原');
        }

    }

    /**
     * 回收站事件删除
     */
    public function recycle_bin_delete(){
        $id = intval(Input::get('id'));
        $r = Event::recycle_delete($id);
        return responseToJson(0,'success',$r);
    }

    /**
     * 事件反馈页面
     */
    public function feedback_determine(){
        return view('admin.event.feedback_determine')
            ->with('title','事件研判')
            ->with('homeNav','事件研判')
            ->with('homeLink','/event_feedback_determine')
            ->with('subNav','')
            ->with('activeNav','事件处理反馈')
            ->with('menus',getMenus());
    }


//    /**
//     * 事件反馈列表
//     */
//    public function feedback_determine_list(){
//
//    }

    /**
     * 事件反馈中处理退回
     */
    public function feedback_determine_back(){
//        $id = intval(Input::get('id'));
//        $r = Event::event_back($id);
//        responseToJson(0,'success',$r);
    }

//    /**
//     * 事件反馈中的回复
//     */
//    public function feedback_determine_reply(){
//
//    }

    /**
     * 事件查询页面
     */
    public function query(){
        return view('admin.event.query')
            ->with('title','事件研判')
            ->with('homeNav','事件研判')
            ->with('homeLink','/event_query')
            ->with('subNav','')
            ->with('activeNav','事件查询')
            ->with('org_list',Event::get_org_list())
            ->with('category_1',Event::get_event_category(1))
            ->with('category_2',Event::get_event_category(2))
            ->with('category_3',Event::get_event_category(3))
            ->with('menus',getMenus());
    }

    /**
     * 事件查询列表
     */
    public function query_list(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $status = Input::get('status');//事件状态
        $key = Input::get('keyword');
        $source = intval(Input::get('source'));
        $org_id = intval(Input::get('org_id'));
        $event_category_code = Input::get('event_category_code');
        $begin = intval(Input::get('begin_date'));
        $end = intval(Input::get('end_date'));

        $query = DB::table('event');
        $query->where('status','<',6);
        if($status>=0){
            $query->where('status',$status);
        }
        if($key){
            $query->where('title','like','%'.mb_strtolower($key).'%');
        }
        if($source>=0){
            $query->where('source',$source);
        }
        if($org_id){
            $id_list = DB::table('event_process_log')->where('to_org_id',$org_id)->lists('event_id');
            $query->whereIn('id',$id_list);
        }
        if($event_category_code){
            $query->where('event_category_code','like',"$event_category_code%");
        }

        if($begin > 0 || $end > 0){
            if($end <=0){
                $end = millisecond();
            }
            $end = $end +86400000-1000;//结束时间偏移
            $query->whereBetween('create_time',[$begin,$end]);
        }


        if($sSearch != '') {
            $query->where('title','like','%'.mb_strtolower($sSearch).'%');
        }

        $count = $query->count();
        $list = $query
            ->skip($iDisplayStart)->take($iDisplayLength)->orderBy('id','desc')->get();

        foreach($list as $key=>$value){
            if($value->source == 0){//事件来源（0：呼叫中心，1：网格员，2：微信用户）
                $list[$key]->source = '呼叫中心';
            }
            else if($value->source == 1){
                $list[$key]->source = '网格员';
            }
            else {
                $list[$key]->source = '微信用户';
            }

            //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
            $list[$key]->event_status=$value->status;
            switch($value->status){
                case 0:
                    $list[$key]->status='待研判';
                    break;
                case 1:
                    $list[$key]->status='待办理';
                    break;
                case 2:
                    $list[$key]->status='办理中';
                    break;
                case 3:
                    $list[$key]->status='办结待审核';
                    break;
                case 4:
                    $list[$key]->status='已办结';
                    break;
                case 5:
                    $list[$key]->status='已挂起';
                    break;
                case 6:
                    $list[$key]->status='已删除';
                    break;
                default:
                    $list[$key]->status='未知';
                    break;
            }

            $list[$key]->last_process_format = $value->last_process_org_name.'-'.$value->last_process_name;

            $list[$key]->last_process_time = date('Y-m-d H:i:s',$list[$key]->last_process_time/1000);
            $list[$key]->create_time = date('Y-m-d H:i:s',$list[$key]->create_time/1000);

        }

        if($list){
            $json["sEcho"] = $sEcho + 1;
            $json["iTotalRecords"] =$count;
            $json["iTotalDisplayRecords"] = $json["iTotalRecords"];
            $json["data"] = $list;
            echo json_encode($json);
        } else{
            $json["sEcho"] = $sEcho + 1;
            $json["iTotalRecords"] = 0;
            $json["iTotalDisplayRecords"] = 0;
            $json["data"] = "";
            echo json_encode($json);
        }
    }

    /**
     * 地图分布页面
     */
    public function map(){
        return view('admin.event.map')
            ->with('title','事件研判')
            ->with('homeNav','事件研判')
            ->with('homeLink','/event_map')
            ->with('subNav','')
            ->with('activeNav','地图分布')
            ->with('menus',getMenus());

    }

    /**
     * 地图分布数据
     */
    public function map_data(){

        $begin =  strtotime(date('Y-m-d'))*1000;
        $end = millisecond();

        $query = DB::table('event');

        //$query->whereBetween('create_time',[$begin,$end]);//todo:test
        //$status是否过滤 待观察

        //$list = $query->select('id','title','desc','create_time','longitude','latitude')->get();
        $list = $query->get();

        foreach($list as $key=>$value){
            $list[$key]->create_time = date('Y-m-d H:i:s',$list[$key]->create_time/1000);
            //img
            $list[$key]->img = DB::table('event_attachment')->where('event_id',$value->id)->value('path');
        }
        if($list == false){
            $list = [];
        }

        return responseToJson(0,'success',$list);

    }

    /**
     * 联合执法页面
     */
    public function union(){
        return view('admin.event.union_map')
            ->with('title','联合执法')
            ->with('homeNav','联合执法')
            ->with('homeLink','/event_union')
            ->with('subNav','')
            ->with('activeNav','联合执法')
            ->with('menus',getMenus());

    }

    /**
     * 联合执法
     */
    public function union_supervise(){
        //事件,人员,车辆 联合执法
        $begin =  0;//todo: strtotime(date('Y-m-d'))*1000;
        $end = millisecond();

//        $event_list = DB::table('event')->whereBetween('create_time',[$begin,$end])->get();

        //事件过滤【允许显示：0：待研判，1：待办理，2：办理中，3：办结待审核】
        $event_status = [0,1,2,3];
        $event_list = DB::table('event')->whereBetween('create_time',[$begin,$end])->wherein('status',$event_status)->get();
        foreach($event_list as $key=>$value){
            $event_list[$key]->create_time = date('Y-m-d H:i:s',$event_list[$key]->create_time/1000);
        }

        $query = DB::table('grid_user');
        //10分钟不更新gps,认为离线
        $last_time = millisecond() - 10*60*1000;
        $query->where('update_time','>',$last_time);

        $person_list = $query->where('is_online',1)->get();
        foreach($person_list as $key=>$value){

            $person_list[$key]->update_time = date('Y-m-d H:i:s',$value->update_time/1000);

            $user = DB::table('user')->where('id',$value->user_id)->first();
            $grid = DB::table('grid')->where('id',$value->grid_id)->first();

            $person_list[$key]->user_name = $user->name;
            $person_list[$key]->user_phone = $user->phone;
            $person_list[$key]->user_email = $user->email;
            $person_list[$key]->user_org_name = DB::table('organization')->where('id',$user->org_id)->value('name');
            $person_list[$key]->user_photo = $user->photo;


            if($grid){
                $person_list[$key]->grid_name = $grid->name;
                $person_list[$key]->grid_short_name = $grid->short_name;
                $person_list[$key]->grid_level = $grid->level.'级网格';
                $person_list[$key]->grid_parent_id = $grid->parent_id;
                $person_list[$key]->grid_parent_name = $grid->parent_id ? DB::table('grid')->where('id',$grid->parent_id)->value('name') : '指挥中心';
                $person_list[$key]->grid_grid_number = $grid->grid_number;
                $person_list[$key]->grid_family_number = $grid->family_number;
                $person_list[$key]->grid_fixed_population_number = $grid->fixed_population_number;
                $person_list[$key]->grid_fluid_population_number = $grid->fluid_population_number;
                $person_list[$key]->grid_manager_id = $grid->manager_id;
                $person_list[$key]->grid_manager_name = $grid->manager_name;
                $person_list[$key]->grid_map = $grid->map;
            }
        }

        $car_list = DB::table('prowl_car')->where('is_online',1)->get();

        $result = [
            'event_list'=>$event_list,
            'person_list'=>$person_list,
            'car_list'=>$car_list
        ];

        return responseToJson(0,'success',$result);
    }

    public function timeline(){
        $event_id = intval(Input::get('id'));
        $list = DB::table('event_process_log')->where('event_id',$event_id)->orderBy('create_time','desc')->get();
        $flag = 0;
        foreach($list as $key=>$value){
            $flag++;
            if($flag % 2 == 0){
                $list[$key]->li_class = 'timeline-inverted';
            }
            else {
                $list[$key]->li_class='';
            }
            switch($value->status){
                case 0:
                    $list[$key]->action = '事件待研判';
                    $list[$key]->fa = 'fa fa-tag';
                    $list[$key]->color = 'gray';
                    break;
                case 1:
                    $list[$key]->action = '研判了事件';
                    $list[$key]->fa = 'fa  fa-star-o';
                    $list[$key]->color = 'blue';
                    break;
                case 2:
                    $list[$key]->action = '领取了事件';
                    $list[$key]->fa = 'fa fa-star-half-o';
                    $list[$key]->color = 'purple';
                    break;
                case 3:
                    $list[$key]->action = '完成了事件';
                    $list[$key]->fa = 'fa fa-star';
                    $list[$key]->color = 'orange';
                    break;
                case 4:
                    $list[$key]->action = '审核通过了事件已办结';
                    $list[$key]->fa = 'fa fa-check';
                    $list[$key]->color = 'green';
                    break;
                case 5:
                    $list[$key]->action = '挂起了事件';
                    $list[$key]->fa = 'fa fa-times';
                    $list[$key]->color = 'yellow';
                    break;
                case 6:
                    $list[$key]->action = '删除了事件';
                    $list[$key]->fa = 'fa fa-trash-o';
                    $list[$key]->color = 'red';
                    break;
                default:
                    $list[$key]->action = '未知操作';
                    break;

            }
        }

        return view('admin.event.timeline')
            ->with('title','事件研判')
            ->with('homeNav','事件研判')
            ->with('homeLink','/event_timeline')
            ->with('subNav','')
            ->with('activeNav','事件流程图')
            ->with('event_logs',$list)
            ->with('menus',getMenus());
    }


    public function export(){
        $id = intval(Input::get('id'));

        $event = DB::table("event")->where('id',$id)->first();

        if($event->source == 0){//事件来源（0：呼叫中心，1：网格员，2：微信用户）
            $event->source = '呼叫中心';
        }
        else if($event->source == 1){
            $event->source = '网格员';
        }
        else {
            $event->source = '微信用户';
        }

        //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        switch($event->status){
            case 0:
                $event->status='待研判';
                break;
            case 1:
                $event->status='待办理';
                break;
            case 2:
                $event->status='办理中';
                break;
            case 3:
                $event->status='办结待审核';
                break;
            case 4:
                $event->status='已办结';
                break;
            case 5:
                $event->status='已挂起';
                break;
            case 6:
                $event->status='已删除';
                break;
            default:
                $event->status='未知';
                break;
        }

        $event->org_name = $event->last_process_org_name;

        $event->limit_end_time = $event->limit_end_time>0 ? date('Y-m-d H:i:s',$event->limit_end_time/1000) : '-';
        $event->create_time = date('Y-m-d H:i:s',$event->create_time/1000);


        //获取类别
        $event_category_code = $event->event_category_code;
        if($event_category_code){
            $code_1 = substr($event_category_code,0,3);
            $category_1 = DB::table('event_category')->where('level',1)->where('code',$code_1)->value('name');
            $code_2 = substr($event_category_code,0,6);
            $category_2 = DB::table('event_category')->where('level',2)->where('code',$code_2)->value('name');
            $code_3 = substr($event_category_code,0,9);
            $category_3 = DB::table('event_category')->where('level',3)->where('code',$code_3)->value('name');
        }
        else {
            $category_1='';
            $category_2='';
            $category_3='';
        }
        $event->category_1=$category_1 == false ? '-' : $category_1;
        $event->category_2=$category_2 == false ? '-' : $category_2;
        $event->category_3=$category_3 == false ? '-' : $category_3;


        //event log
        $log_list = DB::table('event_log')->where('event_id',$event->id)->get();
        $content='';
        foreach($log_list as $log){
            $time = date('Y-m-d H:i:s',$log->create_time/1000);
            $content += $time.' '.$log->org_name.'-'.$log->user_name.' '.$log->action.','.$log->memo.'\n';
        }

        $event->event_log=$content;


        $report_item = [];
        $process_item = [];
        $attachment_list = DB::table('event_attachment')->where('event_id',$id)->get();

        if($attachment_list){
            foreach($attachment_list as $key=>$value){
                //解析Path
                $path = $value->path;
                if($path){
                    $arr = parse_url($path);
                    $arr_query = convertUrlQuery($arr['query']);
                    $file_name = $arr_query['path'];
                    $w = 300;
                    $h = 300;

                    //保存文件全路径
                    $full_path = storage_path().'/app/public/'.$file_name;

                    if($value->category){
                        $process_item[] = convert_pic($file_name,$w,$h);
                    }
                    else {
                        $report_item[] = convert_pic($file_name,$w,$h);
                    }
                }

            }
        }

        $document = new \PHPWord_Template(public_path().'/admin/template/template.docx');

        $document->setValue('title',$event->title);
        $document->setValue('status',$event->status);
        $document->setValue('report_name',$event->reporter_name);
        $document->setValue('report_phone',$event->reporter_phone);
        $document->setValue('create_time',$event->create_time);
        $document->setValue('address',$event->address);
        $document->setValue('category_1',$event->category_1);
        $document->setValue('category_2',$event->category_2);
        $document->setValue('category_3',$event->category_3);
        $document->setValue('org_name',$event->org_name);
        $document->setValue('limit_end_time',$event->limit_end_time);
        $document->setValue('desc',$event->desc);
        $document->setValue('additional_info',$event->additional_info);
        $document->setValue('suggest_info',$event->suggest_info);
        $document->setValue('event_log',$event->event_log);
        $document->replaceStrToImg('report_attachment', $report_item);
        $document->replaceStrToImg('process_attachment', $process_item);

        $path = storage_path().'/app/牧野区网格化管理平台事件详情.docx';
        $document->save($path);

        return response()->download($path);

    }

}
