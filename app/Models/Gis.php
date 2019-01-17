<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gis extends Model
{
    /**
     * @param $point 位置点
     * @param $point_list 多边形(网格地图)
     * @return bool 是否在网格中
     * 判断一个点是否在多边形内部
     */
    public static function is_inside($point,$point_list)
    {
        $count = count($point_list);
        if($count < 3)
        {
            return false;
        }

        $result = false;

         for($i = 0, $j = $count - 1; $i < $count; $i++)
         {
             $p1 = $point_list[$i];
             $p2 = $point_list[$j];

            if($p1->Lat < $point->Lat && $p2->Lat >= $point->Lat || $p2->Lat < $point->Lat && $p1->Lat >= $point->Lat)
            {
                if($p1->Lng + ($point->Lat - $p1->Lat) / ($p2->Lat - $p1->Lat) * ($p2->Lng - $p1->Lng) < $point->Lng)
                {
                    $result = !$result;
                }
            }
            $j = $i;
         }
         return $result;
    }

    /**
     * @param $point Point 位置点
     * 根据经纬度,获取address信息
     */
    public static function geo_address($point){
        //百度Geo查询每天6000次,预留多个备用
        $ak = 'ONMZAsjQwi66QAWVcwKe5ELFS35ZVFkp';
        $ak1='U57SUbwg9GqV4oMCGOezCV6FTclMgHaR';
        $ak2='PaXp1ZxGARxpxHk4zEv3xgPuV5veoYuT';

        $url = "http://api.map.baidu.com/geocoder/v2/?ak=$ak&location=$point->Lat,$point->Lng&output=json&pois=1";

        $result = curl($url);
        //dd($result);
        $obj = json_decode($result,true);

        if(intval($obj['status']) != 0){
            $url = "http://api.map.baidu.com/geocoder/v2/?ak=$ak1&location=$point->Lat,$point->Lng&output=json&pois=1";
            $result = curl($url);
            $obj = json_decode($result,true);
        }
        if(intval($obj['status']) != 0){
            $url = "http://api.map.baidu.com/geocoder/v2/?ak=$ak2&location=$point->Lat,$point->Lng&output=json&pois=1";
            $result = curl($url);
            $obj = json_decode($result,true);
        }

        if(intval($obj['status']) != 0) {
            return responseToJson(1,'超过百度地图的每日查询次数限制');
        }

        $formatted_address = $obj['result']['formatted_address'];

        //dd($formatted_address);

        return responseToJson(0,'success',$formatted_address);
    }


    /**
     * @param $address
     * @return \Illuminate\Http\JsonResponse
     * 根据位置获得经纬度
     */
    public static function geo_point($address){
        //百度Geo查询每天6000次,预留多个备用
        $ak = 'ONMZAsjQwi66QAWVcwKe5ELFS35ZVFkp';
        $ak1='U57SUbwg9GqV4oMCGOezCV6FTclMgHaR';
        $ak2='PaXp1ZxGARxpxHk4zEv3xgPuV5veoYuT';

        $url = "http://api.map.baidu.com/geocoder/v2/?ak=$ak&address=$address&output=json";

        $result = curl($url);
//        dd($result);
        $obj = json_decode($result,true);

        if(intval($obj['status']) != 0){
            $url = "http://api.map.baidu.com/geocoder/v2/?ak=$ak1&address=$address&output=json";
            $result = curl($url);
            $obj = json_decode($result,true);
        }
        if(intval($obj['status']) != 0){
            $url = "http://api.map.baidu.com/geocoder/v2/?ak=$ak2&address=$address&output=json";
            $result = curl($url);
            $obj = json_decode($result,true);
        }

        $data=array();
        if(intval($obj['status']) == 0) {
            $data=$obj['result']['location'];
        }else{
            $data['lng']=0;
            $data['lat']=0;
        }
        return $data;
    }


    /**
     * @param $address 地址字符串
     * Geo地址分隔,结果:xx省,xx市,xx区县,xxx
     */
    public static function split_location($address){
        //分隔符
        $province1 = '省';
        $province2 = '自治区';
        $city1 = '市';
        $area1 = '区';
        $area2 = '县';

        $p = explode($province1,$address);
        if(count($p) != 2){
            $p = explode($province2,$address);
            $province = $p[0].$province2;
        }
        else {
            $province = $p[0].$province1;
        }

        $c = explode($city1,$p[1]);
        //dd($c);
        $city = $c[0].$city1;
        $a = explode($area1,$c[1]);
        if(count($a) != 2){
            $a = explode($area2,$c[1]);
            $area = $a[0].$area1;
        }
        else {
            $area = $a[0].$area2;
        }

        if(count($a) !=2){
            $addr = '';
        }
        else {
            $addr = $a[1];
        }

        $result = ['province'=>$province,'city'=>$city,'area'=>$area,'addr'=>$addr];

        return responseToJson(0,'success',$result);

    }




}
