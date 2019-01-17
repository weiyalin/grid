<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class ExamController extends Controller
{

    /**
     * 考核设置
     */
    public function exam_setting(){
        return view('admin.exam.exam_set_list')
            ->with('title','绩效考核-设置列表')
            ->with('homeNav','绩效考核')
            ->with('homeLink','/exam_setting')
            ->with('subNav','')
            ->with('activeNav','设置列表')
            ->with('menus',getMenus());
    }

    /**
     * 获取考核设置列表
     */
    public function exam_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $query = DB::table('exam_set');

        if(!empty($sSearch)){
            $query->where('name','like','%'.$sSearch.'%');
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
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
     * 考核项删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exam_del(Request $request){
        $exam_id = $request->input('exam_id');
        $del_res = DB::table('exam_set')->where('id',$exam_id)->delete();
        if(!empty($del_res)){
            return responseToJson(0,'','删除成功');
        }else{
            return responseToJson(1,'','修改失败');
        }
    }

    /**
     * @return mixed
     * 考核项添加或修改页面
     */
    public function exam_set(){
        $exam_id = Input::get('exam_id');
        if(!empty($exam_id)){//判断是否为修改
            $exam_data = DB::table('exam_set')->where('id',$exam_id)->first();
        }
        return view('admin.exam.exam_set')
            ->with('title','绩效考核-考核设置')
            ->with('homeNav','绩效考核')
            ->with('homeLink','/exam_setting')
            ->with('subNav','')
            ->with('activeNav','考核设置')
            ->with('exam_data',isset($exam_data)?$exam_data:'')
            ->with('menus',getMenus());
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 考核添加或修该
     */
    public function exam_set_handle(Request $request){
        $data = $request->all();
        $exam_id = $data['exam_id'];
        unset($data['exam_id']);
        $find_res = DB::table('exam_set')->where('name',$data['name'])->first();
        //判断考核项是否存在
        if((empty($exam_id)&&!empty($find_res))||(!empty($exam_id)&&!empty($find_res)&&$exam_id!=$find_res->id)){
            return responseToJson(1,'','该考核项已存在！');
        }
        if(!empty($exam_id)&&!empty($find_res)&&$exam_id==$find_res->id&&$data['name']==$find_res->name&&$data['score']==$find_res->score){
            return responseToJson(0,'','已成功修改');
        }
        if(empty($exam_id)){
            $add_res = DB::table('exam_set')->insert($data);
            if(empty($add_res)){
                return responseToJson(1,'','添加失败，请重试。');
            }else{
                return responseToJson(0,'','已成功添加');
            }
        }else{
            $set_res = DB::table('exam_set')->where('id',$exam_id)->update($data);
            if(empty($set_res)){
                return responseToJson(1,'','修改失败，请重试。');
            }else{
                return responseToJson(0,'','已成功修改');
            }
        }

    }


}
