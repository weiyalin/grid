<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class PartyController extends Controller
{
    //首页
    public function index(){
        return view('admin.party.index')
            ->with('title','党建中心')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }

    /**
     * 场所管理
     */
    public function place(){
        return view('admin.party.place')
            ->with('title','场所管理')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','场所管理')
            ->with('menus',getMenus());
    }

    /**
     * 获得场所信息
     */
    public function place_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $address = trim(Input::get("address"));
        $phone = trim(Input::get("phone"));
        $manager = trim(Input::get("manager"));
        $manager_phone = trim(Input::get("manager_phone"));

        $query = DB::table('party_place');

        if(!empty($sSearch)){
            $query->where('name','like','%'.$sSearch.'%');
        }

        if(!empty($address)){
            $query->where('address','like','%'.$address.'%');
        }

        if(!empty($phone)){
            $query->where('phone',$phone);
        }

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
            ->get();

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
     * 场所管理设置【添加 or 修改】
     */
    public function place_set(Request $request){
        $id = $request->get('place_id',0);
        $place_info = null;
        if(!empty($id)){
            $place_info = DB::table('party_place')
                ->where('id',$id)
                ->first();
        }
        $type_list = $this->getTypeList();
        return view('admin.party.place_set')
            ->with('title','场所管理')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','场所管理')
            ->with('type_list',$type_list)
            ->with('place_info',$place_info)
            ->with('menus',getMenus());
    }

     /**
     * 获取场管理所类型列表：只获取id、name字段，供添加 修改时使用
     */
    private function getTypeList(){
        $type_list = DB::table('party_place_type')->select('id','name')->get();
        return $type_list;
    }

    /**
     * 场所添加 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function place_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['create_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('party_place')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('party_place')
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
     * 场所信息验证【验证场所是否存在】
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function place_name_check(Request $request){
        $obj = $request->input('obj');
        $name = $request->input('name');
        $place_info = DB::table('party_place')
            ->where('name',$name)
            ->first();
        if(empty($place_info)){
            return responseToJson(0,'true');
        }else{
            if(empty($obj)||$place_info['id']!=$obj){
                return responseToJson(1,'false');
            }else{
                return responseToJson(0,'true');
            }
        }
    }

    /**
     * 删除场所
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function place_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('party_place')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }


    /**
     * 经费管理
     */
    public function money(){
        return view('admin.party.money')
            ->with('title','经费管理')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','经费管理')
            ->with('menus',getMenus());
    }

    /**
     * 获得经费信息
     */
    public function money_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

//        $address = trim(Input::get("address"));
//        $phone = trim(Input::get("phone"));
        $manager = trim(Input::get("manager"));
        $manager_phone = trim(Input::get("manager_phone"));
//
        $query = DB::table('party_money');

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
     * 经费管理设置【添加 or 详情】
     */
    public function money_set(Request $request){
        $id = $request->get('money_id',0);
        $type = $request->get('type','add');
        $money_info = null;
        if(($type=='edit'||$type=='see')&&!empty($id)){
            $money_info = DB::table('party_money')
                ->where('id',$id)
                ->first();
        }
        return view('admin.party.money_set')
            ->with('title','经费管理')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','经费管理')
            ->with('money_info',$money_info)
            ->with('type',$type)
            ->with('menus',getMenus());
    }

    /**
     * 经费添加 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function money_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['record_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('party_money')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('party_money')
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
     * 删除经费记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function money_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('party_money')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }



    /**
     * 矛盾反馈管理
     */
    public function feedback(){
        return view('admin.party.feedback')
            ->with('title','矛盾反馈')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','矛盾反馈')
            ->with('menus',getMenus());
    }

    /**
     * 获得反馈信息
     */
    public function feedback_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

//        $address = trim(Input::get("address"));
//        $phone = trim(Input::get("phone"));
        $manager = trim(Input::get("manager"));
        $manager_phone = trim(Input::get("manager_phone"));
//
        $query = DB::table('party_feedback');

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
    public function feedback_set(Request $request){
        $id = $request->get('feedback_id',0);
        $type = $request->get('type','add');
        $feedback_info = null;
        if(($type=='edit'||$type=='see')&&!empty($id)){
            $feedback_info = DB::table('party_feedback')
                ->where('id',$id)
                ->first();
        }
        return view('admin.party.feedback_set')
            ->with('title','矛盾反馈')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','矛盾反馈')
            ->with('feedback_info',$feedback_info)
            ->with('type',$type)
            ->with('menus',getMenus());
    }

    /**
     * 反馈添加 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['record_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('party_feedback')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('party_feedback')
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
    public function feedback_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('party_feedback')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }





    /**
     * 平安建设
     */
    public function peace(){
        return view('admin.party.peace')
            ->with('title','平安建设')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','平安建设')
            ->with('menus',getMenus());
    }
    /**
     * 获得平安建设信息
     */
    public function peace_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $query = DB::table('camera')->where('type',0);

        if(!empty($sSearch)){
            $query->where('name','like','%'.$sSearch.'%');
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('id','desc')
            ->get();

        foreach($list as $k=>$val){
            $list[$k]->last_view_time = date('Y-m-d H:i:s',$val->last_view_time);
            $list[$k]->url = "<span class='data-url'>".$list[$k]->url."</span>";
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
     * 平安建设设置【添加 or 修改】
     */
    public function peace_set(Request $request){
        $id = $request->get('peace_id',0);
        $type = $request->get('type','add');
        $peace_info = null;
        if(($type=='edit'||$type=='see')&&!empty($id)){
            $peace_info = DB::table('camera')
                ->where('id',$id)
                ->first();
        }

        if(!empty($peace_info)){
            $peace_info->last_view_time = date('Y-m-d H:i:s',$peace_info->last_view_time);
        }

        return view('admin.party.peace_set')
            ->with('title','平安建设')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','平安建设')
            ->with('peace_info', $peace_info)
            ->with('type',$type)
            ->with('menus',getMenus());
    }
     /**
     * 平安建设添加 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function peace_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['type'] = 0;
        $data['update_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('camera')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('camera')
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
     * 删除平安建设记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function peace_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('camera')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }

    /**
     * 双城创建
     */
    public function twins(){
        return view('admin.party.twins')
            ->with('title','双城创建')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','双城创建')
            ->with('menus',getMenus());
    }
    /**
     * 获得双城建设信息
     */
    public function twins_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $query = DB::table('camera')->where('type',1);

        if(!empty($sSearch)){
            $query->where('name','like','%'.$sSearch.'%');
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('id','desc')
            ->get();

        foreach($list as $k=>$val){
            $list[$k]->last_view_time = date('Y-m-d H:i:s',$val->last_view_time);
            $list[$k]->url = "<span class='data-url'>".$list[$k]->url."</span>";
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
     * 双城建设设置【添加 or 修改】
     */
    public function twins_set(Request $request){
        $id = $request->get('twins_id',0);
        $type = $request->get('type','add');
        $twins_info = null;
        if(($type=='edit'||$type=='see')&&!empty($id)){
            $twins_info = DB::table('camera')
                ->where('id',$id)
                ->first();
        }

        if(!empty($twins_info)){
            $twins_info->last_view_time = date('Y-m-d H:i:s',$twins_info->last_view_time);
        }

        return view('admin.party.twins_set')
            ->with('title','双城创建')
            ->with('homeNav','党建中心')
            ->with('homeLink','/party_index')
            ->with('subNav','')
            ->with('activeNav','双城创建')
            ->with('twins_info', $twins_info)
            ->with('type',$type)
            ->with('menus',getMenus());
    }

     /**
     * 双城创建添加 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function twins_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['type'] = 1;
        $data['update_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('camera')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('camera')
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
     * 删除双城创建记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function twins_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('camera')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }
}
