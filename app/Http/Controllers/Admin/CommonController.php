<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    /**
     * 获取事件类型
     */
    public function category(){
        //层级（1，2，3）,,,code:001002003
        //level=0 表示所有层级, code=0 表示匹配所有

        $level = Input::get('level');
        $code = Input::get('code');

        $query = DB::table('event_category');

        if(intval($level)){
            $query->where('level',$level);
        }

        if($code){
            $query->where('code','like',"$code%");
        }

        if($level >1 && $code == false){
            $list = [];
        }
        else {
            $list = $query->orderBy('code','asc')->get();
        }
        return responseToJson(0,'success',$list);
    }

    /**
     * 获取组织机构
     */
    public function org(){
        $type = Input::get('type');
        $query = DB::table('organization');
        if($type){
            $query->where('type',$type);
        }

        $list = $query->get();
        return responseToJson(0,'success',$list);
    }

    /**
     * 获取执法车
     */
    public function car(){
        $list = DB::table('prowl_car')->get();
        return responseToJson(0,'success',$list);
    }

    public function attachment_upload(Request $request){
        //判断请求中是否包含name=file的上传文件
        if(!$request->hasFile('file')){
            return responseToJson(1,'上传文件为空！');
        }
        $file = $request->file('file');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            return responseToJson(2,'文件上传出错！');
        }
        $destPath = realpath(base_path('storage/app/public'));
        if(!file_exists($destPath))
            mkdir($destPath,0777,true);
        //$filename = $file->getClientOriginalName();
        $extension = $file -> getClientOriginalExtension();
        $filename = session('user')->id.'-'.millisecond().'.'.$extension;
        if($file->move($destPath,$filename) == false){
            return responseToJson(3,'保存文件失败！');
        }


        //excel导入
        $filePath = 'storage/app/upload/'.$filename;
        $url = '/event_attachment?path='.$filename;

        return responseToJson(0,'success',$url);

    }
}
