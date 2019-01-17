<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class VideoController extends Controller
{
    /**
     * 视频首页
     */
    public function index(){
        return view('admin.video.index')
            ->with('title','视频中心')
            ->with('homeNav','视频中心')
            ->with('homeLink','/video_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }


    public function index_map(){
        return view('admin.video.index_map')
            ->with('title','视频中心')
            ->with('homeNav','视频中心')
            ->with('homeLink','/video_index_map')
            ->with('subNav','')
            ->with('activeNav','视频中心')
            ->with('menus',getMenus());
    }

    /**
     * 视频首页地图数据
     */
    public function index_map_data(){
        $list = DB::table('prowl_car')->where('is_online',1)->get();
        return responseToJson(0,'success',$list);
    }

    public function video_center(){
        return view('admin.video.video_center')
            ->with('title','视频监控')
            ->with('homeNav','视频中心')
            ->with('homeLink','/video_center')
            ->with('subNav','')
            ->with('activeNav','视频监控')
            ->with('menus',getMenus());
    }
    /**
     * 执法车查看页面
     */
    public function car_map(){

    }

    /**
     * 执法车查询地图数据
     */
    public function car_map_data(){

    }

    /**
     * 执法车轨迹页面
     */
    public function car_history(){

    }

    /**
     * 执法车轨迹地图数据
     */
    public function car_history_data(){

    }

    /**
     * 执法车视频回放页面
     */
    public function car_replay(){

    }

    /**
     * 执法车视频回放数据
     */
    public function car_replay_data(){

    }

    /**
     * 执法车管理页面
     */
    public function car_manage(){

    }

    /**
     * 执法车管理列表
     */
    public function car_manage_list(){

    }

    /**
     * 执法车编辑添加页面
     */
    public function car_manage_view(){

    }

    /**
     * 执法车保存添加
     */
    public function car_manage_save(){

    }

    /**
     * 执法车删除
     */
    public function car_manage_delete(){

    }


}
