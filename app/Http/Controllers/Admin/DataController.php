<?php
/**
 * 数据中心
 */

namespace App\Http\Controllers\Admin;

use App\Models\Population;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Excel;

class DataController extends Controller
{
    //首页
    public function index(){
        return view('admin.data.index')
            ->with('title','数据中心')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }


    /**
     * 常住人口列表页
     */
    public function resident_population(){
        $where['key'] = 'is_fixed';     //所有页面均以此值判断来源页面及基本搜索条件
        return view('admin.data.resident')
            ->with('title','常住人口管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','常住人口管理')
            ->with('menus',getMenus())
            ->with('where',$where);
    }

    /**
     * 详细信息 -- ajax调用
     */
    public function population_detail(Request $request){
        $id = intval($request->input('id'));
        //获取个人信息
        $person = DB::table('population')->where('id',$id)->first();
        //组装个人信息
        $person = $this->convertPersonInfo($person);

        //网格
        $tmp = [$person->grid_1, $person->grid_2, $person->grid_3, $person->grid_4];
        $person->grid_1 = $person->grid_2 = $person->grid_3 = $person->grid_4 = '';
        foreach($tmp as $v){
            if($v != 0){
                $grids[] = $v;
            }else{
                break;  //如果出现0，之后的grid,不再获取
            }
        }
        if(isset($grids) && $grids){
            $gridsName = DB::table('grid')->select('level','name')->whereIn('id',$grids)->get();
            //var_dump($gridsName);
            foreach($gridsName as $v){
                switch($v->level){
                    case 1 :
                        $person->grid_1     = $v->name;
                        break;
                    case 2 :
                        $person->grid_2     = $v->name;
                        break;
                    case 3 :
                        $person->grid_3     = $v->name;
                        break;
                    case 4 :
                        $person->grid_4     = $v->name;
                        break;
                }
            }
        }

        //家庭人口信息
        if($person->family_id>0){
            $family = DB::table('population')->where('status',0)->where('family_id',$person->family_id)->get();
        }
        else {
            $family = [];
        }


        if($family) {
            foreach ($family as $k => $v) {
                $family_info[$k]['name'] = $v->name;         //名字
                $family_info[$k]['relation'] = $v->relation;     //关系
                $family_info[$k]['culture_degree'] = $this->getCultureDegreeName($v->culture_degree);    //学历
                $family_info[$k]['card_category'] = $this->getCardCategoryName($v->card_category);      //证件类型
                $family_info[$k]['card_code'] = $v->card_code;    //证件号码
                $family_info[$k]['nation'] = $v->nation;       //民族

                $extra = '';
                $extra .= $v->is_emphases == 1 ? '重点人群/' : '';
                $extra .= $v->is_special == 1 ? '特殊人群/' : '';
                $extra .= $v->is_allowance == 1 ? '低保人群/' : '';
                $extra .= $v->is_invalidism == 1 ? '伤残人群/' : '';
                $extra .= $v->is_older == 1 ? '老龄人/' : '';
                $extra .= $v->is_veteran == 1 ? '退伍军人/' : '';

                $family_info[$k]['extra'] = rtrim($extra, '/');
            }
            $person->family_info = $family_info;
        }
        echo json_encode($person);
    }

    /**
     * 组装个人信息（把int转换成对应的名称）
     * 传入$person对象
     */
    private function convertPersonInfo($person){
        $person->card_category  = $this->getCardCategoryName($person->card_category);    //证件类型
        $person->marital_status = $this->getMaritalStatusName($person->marital_status); //婚姻状况
        $person->culture_degree = $this->getCultureDegreeName($person->culture_degree); //学历
        $person->birthday       = date('Y-m-d H:i:s',$person->birthday/1000);                //生日
        $person->is_fixed       = $this->getFixedName($person->is_fixed);               //人口状态
        $person->is_householder = $person->id == $person->family_id ? '是' : '否';        //户主？
        $person->domicile_address   = $person->domicile_province.' '.$person->domicile_city.' '.$person->domicile_district.' '.$person->domicile_address;
        $person->family_address     = $person->family_province.' '.$person->family_city.' '.$person->family_district.' '.$person->family_address;
        unset($person->domicile_province);  unset($person->family_province);
        unset($person->domicile_city);      unset($person->family_city);
        unset($person->domicile_district);  unset($person->family_district);
        $person->sex            = $this->getSexName($person->sex);
        $person->is_emphases    = $this->getCommonStatusName($person->is_emphases);
        $person->is_special     = $this->getCommonStatusName($person->is_special);

        $person->is_allowance   = $this->getCommonStatusName($person->is_allowance);
        $person->is_invalidism  = $this->getCommonStatusName($person->is_invalidism);
        $person->is_older       = $this->getCommonStatusName($person->is_older);
        $person->is_veteran     = $this->getCommonStatusName($person->is_veteran);

        return $person;
    }

    /**
     * 编辑页面
     */
    public function population_edit(){
        //组户（如果id=family_id即为户主，不等为家庭成员）
        $id = intval(Input::get('id'));
        // 1 、获取个人信息
        $person = DB::table('population')->where('id',$id)->first();

        $label = DB::table('person_relation_label as p')
            ->join('label as l','p.label_id','=','l.id')
            ->select('l.name')
            ->where('population_id',$id)
            ->get();

        $label_data = array();
        foreach($label as $val){
            array_push($label_data,$val->name);
        }
        $label_data = implode(',',$label_data);

        // 2 、组装信息
        // 2.1 出生日期
        $person->birthday = date('Y-m-d H:i:s',$person->birthday/1000);
        // 2.2 是否为户主
        $person->id == $person->family_id ? $person->is_householder = true:$person->is_householder =false;
        // 2.2.1 如果不是户主，查找户主的身份证号信息
        if(!$person->is_householder){
            $person->householder_id = DB::table('population')->where('id',$person->family_id)->value('card_code');
        }else{
            $person->householder_id = '';
        }

        /*// 3、网格信息
            //tips:存在都是 0 、或 部分是0 的可能
        $tmp = [$person->grid_1, $person->grid_2, $person->grid_3, $person->grid_4];
        foreach($tmp as $v){
            if($v != 0){
                $grids[] = $v;
            }else{
                break;  //如果出现0，之后的grid,不再获取
            }
        }

        if(isset($grids) && $grids){
            $gridsName = DB::table('grid')->select('id','level','name')->whereIn('id',$grids)->get();
            //var_dump($gridsName);
            foreach($gridsName as $v){
                switch($v->level){
                    case 1 :
                        $person->grid_1     = $v->name;
                        $person->grid_1_id  = $v->id;
                        break;
                    case 2 :
                        $person->grid_2     = $v->name;
                        $person->grid_2_id  = $v->id;
                        break;
                    case 3 :
                        $person->grid_3     = $v->name;
                        $person->grid_3_id  = $v->id;
                        break;
                    case 4 :
                        $person->grid_4     = $v->name;
                        $person->grid_4_id  = $v->id;
                        break;
                }
            }
        }*/

        //获取证件类型列表
        $cardCategoryList = $this->getCardCategoryList();
        //获取婚姻状况列表
        $maritalStatusList = $this->getMaritalStatusList();
        //获取学历信息列表
        $cultureDegreeList = $this->getCultureDegreeList();
        //获取省份的信息
        $provinces = DB::table('dict_county')->where('pid',0)->get();

        return view('admin.data.edit')
            ->with('title','常住人口管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','常住人口管理')
            ->with('person',$person)
            //->with('family',$family)
            ->with('menus',getMenus())
            ->with('cardCategoryList',$cardCategoryList)
            ->with('maritalStatusList',$maritalStatusList)
            ->with('cultureDegreeList',$cultureDegreeList)
            ->with('provinces',$provinces)
            ->with('label_data',$label_data);
    }

    /**
     * 人口列表
     */
    public function resident_population_list(Request $request){
        $sEcho = intval(trim($request->input("sEcho")));
        $iDisplayStart = intval(trim($request->input("iDisplayStart")));
        $iDisplayLength = intval(trim($request->input("iDisplayLength")));
        $sSearch = trim($request->input("sSearch"));
        $basic_condition = trim($request->input('basic_condition')); //基本搜索条件  ：用来作为来源也的的判断，以此作为基本的搜索条件
        $advanceSearch = $request->input('aSearch'); //是否是高级搜索

        $query = DB::table('population');

        //  1.1 基本搜索条件 -- 代表来源页
            //basic_condition 基本搜索条件  ：用来作为来源也的的判断，以此作为基本的搜索条件
        //if($basic_condition){ //所有页面都有$basic_condition
            switch($basic_condition){
                case 'is_householder' :     //是户主  id=family_id
                    $query->whereRaw('`id`=`family_id`')->where('status',0);
                    break;
                case 'is_fixed' :           //固定人口
                    $query->where('is_fixed',1)->where('status',0);
                    break;
                case 'is_fluid' :           //流动人口
                    $query->where('is_fixed',2)->where('status',0);
                    break;
                case 'status'   :           //回收站
                    $query->where('status',1);
                    break;
                default :
                    $query->where($basic_condition,1)->where('status',0);  // 重点/特殊/低保/老龄/退伍等都可用

            }
        //}

        //  1.2 基本搜索条件
        //$query->where('status',0);

        //  2.1 自带搜索（框）条件
        if($sSearch != '') {
            $query->where('name','like',$sSearch.'%');
        }
        //  2.2 高级搜索
        if($advanceSearch){
            if($name = trim($request->input('name'))){
                $query->where('name','like',$name.'%');
            }
            if($card_code = trim($request->input('card_code'))){
                $query->where('card_code','like',$card_code.'%');
            }
            if($contact_phone = trim($request->input('contact_phone'))){
                $query->where('contact_phone','like',$contact_phone.'%');
            }
            if($birthday = trim($request->input('birthday'))){
                list($start,$end) = explode('--',$birthday);
                $start = strtotime($start)*1000;
                $end = strtotime($end)*1000;
                $query->whereBetween('birthday',[$start,$end]);
            }
            if($grid_1 = intval($request->input('grid_1'))){
                $query->where('grid_1',$grid_1);
            }
            if($grid_2 = intval($request->input('grid_2'))){
                $query->where('grid_2',$grid_2);
            }
            if($grid_3 = intval($request->input('grid_3'))){
                $query->where('grid_3',$grid_3);
            }
            if($grid_4 = intval($request->input('grid_4'))){
                $query->where('grid_4',$grid_4);
            }
        }

        //  3. 取数据
        $count = $query->count();
        $list = $query
            ->skip($iDisplayStart)->take($iDisplayLength)->orderBy('id','desc')->get();

        //  4. 整理数据
        foreach($list as $key=>$value){
            $list[$key]->birthday = date('Y-m-d H:i:s',$list[$key]->birthday/1000);
        }

        //  5. 发送数据
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
     * 新增人口
     */
    public function population_add(){
        //获取省市区信息，填充模板
        $location = (new DistrictController())->getDefaultData(1);  //1:返回数据； 不填写：ajax调用否，返回json

        //获取证件类型列表
        $cardCategoryList   = $this->getCardCategoryList();
        //获取婚姻状况列表
        $maritalStatusList  = $this->getMaritalStatusList();
        //获取学历信息列表
        $cultureDegreeList  = $this->getCultureDegreeList();
        //获取一级网格信息
        //$grids_1 = DB::table('grid')->select('id','name','short_name')->where('level',1)->get();

        return view('admin.data.add')
            ->with('title','数据中心')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_index')
            ->with('subNav','')
            ->with('activeNav','新增人口')
            ->with('menus',getMenus())
            ->with('location',$location)
            ->with('cardCategoryList',$cardCategoryList)
            ->with('maritalStatusList',$maritalStatusList)
            ->with('cultureDegreeList',$cultureDegreeList);
    }
    /**
     * 保存 新增、编辑 人口数据
     */
    public function population_save(Request $request){
        $this->validate($request, [
            //'householder_id' => 'required_if:is_householder,0', 在新增中单独验证
            'name'          => 'required',
            'card_code'     => 'required',
            'domicile_province' => 'required',
            'domicile_city'     => 'required',
            'domicile_district' => 'required',
            'domicile_address'  => 'required',
            'is_allowance'      =>'integer',
            'is_emphases'   =>'integer',
            'is_fixed'      =>'integer',
            'is_householder'=>'integer',
            'is_invalidism' =>'integer',
            'is_older'      =>'integer',
            'is_special'    =>'integer',
            'is_veteran'    =>'integer',
            'marital_status'=>'integer',
        ]);
        $data = $request->all();
        $edit = $request->input('edit');    //判断是否为edit
        //  1.1、处理生日
        $data['birthday'] = strtotime($data['birthday'])*1000;


        //  1.2 、验证身份证：如果是身份证，则长度应为15或18位
        if($data['card_category'] == '01'){
            if(strlen($data['card_code']) != 18 && strlen($data['card_code'] != 15)){
                return responseToJson(1,'证件号码（身份证）的长度应为15或18位，请检查');
            }
        }

        //人口信息~~~~标签处理
        $label = explode(',',$data['label']);
        $label_arr = array();
        foreach($label as $key=>$val){
            $find_res = DB::table('label')->where('name','=',$val)->pluck('id');
            if(empty($find_res)){
                $label_res = DB::table('label')->insertGetId(array('name'=>$val,'value'=>0));
                if(!empty($label_res)){
                    array_push($label_arr,array($label_res));
                }
            }else{
                array_push($label_arr,$find_res);
            }
        }
        unset($data['label']);
        //如果是编辑
        if($edit){
            // 2.1 首先，unset一些无用信息（不处理户主部分信息,其他地方专门处理）
            $id = intval($data['id']);
            unset($data['id']);
            unset($data['edit']);
            unset($data['is_householder']);
            // 2.2 更新
            //DB::connection()->enableQueryLog();
            $data['update_time'] = millisecond();
            $data['updator'] = current_user_id();

            DB::beginTransaction();
            $res = DB::table('population')->where('id',$id)->update($data);
            if(!empty($res)){
                DB::table('person_relation_label')->where('population_id',$id)->delete();
                $label_data = array();
                foreach($label_arr as $key=>$val){
                    $label_data[$key]['label_id'] = $val[0];
                    $label_data[$key]['population_id'] = $id;
                }
                $add_res = DB::table('person_relation_label')->insert($label_data);
                if($add_res){
                    DB::commit();
                    return responseToJson(0,'更改成功');
                }
            }
            DB::rollBack();
            return responseToJson(1,'更改失败');
        }else{
            //为新增
            //3.1 处理户主问题
            $is_householder = $data['is_householder'];          //是否为户主
            unset($data['is_householder']);
            // 3.1.1 不是户主，查找户主ID
            if($is_householder == 0){   //如果不是户主，则判断绑定的是否为户主
                $info = $this->isHouseholder($data['householder_id'],1);
                if($info['code'] == 1){
                    return responseToJson($info);   //如果错误，直接return
                }
                $data['family_id'] = $info['info']->id;     //赋值family_id
            }else{
                //如果是户主
                $data['relation'] = '户主';
            }
            unset($data['householder_id']);
            $data['create_time'] = millisecond();
            DB::beginTransaction();
            $id = DB::table('population')->insertGetId($data);
            if($id){
                if($is_householder){    //如果是户主，则需要更新family_id等于自身id
                    $family_id = DB::table('population')->where('id',$id)->update(['family_id'=>$id]);
                    if(empty($family_id)){
                        DB::rollBack();
                        return responseToJson(1,'添加失败');
                    }
                }
                $label_data = array();
                foreach($label_arr as $key=>$val){
                    $label_data[$key]['label_id'] = $val[0];
                    $label_data[$key]['population_id'] = $id;
                }
                $add_res = DB::table('person_relation_label')->insert($label_data);
                if($add_res){
                    DB::commit();
                    return responseToJson(0,'添加成功');
                }
            }
            DB::rollBack();
            return responseToJson(1,'添加失败');
        }
    }

    /**
     * 编辑是否为户主
     */
    public function editHouseHolderInfo(){
        $id = intval(Input::get('id'));
        $is_householder = intval(Input::get('is_householder'));
        $relation = trim(Input::get('relation'));

        if(!$is_householder){    //如果选择 不是户主
            // 1 、必须填写户主身份证号
            $householder_id = trim(Input::get('householder_id'));
            if(!$householder_id){
                return responseToJson(1,'请输入户主身份证号');
            }

            // 2、判断此人 是不是户主
            $family_id = DB::table('population')->where('id',$id)->value('family_id');

            // 2.1 、如果之前是户主，则判断其下是否有其他成员（有则不能更新）
            if($family_id == $id){  //是户主
                $family_count = DB::table('population')->where('family_id',$id)->whereRaw('`family_id`!=`id`')->count();
                if($family_count>0){    //其下还有成员
                    return responseToJson(1,'该组户内还有其他成员，无法更改');
                }
            }

            // 3 、可以更改family_id
                //3.1、判断被绑定人是不是户主，以及户主id
            $householder_info = $this->isHouseholder($householder_id,1);
            if($householder_info['code'] == 1){
                return responseToJson($householder_info);
            }

            $res = DB::table('population')->where('id',$id)->update(['family_id'=>$householder_info['info']->id,'relation'=>$relation]);
        }else{
            //选择 是户主 直接更新
            $res = DB::table('population')->where('id',$id)->update(['family_id'=>$id,'relation'=>'户主']); //自己的family_id = 自己的id
        }

        if($res){
            return responseToJson(0,'保存成功');
        }else{
            return responseToJson(1,'保存失败');
        }
    }

    /**
     * 删除人口 status = 1
     */
    public function population_delete(){
        $id = intval(Input::get('id'));
        $res = DB::table('population')->where('id',$id)->update(['status'=>1]);
        if($res){
            return responseToJson(0,'删除成功');
        }else{
            return responseToJson(1,'删除失败，请重试');
        }
    }
    /**
     * 组户信息管理
     */
    public function family_list(){
        //添加一个搜索字段用来限定组户信息
        $where['key'] = 'is_householder';
        return view('admin.data.resident')
            ->with('title','组户信息管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','组户信息管理')
            ->with('menus',getMenus())
            ->with('where',$where);
    }

    /**
     * 特殊人群管理
     */
    public function special_list(){
        $where['key'] = 'is_special';
        return view('admin.data.resident')
            ->with('title','特殊人群管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','特殊人群管理')
            ->with('menus',getMenus())
            ->with('where',$where);
    }

    /**
     * 重点人群管理
     */
    public function emphases_list(){
        $where['key'] = 'is_emphases';
        return view('admin.data.resident')
            ->with('title','重点人群管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','重点人群管理')
            ->with('menus',getMenus())
            ->with('where',$where);
    }

    /**
     * 流动人口
     */
    public function fluid_list(){
        $where['key'] = 'is_fluid';     //is_fixed == 2 为流动人口
        return view('admin.data.resident')
            ->with('title','流动人口管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','流动人口管理')
            ->with('menus',getMenus())
            ->with('where',$where);
    }

    /**
     * 流动人口微信平台
     */
    public function fluid_wx(){
        return view('admin.data.fluid_wx')
            ->with('title','流动人口微信平台')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','流动人口微信平台')
            ->with('menus',getMenus());
    }

    /**
     * 回收站
     */
    public function recycle_bin(){
        $where['key'] = 'status';     //status == 1 已删除
        return view('admin.data.resident')
            ->with('title','回收站')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_resident_population')
            ->with('subNav','')
            ->with('activeNav','回收站')
            ->with('menus',getMenus())
            ->with('where',$where);
    }
    /**
     * 回收站操作： 还原、彻底删除
     */
    public function recycle_bin_action(){
        $id = intval(Input::get('id'));
        $type = intval(Input::get('type'));     //1:还原 2：彻底删除
        // 1、操作
        if($type == 1){
            //还原 status->0
            $res = DB::table('population')->where('id',$id)->update(['status'=>0]);
        }else if($type == 2){
            //彻底删除 ：判断一下status，是回收站的再彻底删除
            $res = DB::table('population')->where('id',$id)->where('status',1)->delete();
        }

        // 2、判断结果，返回数据
        if($res){
            return responseToJson(0,'操作成功');
        }else{
            return responseToJson(1,'操作失败，请重试');
        }
    }

    /**
     * 导出excel
     */
    public function exportExcel(Request $request){
        // 1、组装过滤条件
        $pageKey = $request->input('pageKey');  // 代表来自那个页面？ 重点/特殊/低保/老龄/退伍等
        $fileName = '';
        $query = DB::table('population')->select('population.*','grid.name as grid_4_name');

        //  1.1 基本过滤条件 -- 代表来源页
        switch($pageKey) {
            case 'is_householder' :     //是户主  id=family_id
                $query->whereRaw('population.`id`=population.`family_id`')->where('status', 0);
                $fileName = '户主信息--';   //组户信息即为户主信息
                break;
            case 'is_fixed' :           //固定人口
                $query->where('population.is_fixed', 1)->where('status', 0);
                $fileName ='常住人口--';
                break;
            case 'is_fluid' :           //流动人口
                $query->where('population.is_fixed', 2)->where('status', 0);
                $fileName = '流动人口--';
                break;
            case 'status'   :           //回收站
                $query->where('population.status', 1);
                $fileName = '回收站--';
                break;
            default :
                $query->where($pageKey, 1)->where('population.status', 0);  // 重点/特殊/低保/老龄/退伍等都可用
                $fileName = $request->input('pageName').'--';
        }
         // 1.2高级搜索条件
        if($name = trim($request->input('name'))){
            $query->where('population.name','like',$name.'%');
        }
        if($card_code = trim($request->input('card_code'))){
            $query->where('population.card_code','like',$card_code.'%');
        }
        if($contact_phone = trim($request->input('contact_phone'))){
            $query->where('population.contact_phone','like',$contact_phone.'%');
        }
        if($birthday = trim($request->input('birthday'))){
            $fileName = '('.$birthday.')--';
            list($start,$end) = explode('--',$birthday);
            $start = strtotime($start)*1000;
            $end = strtotime($end)*1000;
            $query->whereBetween('population.birthday',[$start,$end]);
        }
        if($grid_1 = intval($request->input('grid_1'))){
            $query->where('population.grid_1',$grid_1);
        }
        if($grid_2 = intval($request->input('grid_2'))){
            $query->where('population.grid_2',$grid_2);
        }
        if($grid_3 = intval($request->input('grid_3'))){
            $query->where('population.grid_3',$grid_3);
        }
        if($grid_4 = intval($request->input('grid_4'))){
            $query->where('population.grid_4',$grid_4);
        }
        // 2 、获取数据
        $population = $query->leftJoin('grid','population.grid_4','=','grid.id')->get();
        // 3、转换数据中int为对应名称
        foreach($population as $k=>$person){
            //unset不需要的数据
            unset($population[$k]->grid_1);unset($population[$k]->grid_2);unset($population[$k]->grid_3);
            unset($population[$k]->grid_4);unset($population[$k]->create_time);unset($population[$k]->update_time);
            unset($population[$k]->status);unset($population[$k]->updator);unset($population[$k]->update_time);

            $population[$k] = get_object_vars($this->convertPersonInfo($population[$k]));
            //dd($population[$k]);
        }

        // 4 .指定文件名
        $fileName = $fileName.' '.date('Y_m_d',time());
        // 5. 指定字段名
        $fieldName = ['编号','姓名','国籍','性别','出生日期','证件','证件号码','民族','婚姻状况','学历','户籍类型',
                      '户籍地址','居住地址','联系地址','联系电话','邮编','备注','所属组户编号','是否重点人群','是否特殊人群',
                      '常住/流动人口','是否低保人群','是否伤残人群','是否老龄人','是否退伍军人','与户主关系','所属网格(第四级)','是否为户主'];
        // 6. 输出excel
        array_unshift($population,$fieldName);
        Excel::create($fileName,function($excel) use ($population){
            $excel->sheet('sheet1', function($sheet) use ($population){
                $sheet->rows($population);
                $sheet->setAutoSize(true);
            });
        })->export('xls');
    }

    /**
     * 验证是否为户主
     * $type    0:前台ajax访问，返回json， 1：类内调用，返回数组
     */
    public function isHouseholder($householder_id='',$type = 0){
        if($type == 0){
            $householder_id = htmlspecialchars(Input::get('householder_id'));
        }
        //$householder_id = '412009123409871234';
        $info = DB::select('select `id`,`name`,`nation` from `population` where `card_code`=? and `id`=`family_id` limit 1',["$householder_id"]);
        if($type == 0){
            if($info){
                return responseToJson(0,'是户主，可以使用',$info[0]);
            }else{
                return responseToJson(1,'此身份证号不存在，或不是户主，请首先添加户主信息');
            }
        }else{
            if($info){
                return array('code'=>0,'info'=>$info[0]);
            }else{
                return array('code'=>1,'msg'=>'绑定户主失败：此身份证号不存在，或不是户主，请首先添加户主信息');
            }

        }

    }


    /**
     * 新增、编辑 时，获取下一级网格列表
     */
    public function getNextGridList(){
        $pid = intval(Input::get('pid'));
        $list = DB::table('grid')->select('id','name','short_name')->where('parent_id',$pid)->get();
        if(!$list){
            $data = new \stdClass();
            $data->id = -1;
            $data->name='无下级网格';
            $data->short_name='无下级网格';
            $list[] = $data;
        }
        return json_encode($list);
    }

    /**
     * 各种碎片信息
     */

    //获取单个文化程度名称
    private function getCultureDegreeName($index){
        $list = $this->getCultureDegreeList();
        return array_key_exists($index,$list) ? $list[$index] : '----';
    }

    //文化程度列表
    private function getCultureDegreeList(){
        return [
            '10'    => '研究生',
            '11'    => '研究生毕业',
            '19'    => '研究生肄业',
            '20'    =>'大学本科',
            '21'    =>'大学毕业',
            '28'    =>'相当大学毕业',
            '29'    =>'大学肄业',
            '30'    =>'大学专科和专科学校',
            '31'    =>'专科毕业',
            '38'    =>'相当专科毕业',
            '39'    =>'专科肄业',
            '40'    =>'中专/中技',
            '41'    =>'中专毕业',
            '42'    =>'中技毕业',
            '48'    =>'相当中专或中技毕业',
            '49'    =>'中专或中技肄业',
            '50'    =>'技工学校',
            '51'    =>'技工学校毕业',
            '59'    =>'技工学校肄业',
            '60'    =>'高中',
            '61'    =>'高中毕业',
            '62'    =>'职业高中毕业',
            '63'    =>'农业高中毕业',
            '68'    =>'相当高中毕业',
            '69'    =>'高中肄业',
        ];
    }

    //获取身份证种类名称
    private  function getCardCategoryName($index){
        $list = $this->getCardCategoryList();
        return array_key_exists($index,$list) ? $list[$index] : '----';
    }

    //获取身份证种类列表
    private function getCardCategoryList(){
        return [
            '01'    => '身份证',
            '02'    => '护照',
            '03'    => '居住证',
            '04'    => '军官证',
            '05'    => '出生证',
            '06'    => '绿卡',
            '07'    => '港澳通行证',
            '08'    => '其他证件',
        ];
    }

    //获取婚姻状态名称
    private  function getMaritalStatusName($index){
        $list = $this->getMaritalStatusList();
        return array_key_exists($index,$list) ? $list[$index] : '----';
    }
    //婚姻状态列表
    private function getMaritalStatusList(){
        return [
            '10'    => '未婚',
            '20'    => '已婚',
            '21'    => '初婚',
            '22'    => '再婚',
            '23'    => '复婚',
            '30'    => '丧偶',
            '40'    => '离婚',
            '90'    => '未说明',
        ];
    }

    //性别
    private function getSexName($index){
        switch($index){
            case 0 :
                return '未知';
            case 1 :
                return '男';
            case 2 :
                return '女';
            case 9 :
                return '未说明';
            default:
                return '----';
        }
    }

    //人口状态：1常住、2流动、0不确定
    private function getFixedName($index){
        switch($index){
            case 0 :
                return '不确定';
            case 1 :
                return '常住人口';
            case 2 :
                return '流动人口';
            default:
                return '----';
        }
    }

    //重点人群、特殊人群、固定人口、低保人群、伤残人群、老龄人、退伍军人 等的 通用状态 名称
    private function getCommonStatusName($index){
        $list = $this->commonStatusList();
        return array_key_exists($index,$list) ? $list[$index] : '----';
    }
    //重点人群、特殊人群、固定人口、低保人群、伤残人群、老龄人、退伍军人 等的 通用状态 列表
    private  function commonStatusList(){
        return [
            0   => '不确定',
            1   => '是',
            2   => '否'
        ];
    }


    /**
     * 人口导入
     */
    public function population_import(Request $request){
        $type = $request->get('excel_type');
        return view('admin.data.add_population')
            ->with('type',$type?$type:'lowest_ensure');
    }

    /**
     * 导入超时手动导入
     */
    public function lowest_ensure(){
//        Population::import_population('');
//        $filename = 'test.xls';
//        $file = 'storage/app/public/'.$filename;//文件地址
        //低保
//            $family_type='城市';//默认
//            $family_type='农村';
//        Population::lowest_ensure($file,$population_data);
        //优抚
//            //老兵
//            $population_data['is_older']=1;
//            $population_data['is_veteran']=1;
//            //伤残
//            $population_data['is_invalidism'] = 1;
//        Population::preferential_treatment($file,$population_data);

        //老龄
//        Population::aging($file);

        //养老院
//        Population::rest($file);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * excel文件上传
     */
    public function excel_upload(Request $request){
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


        //excel导入路径
        $filePath = 'storage/app/public/'.$filename;

        $type = $request->get('excel_type');//excel类型
        if($type=='lowest_ensure'){
//            $family_type='城市';//默认
//            $family_type='低保';
            Population::lowest_ensure($filePath,$family_type);
        }elseif($type=='aging'){
            Population::aging($filePath);
        }elseif($type=='preferential_treatment'){
//            //老兵
//            $population_data['is_older']=1;
//            $population_data['is_veteran']=1;
//            //伤残
//            $population_data['is_invalidism'] = 1;
            Population::preferential_treatment($filePath,$population_data);
        }elseif($type=='rest'){
            Population::rest($filePath);
        }


        return responseToJson(0,'','ok');

    }


    /**
     * @return mixed
     * 标签管理
     */
    public function label_manage(){
        return view('admin.data.label')
            ->with('title','标签管理')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_index')
            ->with('subNav','')
            ->with('activeNav','标签管理')
            ->with('menus',getMenus());
    }

    /**
     * @param Request $request
     * ajax获得标签列表
     */
    public function label_list(Request $request){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $query = DB::table('label');

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
     * 标签信息【应用信息，基本信息】
     */
    public function label_info(Request $request){
        $id = $request->input('label_id');
        $label_info = DB::table('label')->where('id',$id)->first();
        return view('admin.data.label_info')
            ->with('title','标签信息')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_index')
            ->with('subNav','')
            ->with('activeNav','标签管理')
            ->with('label_id',$id)
            ->with('label_info',$label_info)
            ->with('menus',getMenus());
    }


    /**
     * 获取标签的使用情况
     */
    public function label_use_info(){
        $id = intval(trim(Input::get("id")));//标签id
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $query = DB::table('population as p');
        $query->join('person_relation_label as pl','pl.population_id','=','p.id');
        if(!empty($sSearch)){
            $query->where('p.name','like','%'.$sSearch.'%');
        }

        $query->where('pl.label_id',$id);
        $query->where('p.status',0);

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->select('p.id','p.sex','p.name','p.card_code','p.contact_phone','p.contact_address')
            ->get();

        foreach($list as $k=>$val){
            if($val->sex==1){
                $list[$k]->sex = '男';
            }elseif($val->sex==2){
                $list[$k]->sex = '女';
            }elseif($val->sex==0){
                $list[$k]->sex = '未知';
            }elseif($val->sex==9){
                $list[$k]->sex = '未说明';
            }
        }

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 标签删除【未被使用的标签】
     */
    public function label_del(Request $request){
        $label_id = intval($request->input('id'));
        $find_res = DB::table('person_relation_label')->where('label_id',$label_id)->get();
        if(empty($find_res)){
            $res = DB::table('label')->where('id',$label_id)->delete();
            if(!empty($res)){
                return responseToJson(0,'标签已删除');
            }else{
                return responseToJson(1,'删除失败，请重试！');
            }
        }else{
            return responseToJson(2,'标签已被使用！');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 修改标签名
     */
    public function label_save(Request $request){
        $label_id = intval($request->input('id'));
        $name = trim($request->input('name'));
        $find_res = DB::table('label')->where('name',$name)->first();
        if(empty($find_res)){
            $res = DB::table('label')->where('id',$label_id)->update(array('name'=>$name));
            if(!empty($res)){
                return responseToJson(0,'标签已修改');
            }else{
                return responseToJson(1,'修改失败，请重试！');
            }
        }else{
            if($find_res->id==$label_id){
                return responseToJson(0,'标签已修改');
            }
            return responseToJson(2,'标签已存在！');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 删除标签使用
     */
    public function label_use_del(Request $request){
        $label_id = intval($request->input('label_id'));
        $user_id = trim($request->input('population'));
        $res = DB::table('person_relation_label')
            ->where('label_id',$label_id)
            ->where('population_id',$user_id)
            ->delete();
        if(!empty($res)){
            return responseToJson(0,'已删除');
        }else{
            return responseToJson(1,'删除失败，请重试！');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 人口数据excel存储
     */
    public function import_excel(Request $request){
        //判断请求中是否包含name=file的上传文件
        if(!$request->hasFile('import_file')){
            return responseToJson(1,'上传文件为空！');
        }
        $file = $request->file('import_file');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            return responseToJson(2,'文件上传出错！');
        }
        $destPath = realpath(base_path('storage/app/temp'));
        if(!file_exists($destPath))
            mkdir($destPath,0777,true);
        //$filename = $file->getClientOriginalName();
        $extension = $file -> getClientOriginalExtension();
        $filename = session('user')->id.'-'.millisecond().'.'.$extension;
        if($file->move($destPath,$filename) == false){
            return responseToJson(3,'保存文件失败！');
        }

        //excel导入路径
        $filePath = 'storage/app/temp/'.$filename;
        $res = Population::import_population($filePath);
        if(!empty($res)){
            unlink(realpath(base_path($filePath)));
            return responseToJson(0,'导入完成');
        }else{
            return responseToJson(1,'导入失败！');
        }

    }

    /**
     * 计生服务
     */
    public function planned_parenthood(){
        return view('admin.data.planned_parenthood')
            ->with('title','计生服务')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_index')
            ->with('subNav','')
            ->with('activeNav','计生服务')
            ->with('menus',getMenus());
    }

    /**
     * 获得计生服务信息
     */
    public function get_bear_list(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));
        $level_two = trim(Input::get("level_two"));
        $level_there = trim(Input::get("level_there"));
        $level_four = trim(Input::get("level_four"));
        $merry_type = trim(Input::get("merry_type"));
        $gravidity_type = trim(Input::get("gravidity_type"));
        $birth_date = trim(Input::get("birth_date"));
        $children = trim(Input::get("children"));

        $query = DB::table('population as p1');
        $query->leftJoin('gravidity as g','p1.id','=','g.population_id');

        if(!empty($birth_date)){
            $birth_date_data = explode("--",$birth_date);
            if(count($birth_date_data)==2){
                $begin = strtotime($birth_date_data[0]);
                $end = strtotime($birth_date_data[1]);
                $query->whereBetween('g.birth_date', array($begin, $end));
            }
        }

        if(!empty($sSearch)){
            $query->where('g.woman_name','like','%'.$sSearch.'%');
        }
        if(!empty($merry_type)){
            $query->where('g.merry_type',$merry_type);
        }
        if(!empty($children)){
            $query->where('g.children',$children);
        }
        if(!empty($gravidity_type)){
            $query->where('g.gravidity_type','like','%'.$gravidity_type.'%');
        }
        if(!empty($level_four)){
            $query->where('p1.grid_4',$level_four);
        }
        if(!empty($level_there)){
            $query->where('p1.grid_3',$level_two);
        }
        if(!empty($level_two)){
            $query->where('p1.grid_2',$level_two);
        }
//        $query->where('p1.grid_1',1);

        $query->where('p1.is_gravidity',1);
        $query->where('p1.status',0);

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->select('g.*')
            ->orderBy('id','desc')
            ->get();

        foreach($list as $key=>$val){
            if($val->merry_type==10){
                $list[$key]->merry_type='未婚';
            }elseif($val->merry_type==20){
                $list[$key]->merry_type='已婚';
            }elseif($val->merry_type==21){
                $list[$key]->merry_type='初婚';
            }elseif($val->merry_type==22){
                $list[$key]->merry_type='再婚';
            }elseif($val->merry_type==23){
                $list[$key]->merry_type='复婚';
            }elseif($val->merry_type==30){
                $list[$key]->merry_type='丧偶';
            }elseif($val->merry_type==40) {
                $list[$key]->merry_type = '离婚';
            }else{
                $list[$key]->merry_type='未说明';
            }
            $list[$key]->man_age=date('Y-m-d',$val->man_age);
            $list[$key]->woman_age=date('Y-m-d',$val->woman_age);
            $list[$key]->merry_date=date('Y-m-d',$val->merry_date);
            $list[$key]->birth_date=date('Y-m-d',$val->birth_date);
        }

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
     * 获取下级网格
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bear_get_grid(Request $request){
        $parent_id = intval($request->input('data'));
        $grid_list = DB::table('grid')->where('parent_id',$parent_id)->select('id','short_name')->get();
        if(empty($grid_list)){
            return responseToJson(1,'null');
        }else{
            return responseToJson(0,$grid_list);
        }
    }

    /**
     * 计生服务数据添加或修改
     * @param Request $request
     * @return mixed
     */
    public function bear_add(Request $request){
        $id = $request->get('bear_id',0);
        $bear_info = null;
        if(!empty($id)){
            $bear_info = DB::table('population as p')
                ->join('gravidity as g','p.id','=','g.population_id')
                ->where('g.id',$id)
                ->select('g.*','p.card_code as woman_id')
                ->first();
            $bear_info->birth_date = date('Y-m-d',$bear_info->birth_date);
            $bear_info->woman_age = date('Y-m-d',$bear_info->woman_age);
            $bear_info->man_age = date('Y-m-d',$bear_info->man_age);
        }
        return view('admin.data.bear')
            ->with('title','计生服务')
            ->with('homeNav','数据中心')
            ->with('homeLink','/data_index')
            ->with('subNav','')
            ->with('activeNav','信息统计')
            ->with('bear_info',$bear_info)
            ->with('menus',getMenus());
    }

    /**
     * 获得女方信息
     */
    public function bear_get_women(Request $request){
        $card_code = trim($request->input('card_code'));
        $woman_info = DB::table('population')
            ->where('card_code',$card_code)
            ->where('sex','!=','1')
            ->where('status','=','0')
            ->select('id','name','birthday','is_gravidity')
            ->first();
        if(empty($woman_info)){
            return responseToJson(1,'查无此人');
        }else{
            if($woman_info->is_gravidity==1){
                return responseToJson(1,'已统计');
            }
            $woman_info->birthday = date('Y-m-d',$woman_info->birthday);
            return responseToJson(0,$woman_info);
        }
    }

    /**
     * 计生信息统计 or 修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bear_add_save(Request $request){
        $data = $request->all();
        $data['woman_age'] = strtotime($data['woman_age']);
        if(!empty($data['man_age'])){
            $data['man_age'] = strtotime($data['man_age']);
        }
        $data['birth_date'] = strtotime($data['birth_date']);
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        if(empty($obj)){
            DB::beginTransaction();
            $is_gravidity = DB::table('population')
                ->where('is_gravidity','!=',1)
                ->where('id',$data['population_id'])
                ->update(array('is_gravidity'=>1));
            $add_res = DB::table('gravidity')->insertGetId($data);
            if(empty($is_gravidity)||empty($add_res)){
                DB::rollBack();
                return responseToJson(1,'添加失败');
            }else{
                DB::commit();
                return responseToJson(0,'统计成功');
            }
        }else{
            $add_res = DB::table('gravidity')
                ->where('id',$obj)
                ->update($data);
            if($add_res==0||$add_res==1){
                return responseToJson(0,'已修改');
            }else{
                return responseToJson(1,'系统出错了！');
            }
        }
    }
}

