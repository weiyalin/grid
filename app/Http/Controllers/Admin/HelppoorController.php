<?php
/**
 * Created by PhpStorm.
 * User: xunmeng
 * Date: 17/5/13
 * Time: 上午1:37
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
class HelppoorController extends Controller{

    /**
     * 扶贫中心首页
     */
    public function index(){
        return view('admin.help.index')
            ->with('title','扶贫中心')
            ->with('homeNav','扶贫中心')
            ->with('homeLink','/help_poor_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }

    /**
     * 贫困人员信息
     */
    public function people_info_list(){
        return view('admin.help.info_list')
            ->with('title','贫困人员信息')
            ->with('homeNav','扶贫中心')
            ->with('homeLink','/help_poor_index')
            ->with('subNav','')
            ->with('activeNav','贫困人员信息')
            ->with('menus',getMenus());
    }

    /**
     * 矛盾反馈管理
     */
    public function help_back(){
        return view('admin.help.help_back')
            ->with('title','扶贫反馈')
            ->with('homeNav','扶贫中心')
            ->with('homeLink','/help_poor_index')
            ->with('subNav','')
            ->with('activeNav','扶贫反馈')
            ->with('menus',getMenus());
    }

    /**
     * 获得反馈信息
     */
    public function help_back_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

//        $address = trim(Input::get("address"));
//        $phone = trim(Input::get("phone"));
        $manager = trim(Input::get("manager"));
        $manager_phone = trim(Input::get("manager_phone"));
//
        $query = DB::table('help_back');

        if(!empty($sSearch)){
            $query->where('title','like','%'.$sSearch.'%');
        }
//
//        if(!empty($address)){
//            $query->where('address','like','%'.$address.'%');
//        }
//
//        if(!empty($phone)){
//            $query->where('phone',$phone);
//        }
//
        if(!empty($manager)){
            $query->where('manager','like','%'.$manager.'%');
        }

        if(!empty($manager_phone)){
            $query->where('manager_phone',$manager_phone);
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('id','desc')
            ->select('id','title','record_time','manager','manager_phone')
            ->get();

        foreach($list as $k=>$val){
            $list[$k]->record_time = date('Y-m-d H:i:s',$val->record_time);
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
     * 反馈管理设置【添加 or 详情】
     */
    public function help_back_set(Request $request){
        $id = $request->get('feedback_id',0);
        $type = $request->get('type','add');
        $feedback_info = null;
        if(($type=='edit'||$type=='see')&&!empty($id)){
            $feedback_info = DB::table('help_back')
                ->where('id',$id)
                ->first();
        }
        return view('admin.help.helpback_set')
            ->with('title','扶贫反馈')
            ->with('homeNav','扶贫中心')
            ->with('homeLink','/help_poor_index')
            ->with('subNav','')
            ->with('activeNav','扶贫反馈')
            ->with('feedback_info',$feedback_info)
            ->with('type',$type)
            ->with('menus',getMenus());
    }

    /**
     * 反馈添加 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function help_back_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['record_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('help_back')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('help_back')
                ->where('id',$obj)
                ->update($data);
            if(!empty($add_res)){
                return responseToJson(0,'已修改');
            }else{
                return responseToJson(1,'修改失败，请重试！');
            }
        }
    }

    /**
     * 删除反馈记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function help_back_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('help_back')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }

}