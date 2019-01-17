<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;
use Maatwebsite\Excel\Facades\Excel;


class Population extends Model
{

    /**
     * @param $file 文件地址
     * @return string
     * 导入人口功能
     */
    public static function import_population($file){
//        $file = 'public/admin/template/'.'person_template.xls';

        $reader = Excel::load($file);
        $results = $reader->getsheet(0)->toArray();
        if(empty($results)){
            return false;
        }
        for($i=1;$i<count($results);$i++){
            if(!empty($results[$i][2])){
                $find_res = DB::table('population')->where('card_code',$results[$i][2])->first();
                if(!empty($find_res)){

                }else{
                    $population_data['create_time'] = $population_data['update_time'] = time();
                    $population_data['name'] = $results[$i][0]?$results[$i][0]:'';
                    $card_category='01';
                    switch($results[$i][1]){
                        case '身份证':
                            $card_category='01';break;
                        case '护照':
                            $card_category='02';break;
                        case '居住证':
                            $card_category='03';break;
                        case '军官证':
                            $card_category='04';break;
                        case '出生证':
                            $card_category='05';break;
                        case '绿卡':
                            $card_category='06';break;
                        case '港澳通行证':
                            $card_category='07';break;
                        case '其他证件':
                            $card_category='08';break;
                    }
                    $population_data['card_category']=$card_category;
                    $population_data['card_code'] = $results[$i][2];
                    $population_data['nationality']=$results[$i][3]?$results[$i][3]:'';
                    if(empty($results[$i][4])){
                        $population_data['sex'] = 9;
                    }elseif($results[$i][4]=='男'){
                        $population_data['sex'] = 1;
                    }elseif($results[$i][4]=='女'){
                        $population_data['sex'] = 2;
                    }else{
                        $population_data['sex'] = 0;
                    }
                    $population_data['birthday'] = strtotime($results[$i][5])*1000;
                    $population_data['nation'] = $results[$i][6]?$results[$i][6]:'';
                    if($results[$i][7]=='已婚'){
                        $population_data['marital_status'] = 20;//婚姻
                    }elseif($results[$i][7]=='未婚'){
                        $population_data['marital_status'] = 10;//婚姻
                    }elseif($results[$i][7]=='初婚'){
                        $population_data['marital_status'] = 21;//婚姻
                    }elseif($results[$i][7]=='再婚'){
                        $population_data['marital_status'] = 22;//婚姻
                    }elseif($results[$i][7]=='复婚'){
                        $population_data['marital_status'] = 23;//婚姻
                    }elseif($results[$i][7]=='丧偶'){
                        $population_data['marital_status'] = 30;//婚姻
                    }elseif($results[$i][7]=='离婚'){
                        $population_data['marital_status'] = 40;//婚姻
                    }else{
                        $population_data['marital_status'] = 90;//婚姻
                    }
                    $card_category='';//学历
                    switch($results[$i][8]){
                        case '研究生':
                            $card_category='10';break;
                        case '研究生毕业':
                            $card_category='11';break;
                        case '研究生肄业':
                            $card_category='19';break;
                        case '大学本科':
                            $card_category='20';break;
                        case '大学毕业':
                            $card_category='21';break;
                        case '相当大学毕业':
                            $card_category='28';break;
                        case '大学肄业':
                            $card_category='29';break;
                        case '中专/中技':
                            $card_category='40';break;
                        case '专科毕业':
                            $card_category='31';break;
                        case '相当专科毕业':
                            $card_category='38';break;
                        case '专科肄业':
                            $card_category='39';break;
                        case '大学专科和专科学校':
                            $card_category='30';break;
                        case '中专毕业':
                            $card_category='41';break;
                        case '中技毕业':
                            $card_category='42';break;
                        case '相当中专或中技毕业':
                            $card_category='48';break;
                        case '中专或中技肄业':
                            $card_category='49';break;
                        case '技工学校':
                            $card_category='50';break;
                        case '技工学校毕业':
                            $card_category='51';break;
                        case '技工学校肄业':
                            $card_category='59';break;
                        case '高中':
                            $card_category='60';break;
                        case '高中毕业':
                            $card_category='61';break;
                        case '职业高中毕业':
                            $card_category='62';break;
                        case '农业高中毕业':
                            $card_category='63';break;
                        case '相当高中毕业':
                            $card_category='68';break;
                        case '高中肄业':
                            $card_category='69';break;
                    }
                    $population_data['culture_degree']=$card_category;
                    if($results[$i][9]=='农村'){
                        $population_data['family_type'] = '农村';
                    }else{
                        $population_data['family_type'] = '城市';
                    }
                    $population_data['family_province'] = $results[$i][10]?$results[$i][10]:'';
                    $population_data['family_city'] = $results[$i][11]?$results[$i][11]:'';
                    $population_data['family_district'] = $results[$i][12]?$results[$i][12]:'';
                    $population_data['family_address'] = $results[$i][13]?$results[$i][13]:'';
                    $population_data['contact_address'] = $results[$i][14]?$results[$i][14]:'';
                    $population_data['contact_phone'] = $results[$i][15]?$results[$i][15]:'';
                    $population_data['domicile_province'] = $results[$i][16]?$results[$i][16]:'';
                    $population_data['domicile_city'] = $results[$i][17]?$results[$i][17]:'';
                    $population_data['domicile_district'] = $results[$i][18]?$results[$i][18]:'';
                    $population_data['domicile_address'] = $results[$i][19]?$results[$i][19]:'';
                    $population_data['contact_postcode'] = $results[$i][20]?$results[$i][20]:'';
                    $population_data['memo'] = $results[$i][21]?$results[$i][21]:'';

                    $population_data['is_emphases']=self::switch_type($results[$i][22]);
                    $population_data['is_special']=self::switch_type($results[$i][23]);
                    $population_data['is_fixed']=self::switch_type($results[$i][24]);
                    $population_data['is_allowance']=self::switch_type($results[$i][25]);
                    $population_data['is_invalidism']=self::switch_type($results[$i][26]);
                    $population_data['is_older']=self::switch_type($results[$i][27]);
                    $population_data['is_veteran']=self::switch_type($results[$i][28]);

                    $population_data['relation'] = $results[$i][31]?$results[$i][31]:'';
                    $family_id = DB::table('population')->insertGetid($population_data);
                    if($results[$i][29]=='是'){
                        if(!empty($family_id)){//是户主
                            DB::table('population')->where('card_code',$results[$i][2])->update(array('family_id'=>$family_id));
                        }
                    }else{
                        if(!empty($results[$i][30])){
                            $find_family_res = DB::table('population')->where('card_code',$results[$i][30])->first();
                            if(!empty($find_family_res)){
                                DB::table('population')->where('card_code',$results[$i][2])->update(array('family_id'=>$find_family_res->id));
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     *
     */
    public static function switch_type($type){
        $res = 0;
        switch($type){
            case '是':
                $res=1;
                break;
            case '否':
                $res=2;
                break;
        }
        return $res;
    }

    /**
     * @param string $file 文件地址
     * @param string $family_type 户籍类型
     * 低保
     */
    public static function lowest_ensure($file,$family_type='城市'){
//        $file = 'storage/app/public/'.'1-1470127446589.xls';
        $results = Excel::load($file)->getSheet(0)->toArray();
        for($i=0;$i<count($results);$i++){
            if(!empty($results[$i][0])){
                $find_res = DB::table('population')->where('card_code',$results[$i][0])->first();
                if(!empty($find_res)){
                    $family_id = $find_res['id'];
                    $is_allowance=1;
                    $save_family = DB::table('population')->where('card_code',$results[$i][0])->update(array('family_id'=>$family_id,'is_allowance'=>$is_allowance));
                    if(empty($save_family)){
                        continue;
                    }
                }else{
                    $population_data['create_time'] = $population_data['update_time'] = time();
                    $population_data['nationality']='中国';
                    $population_data['card_category']='01';
                    $population_data['is_fixed']=1;
                    $population_data['is_allowance']=1;
                    $population_data['family_type'] = $family_type;
                    $population_data['card_code'] = $results[$i][0];
                    $population_data['name'] = $results[$i][1];
                    if(empty($results[$i][2])){
                        $population_data['sex'] = 9;
                    }elseif($results[$i][2]=='男'){
                        $population_data['sex'] = 1;
                    }elseif($results[$i][2]=='女'){
                        $population_data['sex'] = 2;
                    }else{
                        $population_data['sex'] = 0;
                    }
                    $population_data['nation'] = $results[$i][3];
                    $population_data['marital_status'] = 90;//婚姻
                    $population_data['family_province'] = $population_data['domicile_province'] = '河南省';
                    $population_data['family_city'] = $population_data['domicile_city'] = '新乡市';
                    $population_data['family_district'] = $population_data['domicile_district'] = '牧野区';
                    $population_data['family_address'] = $population_data['domicile_address'] = $results[$i][4];
                    $population_data['contact_address'] = $population_data['domicile_province']." ".$population_data['domicile_city']." ".
                        $population_data['domicile_district']." ".$population_data['domicile_address'];

                    $family_id = DB::table('population')->insertGetid($population_data);
                    if(!empty($family_id)){
                        DB::table('population')->where('card_code',$results[$i][0])->update(array('family_id'=>$family_id));
                    }
                    if($results[$i][5]>1){
                        for($j=0;$j<$results[$i][5]-1;$j++){
                            $find_res = DB::table('population')->where('card_code',$results[$i][7+$j*2])->first();
                            if(!empty($find_res)){
                                DB::table('population')->where('card_code',$results[$i][7+$j*2])->update(array('family_id'=>$family_id,'is_allowance'=>1));
                            }else{
                                $population_data['sex'] = 9;
                                if(!empty($results[$i][7+$j*2])&&!empty($results[$i][8+$j*2])){
                                    $population_data['name'] = $results[$i][7+$j*2];
                                    $population_data['card_code'] = $results[$i][8+$j*2];
                                    $population_data['family_id'] = $family_id;
                                    DB::table('population')->insert($population_data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * @param string $file
     * 老龄人口
     */
    public static function aging($file){
//        $file = 'storage/app/public/'.'test.xls';
        $results = Excel::load($file)->getSheet(0)->toArray();
        for($i=0;$i<count($results);$i++){
            if(!empty($results[$i][3])){
                $find_res = DB::table('population')->where('card_code',$results[$i][3])->first();
                if(!empty($find_res)){
                    DB::table('population')->where('card_code',$results[$i][3])->update(array('is_older'=>1));
                }else{
                    $population_data['create_time'] = $population_data['update_time'] = time();
                    $population_data['nationality']='中国';
                    $population_data['card_category']='01';
                    $population_data['is_fixed']=1;
                    $population_data['is_older']=1;


                    $population_data['card_code'] = $results[$i][3];
                    $population_data['name'] = $results[$i][0];
                    if(empty($results[$i][1])){
                        $population_data['sex'] = 9;
                    }elseif($results[$i][1]=='男'){
                        $population_data['sex'] = 1;
                    }elseif($results[$i][1]=='女'){
                        $population_data['sex'] = 2;
                    }else{
                        $population_data['sex'] = 0;
                    }
                    $population_data['marital_status'] = 90;//婚姻
                    $population_data['family_province'] = $population_data['domicile_province'] = '河南省';
                    $population_data['family_city'] = $population_data['domicile_city'] = '新乡市';
                    $population_data['family_district'] = $population_data['domicile_district'] = '牧野区';

                    $str = $results[$i][4];
                    $str = str_replace('新乡市','',$str);
                    $str = str_replace('牧野区','',$str);

                    $population_data['family_address'] = $population_data['domicile_address'] = $str;
                    $population_data['contact_address'] = $population_data['domicile_province']." ".$population_data['domicile_city']." ".
                        $population_data['domicile_district']." ".$population_data['domicile_address'];


                    $birthday = $results[$i][2];
                    $birthday = preg_replace('/(年|月|日|-)+/','/',$birthday);
                    $birthday = preg_replace('/^(\d{4})(\d{2})$/','$1/$2/0',$birthday);
                    $birthday = preg_replace('/^(\d{4})(\d{2})(\d{2})$/','$1/$2/$3',$birthday);
                    $birthday = str_replace('.','/',$birthday);
                    if(strlen($birthday)<=7){
                        if(substr($birthday,-1,1)!='/'){
                            $birthday.='/1';
                        }else{
                            $birthday.='1';
                        }
                    }elseif(strlen($birthday)==8&&substr($birthday,-1,1)=='/'){
                        $birthday.='1';
                    }
                    $population_data['birthday'] = strtotime($birthday)*1000;
                    DB::table('population')->insert($population_data);
                }
            }
        }
    }


    /**
     * @param string $file 文件
     * @param string 人员类型
     * 优待
     */
    public static function preferential_treatment($file,$population_data){
//        $file = 'storage/app/public/'.'test1.xls';
        $results = Excel::load($file)->getSheet(0)->toArray();
        for($i=0;$i<count($results);$i++){
            if(!empty($results[$i][2])){
                $find_res = DB::table('population')->where('card_code',$results[$i][2])->first();
                if(!empty($find_res)){
                    DB::table('population')->where('card_code',$results[$i][2])->update($population_data);
                }else{
                    $population_data['create_time'] = $population_data['update_time'] = time();
                    $population_data['nationality']='中国';
                    $population_data['nation']=$results[$i][4];//民族
                    $population_data['card_category']='01';
                    $population_data['is_fixed']=1;


                    $population_data['card_code'] = $results[$i][2];
                    $population_data['name'] = $results[$i][0];
                    if(empty($results[$i][1])){
                        $population_data['sex'] = 9;
                    }elseif($results[$i][1]=='男'){
                        $population_data['sex'] = 1;
                    }elseif($results[$i][1]=='女'){
                        $population_data['sex'] = 2;
                    }else{
                        $population_data['sex'] = 0;
                    }

                    if($results[$i][5]=='已婚'){
                        $population_data['marital_status'] = 20;//婚姻
                    }elseif($results[$i][5]=='未婚'){
                        $population_data['marital_status'] = 10;//婚姻
                    }elseif($results[$i][5]=='初婚'){
                        $population_data['marital_status'] = 21;//婚姻
                    }elseif($results[$i][5]=='再婚'){
                        $population_data['marital_status'] = 22;//婚姻
                    }elseif($results[$i][5]=='复婚'){
                        $population_data['marital_status'] = 23;//婚姻
                    }elseif($results[$i][5]=='丧偶'){
                        $population_data['marital_status'] = 30;//婚姻
                    }elseif($results[$i][5]=='离婚'){
                        $population_data['marital_status'] = 40;//婚姻
                    }else{
                        $population_data['marital_status'] = 90;//婚姻
                    }

                    $population_data['family_province'] = $population_data['domicile_province'] = '河南省';
                    $population_data['family_city'] = $population_data['domicile_city'] = '新乡市';
                    $population_data['family_district'] = $population_data['domicile_district'] = '牧野区';
                    $str = $results[$i][7];
                    $str = mb_substr($str,9);
                    $population_data['family_address'] =$str;
                    $str = $results[$i][8];
                    $str = mb_substr($str,9);
                    $population_data['domicile_address'] = $str;
                    $population_data['contact_address'] = $population_data['domicile_province']." ".$population_data['domicile_city']." ".
                        $population_data['domicile_district']." ".$population_data['domicile_address'];


                    $birthday = $results[$i][3];
                    $birthday = preg_replace('/(年|月|日|-)+/','/',$birthday);
                    $birthday = preg_replace('/^(\d{4})(\d{2})$/','$1/$2/0',$birthday);
                    $birthday = preg_replace('/^(\d{4})(\d{2})(\d{2})$/','$1/$2/$3',$birthday);
                    $birthday = str_replace('.','/',$birthday);
                    if(strlen($birthday)<=7){
                        if(substr($birthday,-1,1)!='/'){
                            $birthday.='/1';
                        }else{
                            $birthday.='1';
                        }
                    }elseif(strlen($birthday)==8&&substr($birthday,-1,1)=='/'){
                        $birthday.='1';
                    }

                    $population_data['birthday'] = strtotime($birthday)*1000;
                    if(!empty($results[$i][9])){
                        $population_data['contact_phone'] = $results[$i][9];
                    }

                    if($results[$i][6]=='农业'){
                        $population_data['family_type'] = '农业';
                    }else{
                        $population_data['family_type'] = '城市';
                    }

                    DB::table('population')->insert($population_data);
                }
            }
        }
    }


    /**
     * @param string $file
     * 老龄人口~~养老院
     */
    public static function rest($file){
//        $file = 'storage/app/public/'.'test.xls';
        $results = Excel::load($file)->getSheet(0)->toArray();
        for($i=0;$i<count($results);$i++){
            if(!empty($results[$i][3])){
                $find_res = DB::table('population')->where('card_code',$results[$i][3])->first();
                if(!empty($find_res)){
                    DB::table('population')->where('card_code',$results[$i][3])->update(array('is_older'=>1));
                }else{
                    $population_data['create_time'] = $population_data['update_time'] = time();
                    $population_data['nationality']='中国';
                    $population_data['card_category']='01';
                    $population_data['is_fixed']=1;
                    $population_data['is_older']=1;


                    $population_data['card_code'] = $results[$i][3];
                    $population_data['name'] = $results[$i][0];
                    if(empty($results[$i][1])){
                        $population_data['sex'] = 9;
                    }elseif($results[$i][1]=='男'){
                        $population_data['sex'] = 1;
                    }elseif($results[$i][1]=='女'){
                        $population_data['sex'] = 2;
                    }else{
                        $population_data['sex'] = 0;
                    }
                    $population_data['marital_status'] = 90;//婚姻
                    $population_data['family_province'] = $population_data['domicile_province'] = '河南省';
                    $population_data['family_city'] = $population_data['domicile_city'] = '新乡市';
                    $population_data['family_district'] = $population_data['domicile_district'] = '牧野区';

                    $str = $results[$i][4];
                    $str = str_replace('新乡市','',$str);
                    $str = str_replace('牧野区','',$str);

                    $population_data['family_address'] = $population_data['domicile_address'] = $str;
                    $population_data['contact_address'] = $population_data['domicile_province']." ".$population_data['domicile_city']." ".
                        $population_data['domicile_district']." ".$population_data['domicile_address'];


                    $birthday = $results[$i][2];
                    $birthday = preg_replace('/(年|月|日|-)+/','/',$birthday);
                    $birthday = preg_replace('/^(\d{4})(\d{2})$/','$1/$2/0',$birthday);
                    $birthday = preg_replace('/^(\d{4})(\d{2})(\d{2})$/','$1/$2/$3',$birthday);
                    $birthday = str_replace('.','/',$birthday);
                    if(strlen($birthday)<=7){
                        if(substr($birthday,-1,1)!='/'){
                            $birthday.='/1';
                        }else{
                            $birthday.='1';
                        }
                    }elseif(strlen($birthday)==8&&substr($birthday,-1,1)=='/'){
                        $birthday.='1';
                    }
                    $population_data['birthday'] = strtotime($birthday)*1000;
                    $population_data['contact_phone'] = $results[$i][5];
                    DB::table('population')->insert($population_data);
                }
            }
        }
    }

}