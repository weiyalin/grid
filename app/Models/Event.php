<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Event extends Model
{
    public static function detail($id){
        $event = DB::table('event')->where('id',$id)->first();
        return $event;
    }

    public static function event_process_log($id,$data,$org_id,$org_name,$action,$memo=null){
        $log['event_id']=$id;
        $log['org_id']=$org_id;
        $log['org_name']=$org_name;
        $log['user_id']=current_user_id();
        $log['user_name']=current_user_name();
        $log['create_time']=millisecond();
        $log['status']=$data['status'];
        $log['action']=$action;
        $log['memo']=$memo;

        DB::table('event_log')->insert($log);
//        $log['event_id']=$id;
//        $log['from_org_id']=$from_org_id;
//        $log['from_org_name']=$from_org_name;
//        $log['from_user_id']=session('user')->id;
//        $log['from_user_name']=session('user')->name;
//        $log['to_org_id']=$data['next_process_org_id'];
//        $log['to_org_name']= $data['next_process_org_id']==0 ? '指挥中心' : DB::table('organization')->where('id',$data['next_process_org_id'])->value('name');
//        $log['memo']=$data['suggest_info'];
//        $log['create_time']=millisecond();
//        $log['status']=$data['status'];
//        DB::table('event_process_log')->insert($log);
    }


    public static function process($id,$data){
        if($id){
            if($data['status'] == 1){
                $text = '研判事件';
            }
            else {
                $text = '事件办结';
            }
            //更新
            $r = DB::table('event')->where('id',$id)->update($data);
            //记录日志
            self::event_process_log($id,$data,0,'指挥中心',$text);
        }
        else {
            //添加
            $r = DB::table('event')->insert($data);
        }
        return $r;
    }


    public static function event_delete($id){
        //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        $r = DB::table('event')->where('id',$id)->update(['status'=>6,'update_time'=>millisecond(),'updator'=>current_user_id()]);

        //记录
        $data['next_process_org_id']=0;
        $data['suggest_info']='';
        $data['status']=6;
        self::event_process_log($id,$data,0,'指挥中心','删除事件');

        return $r;
    }


    public static function recycle_delete($id){
        $r = DB::table('event')->where('id',$id)->where('status',6)->delete();
        return $r;
    }


    public static function event_back($id,$suggest_info){
        //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        $event = DB::table('event')->where('id',$id)->first();

        $r = DB::table('event')->where('id',$id)->update(['status'=>1,'next_process_org_id'=>$event->last_process_org_id,'update_time'=>millisecond(),'suggest_info'=>$suggest_info,'updator'=>current_user_id()]);

        //记录
        $data['next_process_org_id']=$event->last_process_org_id;
        $data['suggest_info']=$suggest_info;
        $data['status']=1;
        self::event_process_log($id,$data,0,'指挥中心','退回事件',$suggest_info);

        return $r;
    }

    public static function event_close($id,$suggest_info){
        //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        $r = DB::table('event')->where('id',$id)->update(['status'=>5,'update_time'=>millisecond(),'suggest_info'=>$suggest_info,'updator'=>current_user_id()]);

        //记录
        $data['next_process_org_id']=0;
        $data['suggest_info']=$suggest_info;
        $data['status']=5;
        self::event_process_log($id,$data,0,'指挥中心','关闭事件',$suggest_info);

        return $r;
    }

    public static function event_recorvery($id){
        //状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
        $r = DB::table('event')->where('id',$id)->update(['status'=>0,'update_time'=>millisecond(),'updator'=>current_user_id()]);

        //记录
        $data['next_process_org_id']=0;
        $data['suggest_info']='事件被还原';
        $data['status']=0;
        self::event_process_log($id,$data,0,'指挥中心','还原事件');

        return $r;
    }


    /**
     * @param $parent_id 父级id
     * @return string
     * 生成事件类型代码,根据父级
     */
    public static function general_code($parent_id){
        if($parent_id == 0){
            //一级类别
            $max_code = DB::table('event_category')->where('level',1)->max('code');
            if($max_code == false){
                //还没有一级分类
                $code = '001';
            } else {
                $new_code = intval($max_code)+1;
                $code = sprintf("%03s",$new_code);
            }
        }
        else {
            //非一级类别
            $parent_code = DB::table('event_category')->where('id',$parent_id)->value('code');
            $parent_level = DB::table('event_category')->where('id',$parent_id)->value('level');
            $query = DB::table('event_category')->where('code','like',"$parent_code%");
            if($parent_level==1){
                $query->where('level',2);
            }else{
                $query->where('level',3);
            }
            $max_code = $query->max('code');
            if(empty($max_code)){
                //第一个子分类
                $end_code = '001';
                $code = $parent_code.$end_code;
            }
            else {
                $new_code = intval(substr($max_code,strlen($parent_code)))+1;
                $end_code = sprintf("%03s",$new_code);
                $code = $parent_code.$end_code;
            }

        }

        return $code;
    }


    public static function get_org_list(){
        $list = DB::table('organization')->select('id','name')->get();
        return $list;
    }

    /**
     * 获得正常的机构信息
     * @return mixed
     */
    public static function get_org_status_list(){
        $list = DB::table('organization')->where('status',0)->select('id','name')->get();
        return $list;
    }

    public static function get_event_category($level){
        $list = DB::table('event_category')->where('level',$level)->get();
        return $list;
    }

    //自动研判事件,返回下一个流转部门,返回0代表无法自动研判
    public static function auto_process_event($code){

        $org_id = DB::table("event_category")->where('code',$code)->value('org_id');
        if($org_id == 0){
            $len = strlen($code)-3;
            if($len){
                $p_code = substr($code,0,$len);
                $org_id = DB::table("event_category")->where('code',$p_code)->value('org_id');
                if($org_id == 0){
                    $sub_len = strlen($p_code)-3;
                    if($sub_len){
                        $p_sub_code = substr($code,0,$sub_len);
                        $org_id = DB::table("event_category")->where('code',$p_sub_code)->value('org_id');
                    }
                }
            }
        }

        return $org_id;

    }

    public static function timeline($id){
        $list = DB::table('event_log')->where('event_id',$id)->orderBy('create_time','desc')->get();

//        $list = DB::table('event_process_log')->where('event_id',$id)->orderBy('create_time','desc')->get();
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
                    //$list[$key]->action = '事件待研判';
                    $list[$key]->fa = 'fa fa-tag';
                    $list[$key]->color = 'gray';
                    break;
                case 1:
                   // $list[$key]->action = '研判了事件';
                    $list[$key]->fa = 'fa  fa-star-o';
                    $list[$key]->color = 'blue';
                    break;
                case 2:
                    //$list[$key]->action = '领取了事件';
                    $list[$key]->fa = 'fa fa-star-half-o';
                    $list[$key]->color = 'purple';
                    break;
                case 3:
                   // $list[$key]->action = '完成了事件';
                    $list[$key]->fa = 'fa fa-star';
                    $list[$key]->color = 'orange';
                    break;
                case 4:
                   // $list[$key]->action = '审核通过了事件已办结';
                    $list[$key]->fa = 'fa fa-check';
                    $list[$key]->color = 'green';
                    break;
                case 5:
                   // $list[$key]->action = '挂起了事件';
                    $list[$key]->fa = 'fa fa-times';
                    $list[$key]->color = 'yellow';
                    break;
                case 6:
                  //  $list[$key]->action = '删除了事件';
                    $list[$key]->fa = 'fa fa-trash-o';
                    $list[$key]->color = 'red';
                    break;
                default:
                    $list[$key]->action = '未知操作';
                    break;

            }
        }

        return $list;
    }


}
