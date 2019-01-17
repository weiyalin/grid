<?php
/**
 * Created by PhpStorm.
 * User: xunmeng
 * Date: 17/5/13
 * Time: 上午2:43
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class VisitController extends Controller{
    public function visit_people_list(){
        return view('admin.visit.people_list')
            ->with('title','上访人员信息')
            ->with('homeNav','信访中心')
            ->with('homeLink','/visit_people_list')
            ->with('subNav','')
            ->with('activeNav','上访人员信息')
            ->with('menus',getMenus());
    }
}