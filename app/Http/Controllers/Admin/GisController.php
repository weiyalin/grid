<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use App\Models\Gis;

class GisController extends Controller
{
    /**
     * Gis首页
     */
    public function index(){
        return view('admin.gis.index')
            ->with('title','Gis中心首页')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }

    /**
     * 事件地图页面
     */
    public function event_map(){
        $type = intval(Input::get("type"));
        $text = '全部事件';
        switch($type){
            case 1:
                $text = '城管事件';

                break;
            case 2:
                $text = '民政事件';

                break;
            case 3:
                $text = '维稳事件';

                break;
            case 4:
                $text = '环保事件';

                break;
            case 5:
                $text = '安全生产事件';

                break;
            case 6:
                $text = '食药监事件';

                break;
            case 7:
                $text = '卫计委事件';

                break;
            default:
                $text = '全部事件';
                break;
        }


        return view('admin.gis.map')
            ->with('title','事件分布')
            ->with('homeNav','事件分布')
            ->with('homeLink','/gps_event_map')
            ->with('subNav','')
            ->with('activeNav',$text)
            ->with('type',$type)
            ->with('org_list',\App\Models\Event::get_org_list())
            ->with('category_1',\App\Models\Event::get_event_category(1))
//            ->with('category_2',\App\Models\Event::get_event_category(2))
//            ->with('category_3',\App\Models\Event::get_event_category(3))
            ->with('menus',getMenus());
    }


    /**
     * 地图分布数据
     */
    public function map_data(){
        //todo: 人员分类与事件关联
        $type = intval(Input::get("type"));
        $keyword = Input::get('keyword');
        $source = Input::get('source');
        $begin =  intval(Input::get('begin_date'));
        $end = intval(Input::get('end_date'));
        $org_id = Input::get('org');
        $status = Input::get('status');
        $category_1 = Input::get('category_1');
        $category_2 = Input::get('category_2');
        $event_category_code = Input::get('category_3');

        $query = DB::table('event');

        //部门过滤
        if($type){
            $code_list = DB::table("event_category")->where('department_id',$type)->lists('code');
            //获取所有下级code
            $category_code_list = [];
            foreach($code_list as $code){
                $list_code = DB::table('event_category')->where('code','like',"$code%")->lists('code');
                $category_code_list = array_merge($category_code_list,$list_code);
            }

            $event_category_code_list = array_unique($category_code_list);

            $query->whereIn('event_category_code',$event_category_code_list);
        }

        $query->where('status','<',6);
        if($status>=0){
            $query->where('status',$status);
        }
        if($keyword){
            $query->where('title','like','%'.mb_strtolower($keyword).'%');
        }
        if($source>=0){
            $query->where('source',$source);
        }
        if($org_id){
            $id_list = DB::table('event_process_log')->where('to_org_id',$org_id)->lists('event_id');
            $query->whereIn('id',$id_list);
        }
        if($event_category_code){
            $query->where('event_category_code','like',"$event_category_code%");
        }

        if($begin > 0 || $end > 0){
            if($end <=0){
                $end = millisecond();
            }
            $end = $end +86400000-1000;//结束时间偏移
            $query->whereBetween('create_time',[$begin,$end]);
        }


        //$query->whereBetween('create_time',[$begin,$end]);
        //$status是否过滤 待观察

        $list = $query->get();
        foreach($list as $key=>$value){
            $list[$key]->create_time = date('Y-m-d H:i:s',$list[$key]->create_time/1000);
            //img
            $list[$key]->img = DB::table('event_attachment')->where('event_id',$value->id)->value('path');
        }
        if($list == false){
            $list = [];
        }
        return responseToJson(0,'success',$list);
    }

    public function user_location_map(){
        $grid_user_list = DB::table('grid_user')
            ->where('is_manager',1)
            ->leftJoin('user','grid_user.user_id','=','user.id')
            ->select('user.id as id','user.name as name')
            ->get();

        return view('admin.gis.user_location')
            ->with('title','人员轨迹')
            ->with('homeNav','人员轨迹')
            ->with('homeLink','/gps_user_location')
            ->with('subNav','')
            ->with('activeNav','人员轨迹')
            ->with('grid_user_list',$grid_user_list)
            ->with('menus',getMenus());

    }


    public function user_location_data(){
        $id = intval(Input::get('id'));
        $t = intval(Input::get('date')) / 1000;
        //一天的开始和结束
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t))*1000;
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t))*1000;

        $query = DB::table('gps');
        if($id){
            $query->where('target_id',$id);

        }

        if($t){
            $query->whereBetween('create_time',[$start,$end]);
        }
        $query->where('longitude','>',0);
        $query->where('latitude','>',0);


        $result = $query->where('type',1)->get();
        return responseToJson(0,'success',$result);
    }

    /**
     * 人员定位页面
     */
    public function location_map(){
        $type = intval(Input::get("type"));
        $page = 'admin.gis.location';
        $text = '全部人员';

        if($type == 1){
            //网格长
            $page = 'admin.gis.chengguan_location';
            $text = '网格长';
        }
//        else if($type == 2){
//            //环保
//            $page = 'admin.gis.huanbao_location';
//            $text = '环保人员';
//        }
        else if($type == 0){
            //所有
            $page = 'admin.gis.location';
            $text = '全部人员';
        }

        return view($page)
            ->with('title','人员定位')
            ->with('homeNav','人员定位')
            ->with('homeLink','/gps_location_map')
            ->with('subNav','')
            ->with('activeNav',$text)
            ->with('menus',getMenus());
    }

    /**
     * 人员定位数据
     */
    public function location_data(){
        $type = intval(Input::get("type"));

        $query = DB::table('grid_user');
        if($type){
            $query->where('is_manager',$type);
        }

        //10分钟不更新gps,认为离线
        $last_time = millisecond() - 10*60*1000;
        $query->where('update_time','>',$last_time);

        $list = $query->where('is_online',1)->get();

        foreach($list as $key=>$value){

            $list[$key]->update_time = date('Y-m-d H:i:s',$value->update_time/1000);

            $user = DB::table('user')->where('id',$value->user_id)->first();
            $grid = DB::table('grid')->where('id',$value->grid_id)->first();

            $list[$key]->user_name = $user->name;
            $list[$key]->user_phone = $user->phone;
            $list[$key]->user_email = $user->email;
            $list[$key]->user_org_name = DB::table('organization')->where('id',$user->org_id)->value('name');
            $list[$key]->user_photo = $user->photo;

            if($grid){
                $list[$key]->grid_name = $grid->name;
                $list[$key]->grid_short_name = $grid->short_name;
                $list[$key]->grid_level = $grid->level.'级网格';
                $list[$key]->grid_parent_id = $grid->parent_id;
                $list[$key]->grid_parent_name = $grid->parent_id ? DB::table('grid')->where('id',$grid->parent_id)->value('name') : '指挥中心';
                $list[$key]->grid_grid_number = $grid->grid_number;
                $list[$key]->grid_family_number = $grid->family_number;
                $list[$key]->grid_fixed_population_number = $grid->fixed_population_number;
                $list[$key]->grid_fluid_population_number = $grid->fluid_population_number;
                $list[$key]->grid_manager_id = $grid->manager_id;
                $list[$key]->grid_manager_name = $grid->manager_name;
                $list[$key]->grid_map = $grid->map;
            }


        }

        return responseToJson(0,'success',$list);
    }

    /**
     * 地图上用户详细信息
     */
    public function user_detail(){
//        $user_id = I('id',0,'int');
//
//        $user = DB::table('user')->where('id',$user_id)->first();
//        if($user == false){
//            return responseToJson(1,'用户不存在');
//        }
//        $user->org_name = DB::table('organization')->where('id',$user->org_id)->value('name');
//
//        $list = [];
//        //用户管理的网格
//        $grid_user_list = DB::table('grid_user')->where('user_id',$user_id)->get();
//        foreach($grid_user_list as $grid_user){
//            $grid = DB::table('grid')->where('id',$grid_user->grid_id)->find();
//            $grid->user_name = $user->name;
//            $grid->user_phone = $user->phone;
//            $grid->user_email = $user->email;
//            $grid->user_org_name = $user->org_name;
//            $grid->user_is_manager = $grid_user->is_manager;
//
//            $list[] = $grid;
//        }
//
//        return responseToJson(0,'success',$list);
    }


    /**
     * 网格地图页面
     */
    public function grid_map(){
        return view('admin.gis.grid_map')
            ->with('title','网格地图')
            ->with('homeNav','网格地图')
            ->with('homeLink','/gps_grid_map')
            ->with('subNav','')
            ->with('activeNav','网格地图')
            ->with('menus',getMenus());
    }

    /**
     * 网格地图查询(按照级别)
     */
    public function grid_map_query(){
        $level = intval(Input::get('level'));
        $parent_id = intval(Input::get('level'));

        $grid_list = DB::table('grid')
            ->where('level',$level)
            ->where('parent_id',$parent_id)
            ->get();

        return responseToJson(0,'success',$grid_list);
    }


    /**
     * 网格中的组织机构查询
     */
    public function grid_org_query(){
        $level = intval(Input::get('level'));
        $grid_id = intval(Input::get('grid_id'));

        $result_list=[];

        $grid_arr= [$grid_id];
        $text = '';
        for($i=0;$i<4;$i++){
            $grid_id_list = DB::table('grid')->whereIn('parent_id',$grid_arr)->lists('id');
            $grid_level = $i+1;
            $grid_arr = $grid_id_list;

            switch($grid_level){
                case 1:
                    $text='指挥中心';
                    break;
                case 2:
                    $text='办事处/乡镇级';
                    break;
                case 3:
                    $text='社区/村组级';
                    break;
                case 4:
                    $text='网格级';
                    break;
            }
            $org_count = DB::table('organization')
                ->whereIn("grid_$grid_level",$grid_id_list)
                ->where('type',2)
                ->count();

            $office_count = DB::table('organization')
                ->whereIn("grid_$grid_level",$grid_id_list)
                ->where('type',1)
                ->count();
            $result_list[] = ['level'=>$grid_level,'level_text'=>$text,'org_count'=>$org_count,'office_count'=>$office_count];
        }

//        if($level == 0){
//            $org_count = DB::table('organization')
//                ->where('type',2)
//                ->count();
//
//            $office_count = DB::table('organization')
//                ->where('type',1)
//                ->count();
//
//            $user_list = DB::table('grid_user')->leftJoin('user','user.id','=','grid_user.user_id')
//                ->select('user.id as user_id,grid_user.id,user.name,user.sex,user.org_id,user.title,user.phone,user.email')
//                ->take(10)
//                ->get();
//        }
//        else {
//            $org_count = DB::table('organization')
//                ->where("grid_$level",$grid_id)
//                ->where('type',2)
//                ->count();
//
//            $office_count = DB::table('organization')
//                ->where("grid_$level",$grid_id)
//                ->where('type',1)
//                ->count();
//
//            $user_list = DB::table('grid_user')->leftJoin('user','user.id','=','grid_user.user_id')
//                ->select('user.id as user_id,grid_user.id,user.name,user.sex,user.org_id,user.title,user.phone,user.email')
//                ->where('grid_user.grid_id',$grid_id)
//                ->take(10)
//                ->get();
//        }
//
//
//
//        $result = ['org_count'=>$org_count,'office_count'=>$office_count,'user_list'=>$user_list];

        return responseToJson(0,'success',$result_list);

    }

    /**
     * 网格中的人口数据查询
     */
    public function grid_population_query(){
        $level = intval(Input::get('level'));
        $grid_id = intval(Input::get('grid_id'));

        $level = DB::table('grid')->where('id',$grid_id)->value('level');

        if($level == 0){
            $fixed_count = DB::table('grid')->sum('fixed_population_number');
            $fluid_count = DB::table('grid')->sum('fluid_population_number');
        }
        else {
            $grid_info = DB::table('grid')->where('id',$grid_id)->first();
            $fixed_count = $grid_info->fixed_population_number;
            $fluid_count = $grid_info->fluid_population_number;
        }

        $result = ['fixed_count'=>$fixed_count,'fluid_count'=>$fluid_count];

        return responseToJson(0,'success',$result);
    }

    /**
     * 网格中的事件数据查询
     */
    public function grid_event_query(){
        $level = intval(Input::get('level'));
        $grid_id = intval(Input::get('grid_id'));

        $level = DB::table('grid')->where('id',$grid_id)->value('level');

        //统计7大部门事件数
        $event_list=[];
        $department_list = DB::table('event_department')->get();
        foreach($department_list as $department){
            $d_id = $department->id;
            $code_list = DB::table("event_category")->where('department_id',$d_id)->lists('code');
            //获取所有下级code
            $category_code_list = [];
            foreach($code_list as $code){
                $list_code = DB::table('event_category')->where('code','like',"$code%")->lists('code');
                $category_code_list = array_merge($category_code_list,$list_code);
            }

            $event_category_code_list = array_unique($category_code_list);

            if($level == 0){//状态（0：待研判，1：待办理，2：办理中，3：办结待审核，4：已办结，5：已挂起，6：已删除）
                $event_count = DB::table('event')
                    ->whereIn('event_category_code',$event_category_code_list)
                    ->count();
                $event_completed_count = DB::table('event')
                    ->where('status',4)
                    ->whereIn('event_category_code',$event_category_code_list)
                    ->count();
            }
            else {
                $level = DB::table('grid')->where('id',$grid_id)->value('level');

                $event_count = DB::table('event')
                    ->where("grid_$level",$grid_id)
                    ->whereIn('event_category_code',$event_category_code_list)
                    ->count();
                $event_completed_count = DB::table('event')
                    ->where("grid_$level",$grid_id)
                    ->where('status',4)
                    ->whereIn('event_category_code',$event_category_code_list)
                    ->count();
            }

            $event_list[] = ['department_id'=> $d_id,'department_name'=>$department->name,'event_count'=>$event_count,'event_completed_count'=>$event_completed_count];
        }



        return responseToJson(0,'success',$event_list);
    }

    /**
     * 网格地图开发页面
     */
    public function grid_dev(){
        return view('admin.test')
            ->with('title','首页')
            ->with('homeNav','首页')
            ->with('homeLink','/gps_grid_dev')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }

    public function grid_sub_geo_json(){
        $parent_id = intval(Input::get('id'));
        $grid_list = DB::table('grid')->where('parent_id',$parent_id)->get();
        $features = [];
        $data = [];
        foreach($grid_list as $grid){
            $property = new \stdClass();
            $property->name=$grid->short_name;

            //构造数组
            $map = trim($grid->map);
            if($map == false){
                continue;
            }
            $points = explode(";",$map);
            $coordinates = [];
            foreach($points as $point){
                $p = explode(",",$point);
//                if(count($p) < 2){
//                    $grid->p = $p;
//                    dd($grid);
//                }
                $coordinates[] = [floatval($p[0]),floatval($p[1])];
            }

            //构造对象
            $geometry = new \stdClass();
            $geometry->type="Polygon";
            $geometry->coordinates = [$coordinates];

            $feature = new \stdClass();
            $feature->type = "Feature";
            $feature->id=$grid->id;
            $feature->properties = $property;
            $feature->geometry = $geometry;

            $features[] = $feature;

            $data[] = ['name'=>$grid->short_name,'value'=>$grid->fixed_population_number,'grid_id'=>$grid->id];
        }

        $json = new \stdClass();
        $json->type="FeatureCollection";
        $json->features = $features;

        $result = ['json'=>$json,'data'=>$data];

        return response()->json($result);
    }


//    public function grid_geo_json(){
//        //return response()->download(public_path().'/ui_resource/js/json/USA.json');
//
//        $grid_id = intval(Input::get('id'));
//        $map = DB::table('grid')->where('id',$grid_id)->value('map');
//
//        //构造数组
//        $points = explode(";",$map);
//        $coordinates = [];
//        foreach($points as $point){
//            $p = explode(",",$point);
//            $coordinates[] = [floatval($p[0]),floatval($p[1])];
//        }
//
//        //构造对象
//        $geometry = new \stdClass();
//        $geometry->type="Polygon";
//        $geometry->coordinates = [$coordinates];
//
//        $property = new \stdClass();
//        $property->name="总人口数";
//
//
//        $feature = new \stdClass();
//        $feature->type = "Feature";
//        $feature->id="01";
//        $feature->properties = $property;
//        $feature->geometry = $geometry;
//
//        $json = new \stdClass();
//        $json->type="FeatureCollection";
//        $json->features = [$feature];
//
//        //$result = json_encode($json);
//
//
//        return response()->json($json);
//    }


    /**
     * 所有网格列表
     * @return mixed
     */
    public function grid_manage(){
        $user = DB::table('grid')->select('manager_id','manager_name')->groupBy('manager_name')->get();
        return view('admin.gis.grid_list')
            ->with('title','网格地图-网格管理')
            ->with('homeNav','网格地图')
            ->with('homeLink','/gps_grid_manage')
            ->with('subNav','')
            ->with('activeNav','网格管理')
            ->with('user',$user)
            ->with('menus',getMenus());
    }


    /**
     * 获取网格数据
     * @param Request $request
     */
    public function grid_list_data(Request $request){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $short_name = trim(Input::get('short_name'));
        $level = trim(Input::get('level'));
        $manager_name = trim(Input::get('manager_name'));
        $manager_id = trim(Input::get('manager_id'));

        $query = DB::table('grid as g');

        if(!empty($sSearch)){
            $query->where('g.name','like','%'.$sSearch.'%');
        }

        if(!empty($short_name)){
            $query->where('g.short_name','like','%'.$short_name.'%');
        }
        if(!empty($level)){
            $query->where('g.level',$level);
        }
        if(!empty($manager_id)&&!empty($manager_name)){
            $query->where('g.manager_name','like','%'.$manager_name.'%');
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->leftJoin('grid as g1','g.parent_id','=','g1.id')
            ->select('g1.short_name as parent_name','g.parent_id','g.name','g.short_name','g.id','g.manager_id','g.manager_name','g.grid_number','g.level')
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
     * 网格管理
     * 添加或修改页面
     */
    public function grid_manage_page(){
        $grid_id = input::get('grid_id');
        if(!empty($grid_id)){//判断是否为修改
            $grid_info = DB::table('grid')->where('id',$grid_id)->first();
        }
        $user_info = DB::table('user')->where('status',0)->select('id','name')->get();
        return view('admin.gis.grid_manage')
            ->with('title','网格设置')
            ->with('homeNav','网格地图')
            ->with('homeLink','/gps_grid_manage')
            ->with('subNav','')
            ->with('activeNav','网格管理')
            ->with('user',$user_info)
            ->with('grid_info',isset($grid_info)?$grid_info:'')
            ->with('menus',getMenus());
    }

    /**
     * 获取父级网格
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid_parent(Request $request){
        $level = $request->input('data');
        $grid_info = DB::table('grid')->where('level','=',($level-1))->select('id','name')->get();
        if(!empty($grid_info)){
            return responseToJson(0,'',$grid_info);
        }else{
            return responseToJson(1,'',$grid_info);
        }
    }

    /**
     * 删除网格
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del_grid(Request $request){
        $grid_id = $request->input("grid_id");
        $find_res = DB::table('grid')->where('parent_id',$grid_id)->count();
        if($find_res>0){
            return responseToJson(1,'','有直属网格！');
        }
        $del_res = DB::table('grid')->where('id',$grid_id)->delete();
        if(!empty($del_res)){
            return responseToJson(0,'','删除成功');
        }else{
            return responseToJson(1,'','修改失败');
        }
    }

    /**
     * 网格添加或修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid_set(Request $request){
        $data = $request->all();
        $grid_id = $data['grid_id'];
        unset($data['grid_id']);
        if(empty($grid_id)){
            DB::beginTransaction();
            $set_res = DB::table('grid')->insert($data);
            if(!empty($set_res)){
                $grid_user['user_id'] = $data['manager_id'];
                $grid_user['grid_id'] = $set_res;
                $grid_user['is_manager'] = 1;
                $res = DB::table('grid_user')->insert($grid_user);
                if(!empty($res)){
                    DB::commit();
                    return responseToJson(0,'','添加成功');
                }
            }
            DB::rollback();
            return responseToJson(1,'','添加失败');
        }else{
            DB::table('grid')->where('id',$grid_id)->update($data);
            //
            DB::table('grid_user')->where('grid_id',$grid_id)->delete();
            DB::table('grid_user')->insert(['is_manager'=>1,'update_time'=>millisecond(),'user_id'=>$data['manager_id'],'grid_id'=>$grid_id]);


//            DB::beginTransaction();
//            $find_manager_id = DB::table('grid')->where('id',$grid_id)->select('manager_id')->first();
//            DB::table('grid')->where('id',$grid_id)->update($data);
//
//
//            if(!empty($set_res)){
//                if($find_manager_id==$data['manager_id']){
//                    DB::commit();
//                    return responseToJson(0,'','修改成功');
//                }else{
//                    $grid_user['user_id'] = $data['manager_id'];
//                    $res = DB::table('grid_user')->where('gird_id',$grid_id)->insert($grid_user);
//                    if($res){
//                        DB::commit();
//                        return responseToJson(0,'','修改成功');
//                    }
//                }
//            }
//            DB::rollback();
            return responseToJson(0,'修改改成功','修改成功');
        }
    }

    /**
     * @return mixed
     * 网格地图
     */
    public function map(){
//        $result = Event::auto_process_event('001');
//        dd($result);
        return view('admin.gis.grid_draw_map')
            ->with('title','首页')
            ->with('homeNav','首页')
            ->with('homeLink','/test')
            ->with('subNav','')
            ->with('activeNav','首页')
            ->with('menus',getMenus());
    }


    /**
     * @param Request $request
     * 根据地址获得经纬坐标
     */
    public function address_point(Request $request){

//        $contact_address = DB::table('event')->where('longitude','=',0)->where('latitude','=',0)->select('id','province','city','district','address')->get();
//        foreach($contact_address as $val){
//            $address = $val->province.$val->city.$val->district.$val->address;
//            $point = Gis::geo_point($address);
//            $data['longitude']=$point['lng'];
//            $data['latitude']=$point['lat'];
//            DB::table('event')->where('id',$val->id)->update($data);
//        }

        $address = $request->input('address');
//        dump($address);
        $point = Gis::geo_point($address);
        dump($point);
    }

    //获得父级网格及下级网格信息【获得地图信息】
    public function parent_info(Request $request){
        $parent_id = $request->input('parent_id');
        $info = DB::table('grid')->where('id',$parent_id)->orwhere('parent_id',$parent_id)->select('map')->get();
        if(empty($info)){
            return responseToJson(1,'','无数据');
        }else{
            return responseToJson(0,'',$info);
        }
    }


    /**
     * 机构管理
     */
    public function grid_org(){
        return view('admin.gis.grid_org')
            ->with('title','机构管理')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','机构管理')
            ->with('menus',getMenus());
    }


    /**
     * 获得机构信息
     */
    public function grid_org_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $mobile = trim(Input::get("mobile"));
        $manager = trim(Input::get("manager"));
        $manager_mobile = trim(Input::get("manager_mobile"));
        $grid_1 = trim(Input::get("grid_1"));
        $grid_2 = trim(Input::get("grid_2"));
        $grid_3 = trim(Input::get("grid_3"));
        $grid_4 = trim(Input::get("grid_4"));

        $query = DB::table('grid_org as g_o');
        $query->leftJoin('grid as g','g.id','=','g_o.grid_id');

        $grid_info = null;
        if(!empty($grid_1)){
            if(!empty($grid_2)){
                if(!empty($grid_3)){
                    if(!empty($grid_4)){
                        $query->where('g_o.grid_id',$grid_4);
                    }else{
                        $grid_info = DB::table('grid as g4','g4.id')
                            ->leftJoin('grid as g3','g3.id','=','g4.parent_id')
                            ->where('g3.id',$grid_3)
                            ->select("g4.id")
                            ->get();
                    }
                }else{
                    $grid_info = DB::table('grid as g4','g4.id')
                        ->leftJoin('grid as g3','g3.id','=','g4.parent_id')
                        ->leftJoin('grid as g2','g2.id','=','g3.parent_id')
                        ->where('g2.id',$grid_2)
                        ->select("g4.id")
                        ->get();
                }
            }else{
                $grid_info = DB::table('grid as g4','g4.id')
                    ->leftJoin('grid as g3','g3.id','=','g4.parent_id')
                    ->leftJoin('grid as g2','g2.id','=','g3.parent_id')
                    ->leftJoin('grid as g1','g1.id','=','g2.parent_id')
                    ->where('g1.id',$grid_1)
                    ->select("g4.id")
                    ->get();
            }
        }

        if(!empty($grid_info)){
            $grid = array();
            foreach($grid_info as $val){
                array_push($grid,$val->id);
            }
            $query->whereIn('g_o.grid_id',$grid);
        }

        if(!empty($sSearch)){
            $query->where('g_o.name','like','%'.$sSearch.'%');
        }

        if(!empty($mobile)){
            $query->where('mobile',$mobile);
        }

        if(!empty($manager)){
            $query->where('manager','like','%'.$manager.'%');
        }

        if(!empty($manager_mobile)){
            $query->where('manager_mobile',$manager_mobile);
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('g_o.id','desc')
            ->select('g_o.*','g.short_name')
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
     * 机构管理设置【添加 or 修改】
     */
    public function grid_org_set(Request $request){
        $id = $request->get('org_id',0);
        $org_info = null;
        $grid_info = null;
        $grid_info_1 = null;
        $grid_info_2 = null;
        $grid_info_3 = null;
        $grid_info_4 = null;
        if(!empty($id)){
            $org_info = DB::table('grid_org')
                ->where('id',$id)
                ->first();
            $grid_info = DB::table('grid_org as g_o')
                ->leftJoin('grid as g4','g4.id','=','g_o.grid_id')
                ->leftJoin('grid as g3','g3.id','=','g4.parent_id')
                ->leftJoin('grid as g2','g2.id','=','g3.parent_id')
                ->leftJoin('grid as g1','g1.id','=','g2.parent_id')
                ->where('g_o.id',$id)
                ->select('g_o.grid_id','g3.id as grid_3','g2.id as grid_2','g1.id as grid_1')
                ->first();
            $grid_info_1 = DB::table('grid')->where('parent_id',0)->select('id','short_name')->get();
            $grid_info_2 = DB::table('grid')->where('parent_id',$grid_info->grid_1)->select('id','short_name')->get();
            $grid_info_3 = DB::table('grid')->where('parent_id',$grid_info->grid_2)->select('id','short_name')->get();
            $grid_info_4 = DB::table('grid')->where('parent_id',$grid_info->grid_3)->select('id','short_name')->get();
        }
        return view('admin.gis.grid_org_set')
            ->with('title','机构管理')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','机构管理')
            ->with('org_info',$org_info)
            ->with('grid_info',$grid_info)
            ->with('grid_info_1',$grid_info_1)
            ->with('grid_info_2',$grid_info_2)
            ->with('grid_info_3',$grid_info_3)
            ->with('grid_info_4',$grid_info_4)
            ->with('menus',getMenus());
    }

    /**
     * 机构添加 or修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid_org_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['create_time'] = time();
        if(empty($obj)){
            $save_res = DB::table('grid_org')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('grid_org')
                ->where('id',$obj)
                ->update($data);
            if(!empty($add_res)){
                return responseToJson(0,'已修改');
            }else{
                return responseToJson(1,'修改失败，请重试！');
            }
        }
    }

    /**
     * 删除机构信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid_org_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('grid_org')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }


    /**
     * 职员管理
     */
    public function employee(){
        return view('admin.gis.employee')
            ->with('title','职责管理')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','职责管理')
            ->with('menus',getMenus());
    }


    /**
     * 获得职员信息
     */
    public function employee_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $mobile = trim(Input::get("mobile"));
        $post = trim(Input::get("post"));
        $age_limit_min = intval(trim(Input::get("age_limit_min")));
        $age_limit_max = intval(trim(Input::get("age_limit_max")));
        $grid_1 = trim(Input::get("grid_1"));
        $grid_2 = trim(Input::get("grid_2"));
        $grid_3 = trim(Input::get("grid_3"));
        $grid_4 = trim(Input::get("grid_4"));

        $query = DB::table('employee_user as e');
        $query->leftJoin('population as p','e.population_id','=','p.id');

        if(!empty($grid_1)){
            $query->where('p.grid_1',$grid_1);
        }
        if(!empty($grid_2)){
            $query->where('p.grid_2',$grid_2);
        }
        if(!empty($grid_3)){
            $query->where('p.grid_3',$grid_3);
        }
        if(!empty($grid_4)){
            $query->where('p.grid_4',$grid_4);
        }

        if(!empty($sSearch)){
            $query->where('e.name','like','%'.$sSearch.'%');
        }

        if(!empty($mobile)){
            $query->where('mobile',$mobile);
        }

        if(!empty($post)){
            $query->where('post','like','%'.$post.'%');
        }

        if(!empty($age_limit_min)){
            $query->where('age_limit','>=',$age_limit_min);
        }
        if(!empty($age_limit_max)){
            $query->where('age_limit','<=',$age_limit_max);
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('e.id','desc')
            ->select('e.*','p.card_code')
            ->get();

        foreach($list as $key=>$val){
            if($val->is_party==1){
                $list[$key]->is_party = '是';
            }else{
                $list[$key]->is_party = '否';
            }
            if($val->sex==1){
                $list[$key]->sex = '男';
            }elseif($val->sex==2){
                $list[$key]->sex = '女';
            }elseif($val->sex==9){
                $list[$key]->sex = '未说明';
            }else{
                $list[$key]->sex = '未知';
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
     * 职员管理设置【添加 or 修改】
     */
    public function employee_set(Request $request){
        $id = $request->get('employee_id',0);
        $employee_info = null;
        $label_data = null;
        if(!empty($id)){
            $employee_info = DB::table('employee_user as e')
//                ->leftJoin('population as p','e.population_id','=','p.id')
                ->where('e.id',$id)
//                ->select('e.id','e.population_id','e.unit','e.post','e.age_limit','e.mobile','e.sex','p.card_code','e.name','e.is_party')
                ->first();
            $label_data = self::get_label($employee_info->population_id);
        }
        return view('admin.gis.employee_set')
            ->with('title','职员管理')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','职员管理')
            ->with('employee_info',$employee_info)
            ->with('label_data',$label_data)
            ->with('menus',getMenus());
    }

    /**
     * 获得职员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function employee_get_info(Request $request){
        $name = trim($request->input('name'));
        $employee_info = DB::table('population')
            ->where('name',"like","%".$name."%")
            ->where('status','=','0')
            ->select('id','name','card_code','is_party','sex','contact_address','contact_phone')
            ->limit(10)
            ->get();
        if(empty($employee_info)){
            return responseToJson(1,'查无此人');
        }else{
            foreach($employee_info as $key=>$val){
                $label_data = self::get_label($val->id);
                $employee_info[$key]->label = $label_data;
            }
            return responseToJson(0,$employee_info);
        }
    }

    /**
     * 获取职员标签信息
     * @param $population_id
     * @return array|string
     */
    public function get_label($population_id){
        $label = DB::table('person_relation_label as p')
            ->join('label as l','p.label_id','=','l.id')
            ->select('l.name')
            ->where('population_id',$population_id)
            ->get();
        $label_data = array();
        foreach($label as $val){
            array_push($label_data,$val->name);
        }
        $label_data = implode(',',$label_data);
        return $label_data;
    }

    /**
     * 职员添加 or修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function employee_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['create_time'] = time();

        //标签处理
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
        $label_data = array();
        foreach($label_arr as $key=>$val){
            $label_data[$key]['label_id'] = $val[0];
            $label_data[$key]['population_id'] = $data['population_id'];
        }
        DB::table('person_relation_label')->where('population_id',$data['population_id'])->delete();
        DB::table('person_relation_label')->insert($label_data);
        unset($data['label']);

        //基本信息处理
        if(empty($obj)){
            $save_res = DB::table('employee_user')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('employee_user')
                ->where('id',$obj)
                ->update($data);
            if(!empty($add_res)){
                return responseToJson(0,'已修改');
            }else{
                return responseToJson(1,'修改失败，请重试！');
            }
        }
    }

    /**
     * 删除职员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function employee_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('employee_user')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }


    /**
     * 人员管理
     * @return mixed
     */
    public function population(){
        return view('admin.gis.population')
            ->with('title','人员管理')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','人员管理')
            ->with('menus',getMenus());
    }

    /**
     * 获得职员信息
     */
    public function population_data(){
        $sEcho = intval(trim(Input::get("sEcho")));
        $iDisplayStart = intval(trim(Input::get("iDisplayStart")));
        $iDisplayLength = intval(trim(Input::get("iDisplayLength")));
        $sSearch = trim(Input::get("sSearch"));

        $contact_address = trim(Input::get("contact_address"));
        $contact_phone = trim(Input::get("contact_phone"));
//        $age_limit_min = intval(trim(Input::get("age_limit_min")));
//        $age_limit_max = intval(trim(Input::get("age_limit_max")));
        $grid_1 = trim(Input::get("grid_1"));
        $grid_2 = trim(Input::get("grid_2"));
        $grid_3 = trim(Input::get("grid_3"));
        $grid_4 = trim(Input::get("grid_4"));

        $query = DB::table('person_user as e');
        $query->leftJoin('population as p','e.population_id','=','p.id');

        if(!empty($grid_1)){
            $query->where('p.grid_1',$grid_1);
        }
        if(!empty($grid_2)){
            $query->where('p.grid_2',$grid_2);
        }
        if(!empty($grid_3)){
            $query->where('p.grid_3',$grid_3);
        }
        if(!empty($grid_4)){
            $query->where('p.grid_4',$grid_4);
        }

        if(!empty($sSearch)){
            $query->where('e.name','like','%'.$sSearch.'%');
        }

        if(!empty($contact_phone)){
            $query->where('e.mobile',$contact_phone);
        }

        if(!empty($contact_address)){
            $query->where('e.contact_address','like','%'.$contact_address.'%');
        }

        $count = $query->count();
        $list= $query
            ->skip($iDisplayStart)->take($iDisplayLength)
            ->orderBy('e.id','desc')
            ->select('e.*','p.card_code')
            ->get();

        foreach($list as $key=>$val){
            if($val->is_party==1){
                $list[$key]->is_party = '是';
            }else{
                $list[$key]->is_party = '否';
            }
            if($val->sex==1){
                $list[$key]->sex = '男';
            }elseif($val->sex==2){
                $list[$key]->sex = '女';
            }elseif($val->sex==9){
                $list[$key]->sex = '未说明';
            }else{
                $list[$key]->sex = '未知';
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
     * 人员管理设置【添加 or 修改】
     */
    public function population_set(Request $request){
        $id = $request->get('person_id',0);
        $employee_info = null;
        $label_data = null;
        if(!empty($id)){
            $employee_info = DB::table('person_user as e')
//                ->leftJoin('population as p','e.population_id','=','p.id')
                ->where('e.id',$id)
//                ->select('e.id','e.population_id','e.unit','e.post','e.age_limit','e.mobile','e.sex','p.card_code','e.name','e.is_party')
                ->first();
            $label_data = self::get_label($employee_info->population_id);
        }
        return view('admin.gis.person_set')
            ->with('title','人员管理')
            ->with('homeNav','Gis中心')
            ->with('homeLink','/gps_index')
            ->with('subNav','')
            ->with('activeNav','人员管理')
            ->with('person_info',$employee_info)
            ->with('label_data',$label_data)
            ->with('menus',getMenus());
    }

    /**
     * 人员添加 or修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function population_info_set(Request $request){
        $data = $request->all();
        $obj = $data['obj'];
        $data = array_except($data, ['obj']);
        $data['create_time'] = time();

        //标签处理
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
        $label_data = array();
        foreach($label_arr as $key=>$val){
            $label_data[$key]['label_id'] = $val[0];
            $label_data[$key]['population_id'] = $data['population_id'];
        }
        DB::table('person_relation_label')->where('population_id',$data['population_id'])->delete();
        DB::table('person_relation_label')->insert($label_data);
        unset($data['label']);

        //基本信息处理
        if(empty($obj)){
            $save_res = DB::table('person_user')->insertGetId($data);
            if(empty($save_res)){
                return responseToJson(1,'添加失败');
            }else{
                return responseToJson(0,'添加成功');
            }
        }else{
            $add_res = DB::table('person_user')
                ->where('id',$obj)
                ->update($data);
            if(!empty($add_res)){
                return responseToJson(0,'已修改');
            }else{
                return responseToJson(1,'修改失败，请重试！');
            }
        }
    }

    /**
     * 删除人员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function population_del(Request $request){
        $obj = $request->input('obj');
        $place_info = DB::table('person_user')
            ->where('id',$obj)
            ->delete();
        if(!empty($place_info)){
            return responseToJson(0,'true');
        }else{
            return responseToJson(1,'false');
        }
    }
}
