<?php
/**
 * Created by PhpStorm.
 * User: xunmeng
 * Date: 17/5/13
 * Time: 上午12:42
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HawkeyeController extends Controller{

    /**
     * 报警视频页
     */
    public function hawkeye_video(){
        return view('admin.howkeye.hawkeye_video_list')
            ->with('title','鹰眼系统')
            ->with('homeNav','鹰眼系统')
            ->with('homeLink','/hawkeye_video')
            ->with('subNav','')
            ->with('activeNav','报警视频')
            ->with('menus',getMenus());
    }
}