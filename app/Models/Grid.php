<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gis;
use DB;

class Grid extends Model
{
    protected $table = 'grid';
   //

    //根据实际住址修改其所属网格
    public static function address_grid(){
        $contact_address = DB::table('population')->select('id','contact_address')->get();
        foreach($contact_address as $val){
            $grid = self::address_to_grid(str_replace(' ','',$val->contact_address));
            if(!empty($grid)){
                $grid_data['grid_1'] = $grid[0];
                $grid_data['grid_2'] = $grid[1];
                $grid_data['grid_3'] = $grid[2];
                $grid_data['grid_4'] = $grid[3];
                DB::table('population')->where('id',$val->id)->update($grid_data);
            }
        }
    }


    /**
     * 根据地址得到其所属的网格
     * @param $address 家庭住址地址
     */
    public static function address_to_grid($address){
        $arr = Gis::geo_point($address);
        $lng = $arr['lng'];
        $lat = $arr['lat'];

        return self::event_grid_id($lng,$lat);
    }

    /**
     *  根据 事件经纬度 判断 所属 网格
     *  返回网格id  array(grid_1,grid_2,grid_3,grid_4);
     * @param $lng  经度
     * @param $lat  纬度
     * @return Array   [grid_1,grid_2,grid_3,grid_4]
     */
    public static function event_grid_id($lng,$lat){
        //赋初始值
        $grid_4 = 0;
        $grid_3 = 0;
        $grid_2 = 0;
        $grid_1 = 0;

        $point = new \stdClass();
        $point->Lng = floatval($lng);
        $point->Lat = floatval($lat);

        //四个级别依次查找，找到后直接跳出
        for($level=4; $level>0; $level--){
            $grid_info = self::get_grid_id($point,$level);
            if($grid_info) break;
        }

        //如果找到所属的网格
        if($grid_info){
            switch($grid_info['level']){
                case 4 :
                    $grid_4  = $grid_info['id'];
                    $grid_3  = $grid_info['parent_id'];
                    $tmp_grid = self::select('id','parent_id')->find($grid_3);
                    $grid_2 = $tmp_grid->parent_id;
                    $grid_1 = self::where('id',$grid_2)->value('parent_id');
                    break;
                case 3 :
                    $grid_3 = $grid_info['id'];
                    $grid_2 = $grid_info['parent_id'];
                    $grid_1 = DB::table('grid')->where('id',$grid_2)->value('parent_id');
                    break;
                case 2 :
                    $grid_2 = $grid_info['id'];
                    $grid_1 = $grid_info['parent_id'];
                    break;
                case 1 :
                    $grid_1 = $grid_info['id'];
                    break;
            }
        }

        return [$grid_1,$grid_2,$grid_3,$grid_4];
    }

    /**
     * 查找所属网格ID
     * @param $point    坐标对象
     * @param $level    要在那个级别的网格查找 1-4
     * @return array    返回网格id,parent_id，以及网格所在级别
     */
    public static function get_grid_id($point,$level){
        // 1. 取出所有 四级网格 的 坐标组
        $grids = self::where('level', $level)->select('id', 'parent_id', 'map')->get();

        // 2. 循环，查找属于哪一个网格
        foreach ($grids as $g) {
            $map = trim($g->map, ';');       //去掉可能的多余;
            $points_list = explode(';', $map);    //分割坐标

            $poly = [];
            foreach ($points_list as $p) {
                if($p == false){
                    continue;
                }
                $poly_point = new \stdClass();
                list($poly_point->Lng, $poly_point->Lat) = explode(',', $p);  //分割经纬度(经度在前，纬度在后)

                $poly[] = $poly_point;
                unset($poly_point);
            }

            //如果在某个网格内，直接返回其id
            if (Gis::is_inside($point, $poly)) {
                return array('id'=>$g->id,'parent_id'=>$g->parent_id,'level'=>$level);
            }
        }

        return false;
    }
}
