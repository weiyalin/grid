<?php
/**
 * 用来做地址选择的三级联动菜单
 * 目前做的是返回json数据
 */
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DistrictController extends Controller
{
    //
    /**
     * 获取默认信息:河南省+新乡市+牧野区
     *  1、获取全部的省，默认选择河南省(id=1)
     *  2、获取全部河南省下的市区（默认选择新乡市 id=85）
     *  3、获取全部新乡市下的分区（默认选择牧野区 id=）
     *  type : 0:默认获取json信息 1:获取数组
     */
    public function getDefaultData($type=0){
        //DB::connection()->enableQueryLog();
        $data['provinces'] = DB::table('dict_county')->where('type',0)->select('id','name')->get();
        $data['citys']     = DB::table('dict_county')->where('type',1)->select('id','name')->where('pid',1)->get();
        $data['districts'] = DB::table('dict_county')->where('type',2)->select('id','name')->where('pid',85)->get();
        //var_dump(DB::getQueryLog());
        if($type == 1){
            return $data;
        }
        return json_encode($data);
    }
    /**
     * 获取"省"信息
     */
    public function getProvice(){
        // 'type' => 0; 为省
        $province = DB::table('dict_county')->where('type',0)->get();
        return json_encode($province);
    }

    /**
     * 根据“省”id获取“市”
     */
    public function getCity(Request $request){
        $pid = intval(trim($request->input('province_id')));
        $citys = DB::table('dict_county')->where('type',1)->where('pid',$pid)->get();
        return json_encode($citys);
    }

    /**
     * 根据市‘id’获取‘区’
     */
    public function getDistrict(Request $request){
        $cid = intval(trim($request->input('city_id')));
        $districts = DB::table('dict_county')->where('type',2)->where('pid',$cid)->get();
        return json_encode($districts);
    }
}
