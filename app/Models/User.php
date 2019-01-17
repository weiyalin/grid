<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;

class User extends Model
{
    //

    public static function get_menus($type=0){
        $user_id = session('user')->id;
        $role_id = DB::table('user_role')->where('user_id',$user_id)->value('role_id');
        $node_id_list = DB::table('role_node')->where('role_id',$role_id)->lists('node_id');
        if($type){
            $union = DB::table("node")->where('type',2)->orderBy('code','asc');
            $nodes = DB::table('node')->where('type',0)->whereIn('id',$node_id_list)->orderBy('code','asc')
                ->union($union)
                ->get();
            //dd($nodes);
        }
        else {
            $nodes = DB::table('node')->where('type',$type)->whereIn('id',$node_id_list)->orderBy('code','asc')->get();
        }
        return $nodes;
    }

    //存储几项用户信息到session
    public static function save_user_info_to_session(Request $request){
        session(['current_user_id'=>$request->user()->id]);
        session(['current_user_name'=>$request->user()->name]);
        session(['current_user_phone'=>$request->user()->phone]);
        session(['current_user_org_id'=>$request->user()->org_id]);
        session(['current_user_org_name'=>DB::table('organization')->where('id',$request->user()->org_id)->value('name')]);
    }

}
